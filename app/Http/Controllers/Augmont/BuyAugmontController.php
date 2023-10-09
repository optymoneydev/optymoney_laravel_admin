<?php

namespace App\Http\Controllers\Augmont;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Augmont\AugmontController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\KycController;
use App\Http\Controllers\Payments\RazorpayController;
use App\Http\Controllers\Payments\RazorpaySIPController;
use App\Http\Controllers\Augmont\InvoiceAugmontController;
use App\Http\Controllers\Augmont\RatesAugmontController;
use App\Http\Controllers\Augmont\User\UserAugmontController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\Nsdl\SignzyController;
use App\Http\Controllers\Nsdl\NsdlController;
use App\Http\Controllers\Augmont\SIPAugmontController;
use GuzzleHttp\Client;
use Redirect;
use Session;
use View;
use \stdClass;
Use hash_hmac;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
Use App\Models\AugmontOrders;
Use App\Models\Bfsi_user;
Use App\Models\Bfsi_users_detail;
Use App\Models\KycStatus;
use Illuminate\Support\Str;

class BuyAugmontController extends Controller
{
    public function buyAugmont(Request $request) {
        $id = $request->session()->get('id');
        $userProfile = (new UsersController)->getUserDataByUID($id);

        if(Str::contains(url()->current(), 'silverBuy')) {
            $data = ["metal" => "silver", "silverGrams" => $request->silverGrams, "silverAmount" => $request->silverAmount, "silverPrice" => $request->silverPrice, "silverGST" => $request->silverGST, "silverBlockId" => $request->silverBlockId];
            $amt = $request->silverAmount;
            $redirectURL = 'augmont.buysilver';
        } else {
            if(Str::contains(url()->current(), 'goldBuy')) {
                $data = ["metal" => "gold", "goldGrams" => $request->goldGrams, "goldAmount" => $request->goldAmount, "goldPrice" => $request->goldPrice, "goldGST" => $request->goldGST, "goldBlockId" => $request->goldBlockId];
                $amt = $request->goldAmount;
                $redirectURL = 'augmont.buygold';
            }
        }
        $data["kycRequired"] = $this->getPurchaseAmount($id, $amt);
        $data["redirectURL"] = $redirectURL;
        Session::put('orderData', $data);
        if($userProfile->augid!=null) {
            if($data["kycRequired"]==false) {
                return View::make($redirectURL, $data);
            } else {
                $augKycStatus = (new KycController)->aug_kyc_db_check($request);
                if($augKycStatus=="pending" || $augKycStatus=="rejected") {    
                    $prevPath = (new GeneralController)->previousPath();
                    return redirect($prevPath)->with('augKycStatus', $augKycStatus);
                } else {
                    if($augKycStatus=="approved") {
                        return View::make($redirectURL, $data);
                    } else {
                        if($augKycStatus==null || $augKycStatus=="") {
                            return redirect('/augmont/kyc')->with('status', $data);
                        }
                    }
                }
            }
        } else {
            if($userProfile->augcity==null || $userProfile->augstate==null || $userProfile->augcity=="" || $userProfile->augstate=="" || $userProfile->dob==null || $userProfile->dob=="") {
                return redirect('/augmont/augmontReq')->with('status', $data);
            } else {
                $augid = (new UserAugmontController)->checkUpdateAugmont($userData[0]);
                if($augid!="" || $augid!=NULL) {
                    return View::make($redirectURL, $data);
                }
            }
        }
    }

    public function buySIPAugmont(Request $request) {
        $request->session()->forget('url');
        $id = $request->session()->get('id');
        $userProfile = (new UsersController)->getUserDataByUID($id);
        if(Str::contains(url()->current(), 'silverSipBuy')) {
            if(Str::contains($request->silverSipDate, '/')) {
                $newDate = \Carbon\Carbon::createFromFormat('m/d/Y', $request->silverSipDate)
                        ->format('Y-m-d');
                $cycleDate = Carbon::createFromFormat('m/d/Y', $request->silverSipDate)->format('d');
                $request->silverSipDate = $newDate;
            }
            $data = [
                "silverSipDate" => $request->silverSipDate, 
                "silverSipCycleDate" => $cycleDate,
                "silverSipInvestmentPurpose" => $request->silverSipInvestmentPurpose, 
                "metalType" => "silver", 
                "silverSipAmount" => $request->silverSipAmount,
                "silverSipPrice" => $request->silverSipPrice, 
                "silverSipGST" => $request->silverSipGST, 
                "silverSipBlockId" => $request->silverSipBlockId, 
                "silverSipDate" => $request->silverSipDate,
                "trans_type" => "sip",
            ];
            $redirectURL = 'augmont.buysipsilver';
        } else {
            if(Str::contains(url()->current(), 'goldSipBuy')) {
                if(Str::contains($request->goldSipDate, '/')) {
                    $newDate = \Carbon\Carbon::createFromFormat('m/d/Y', $request->goldSipDate)
                            ->format('Y-m-d');
                    $cycleDate = Carbon::createFromFormat('m/d/Y', $request->goldSipDate)->format('d');
                    $request->goldSipDate = $newDate;
                }
                $data = [
                    "goldSipDate" => $request->goldSipDate, 
                    "goldSipCycleDate" => $cycleDate,
                    "goldSipInvestmentPurpose" => $request->goldSipInvestmentPurpose, 
                    "metalType" => "gold", 
                    "goldSipAmount" => $request->goldSipAmount,
                    "goldSipPrice" => $request->goldSipPrice, 
                    "goldSipGST" => $request->goldSipGST, 
                    "goldSipBlockId" => $request->goldSipBlockId, 
                    "goldSipDate" => $request->goldSipDate,
                    "trans_type" => "sip"
                ];
                $redirectURL = 'augmont.buysipgold';
            }
        }
        $data["redirectURL"] = $redirectURL;
        $data['kycRequired']=true;
        Session::put('orderData', $data);
        if($userProfile->augid!=null) {
            if($userProfile->pan_number==null) {
                return redirect('/augmont/kyc');
            } else {
                $nsdl_response = (new NsdlController)->getPasscodeEncyrt($userProfile->pan_number);
                if(isset($nsdl_response['data']['APP_NAME'])) {
                    if($userProfile->cust_name!=$nsdl_response['data']['APP_NAME']) {
                        $upstatus = Bfsi_users_detail::where('fr_user_id', $id)->update([
                            'cust_name' => $nsdl_response['data']['APP_NAME']
                        ]);
                        $userProfile->cust_name = $nsdl_response['data']['APP_NAME'];
                    }
                }
                $result = (new NsdlController)->nsdlResponseUpdate($nsdl_response, $request);
                $augKycStatus = (new KycController)->aug_kyc_db_check($request);
                if($augKycStatus=="pending" || $augKycStatus=="rejected") {    
                    $prevPath = (new GeneralController)->previousPath();
                    return redirect($prevPath)->with('augKycStatus', $augKycStatus);
                } else {
                    if($augKycStatus=="approved") {
                        return View::make($redirectURL, $data);
                    } else {
                        if($augKycStatus==null || $augKycStatus=="") {
                            return redirect('/augmont/kyc')->with('status', $data);
                        }
                    }
                }
            }

            
        } else {
            if($userProfile->augcity==null || $userProfile->augstate==null || $userProfile->augcity=="" || $userProfile->augstate=="" || $userProfile->dob==null || $userProfile->dob=="") {
                return redirect('/augmont/augmontReq')->with('status', $data);
            } else {
                $augid = (new UserAugmontController)->checkUpdateAugmont($userData[0]);
                if($augid!="" || $augid!=NULL) {
                    return View::make($redirectURL, $data);
                }
            }
        }
    }

    public function createOrder(Request $request) {
        $orderData = session()->get('orderData');
        if(Arr::exists($orderData, 'razorpayOrderId')) {
            if($orderData['razorpayOrderId']!="") {
                if(Arr::exists($orderData, 'errors')) {
                    $error_key = "";
                    foreach($orderData['errors'] as $key => $value) {
                        if($key=="block_rate") {
                            $error_key = $key;
                        }
                    } 
                    if($error_key=="block_rate") {
                        $augmontOrder = AugmontOrders::where([
                            'razorpayOrderId' => $orderData['razorpayOrderId'],
                        ])->first();
                        $augOrderRes = $this->createAugmontOrder($augmontOrder);
                    } else {
                        return '/dashboard/index';
                    }
                }
                
            }
        } else {
            $general = new GeneralController();
            $razorpay = new RazorpayController();

            $data = $request->all();
            $id = $request->session()->get('id');
            $merchantTransactionId = "AUGOM_".$id."_".$general->uniqueNumericId(5)."_".$general->uniqueNumericId(10);
            
            $userData = Bfsi_user::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'bfsi_user.pk_user_id')
                ->where('bfsi_user.pk_user_id', $request->session()->get('id'))
                ->get(['bfsi_user.*', 'bfsi_users_details.*']);
            
            if($userData[0]->augid==null) {
                $userData[0]->augid = (new UserAugmontController)->checkUpdateAugmont($userData[0]);
            }
            $orderData['totalAmount'] = $data['totalAmount'];
            session()->put('orderData',$orderData);
            $razorRes = $razorpay->createOrder($data, $merchantTransactionId, $userData[0], str_replace('.','', number_format($data['totalAmount'], 2, '.', '')));

            $up_stat = $this->insertOrder($id, 'Buy', $userData, $data, $razorRes, $merchantTransactionId);
            
            $post_data = new stdClass();
            $post_data->id = $razorRes->id;
            $post_data->entity = $razorRes->entity;
            $post_data->amount = $razorRes->amount;
            $post_data->amount_paid = $razorRes->amount_paid;
            $post_data->amount_due = $razorRes->amount_due;

            $post_data->key = env('RAZORPAY_KEY'); // Enter the Key ID generated from the Dashboard
            $post_data->amount = $razorRes->amount; // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
            $post_data->currency = "INR";
            $post_data->name = "Optymoney";
            $post_data->description = "Transaction : ".$merchantTransactionId;
            $post_data->image = "https://optymoney.com/static/opty_theme/img/optymoney_icon.png";
            $post_data->order_id = $razorRes->id; //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
            $post_data->callback_url = url('/augmont/orderResponse');
            $post_data->prefill = array(
                'name'=>$userData[0]->cust_name,
                'email' => $userData[0]->login_id, 
                'contact'=>$userData[0]->contact
            );
            $post_data->customer = array(
                'name'=>$userData[0]->cust_name,
                'email' => $userData[0]->login_id, 
                'contact'=>$userData[0]->contact
            );
            $post_data->notes = array(
                "address"=> "Razorpay Corporate Office",
                "descr" =>$merchantTransactionId
            );
            $post_data->theme = array(
                "color"=> "#D33633"
            );
            // $post_data->readonly = array(
            //     "contact"=> true,
            //     "email"=> true,
            //     "name"=> true
            // );
            // dd($razorRes);
            return json_encode($post_data);
        }        
    }

    public function createSipOrder(Request $request) {
        $general = new GeneralController();
        $razorpaysip = new RazorpaySIPController();
        $data = $request->all();
        $orderData = session()->get('orderData');
        $id = $request->session()->get('id');
        if(Arr::exists($orderData, 'razorpayOrderId')) {
            if($orderData['razorpayOrderId']!="") {
                if(Arr::exists($orderData, 'errors')) {
                    $error_key = "";
                    foreach($orderData['errors'] as $key => $value) {
                        if($key=="block_rate") {
                            $error_key = $key;
                        }
                    } 
                    if($error_key=="block_rate") {
                        $augmontOrder = AugmontOrders::where([
                            'razorpaySubscriptionId' => $orderData['razorpayOrderId'],
                        ])->first();
                        $augOrderRes = $this->createAugmontOrder($augmontOrder);
                    } else {
                        return '/dashboard/index';
                    }
                }
                
            }
        } else {
            $merchantTransactionId = "AUGOM_".$id."_".$general->uniqueNumericId(5)."_".$general->uniqueNumericId(10);
            
            $userData = Bfsi_user::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'bfsi_user.pk_user_id')
                ->where('bfsi_user.pk_user_id', $request->session()->get('id'))
                ->get(['bfsi_user.*', 'bfsi_users_details.*']);

            $razorAmtFormat = str_replace('.','', number_format($data['amount'], 2, '.', ''));
            $razorRes = $razorpaysip->sip_payment($data, $merchantTransactionId, $userData[0], $razorAmtFormat);
            $orderData['razorpayOrderId'] = $razorRes->id;
            session()->put('orderData',$orderData);
            $up_stat = $this->insertOrder($id, 'SIP', $userData, $data, $razorRes, $merchantTransactionId);
            
            $post_data = new stdClass();
            $post_data->key = env('RAZORPAY_SIP_KEY'); // Enter the Key ID generated from the Dashboard
            $post_data->subscription_id = $razorRes->id;
            $post_data->name = $razorRes->notes->name;
            $post_data->description = $razorRes->notes->description.". Auth txn for ".$razorRes->id;
            $post_data->image = "https://optymoney.com/static/opty_theme/img/optymoney_icon.png";
            $post_data->prefill = array(
                'name'=>$userData[0]->cust_name,
                'email' => $userData[0]->login_id, 
                'contact'=>$userData[0]->contact
            );
            $post_data->callback_url = url('/augmont/sipOrderResponse');
            $post_data->notes = array(
                "address"=> "Razorpay Corporate Office",
                "descr" =>$merchantTransactionId
            );
            $post_data->theme = array(
                "color"=> "#D33633"
            );
            // $post_data->readonly = array(
            //     "contact"=> true,
            //     "email"=> true,
            //     "name"=> true
            // );
            return json_encode($post_data);    
        }
    }

    public function saveOrder(Request $request) {
        $general = new GeneralController();
        $razorpay = new RazorpayController();

        $data = $request->all();
        $id = $request->session()->get('id');
        $merchantTransactionId = "AUGOM_".$id."_".$general->uniqueNumericId(5)."_".$general->uniqueNumericId(10);
        
        $userData = Bfsi_user::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'bfsi_user.pk_user_id')
              ->where('bfsi_user.pk_user_id', $request->session()->get('id'))
              ->get(['bfsi_user.*', 'bfsi_users_details.*']);
        
        $orderData = session()->get('orderData');
        $orderData['totalAmount'] = $data['totalAmount'];
        session()->put('orderData',$orderData);
        dd(session()->get('orderData'));
        $razorRes = $razorpay->payment($data, $merchantTransactionId, $userData[0], str_replace('.','', number_format($data['totalAmount'], 2, '.', '')));

        $up_stat = $this->insertOrder($id, 'Buy', $userData, $data, $razorRes, $merchantTransactionId);
        // dd(str_replace('.','', number_format($data['totalAmount'], 2, '.', '')));
        return response()->json(['success'=>$razorRes->short_url]);
    }

    public function saveSipOrder(Request $request) {
        $general = new GeneralController();
        $razorpaysip = new RazorpaySIPController();

        $data = $request->all();
        $id = $request->session()->get('id');
        $merchantTransactionId = "AUGOM_SIP_".$id."_".$general->uniqueNumericId(5)."_".$general->uniqueNumericId(10);
        
        $userData = Bfsi_user::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'bfsi_user.pk_user_id')
              ->where('bfsi_user.pk_user_id', $request->session()->get('id'))
              ->get(['bfsi_user.*', 'bfsi_users_details.*']);
        
        $orderData = session()->get('orderData');
        $orderData['totalAmount'] = $data['totalAmount'];
        session()->put('orderData',$orderData);
        $razorRes = $razorpaysip->sip_payment($data, $merchantTransactionId, $userData[0]->pk_user_id);

        $up_stat = $this->insertOrder($id, 'SIP', $userData, $data, $razorRes, $merchantTransactionId);
        // dd(str_replace('.','', number_format($data['totalAmount'], 2, '.', '')));
        return response()->json(['success'=>$razorRes->short_url]);
    }

    public function postOrderToAugmont($form_params) {
        $tokentype = "Bearer ";
        $authToken = $tokentype.(new AugmontController)->merchantAuth();

        if($authToken==401) {
            return 401;
            // return json_encode({
            //     "statusCode": 401,
            //     "message": "You are not authrorized to perform this request."
            //   });
        } else {
            return (new AugmontController)->clientRequests('POST', 'merchant/v1/buy', $form_params);
        }
    }

    public function insertOrder($id, $ordertype, $userData, $augOrderData, $razorRes, $merchantTransactionId) {
        $augmontOrders = new AugmontOrders();
        $augmontOrders->user_id = $id;
        $augmontOrders->emailId = $userData[0]->login_id;
        $augmontOrders->metalType = $augOrderData['metalType'];
        $augmontOrders->merchantTransactionId = $merchantTransactionId;
        $augmontOrders->userName = $userData[0]->cust_name;
        $augmontOrders->userAddress = $userData[0]->address1.", ".$userData[0]->address2;
        $augmontOrders->userCity = $userData[0]->augcity;
        $augmontOrders->userState = $userData[0]->augstate;
        $augmontOrders->userPincode = $userData[0]->pincode;
        $augmontOrders->uniqueId = $userData[0]->augid;
        $augmontOrders->mobileNumber = $userData[0]->contact;
        $augmontOrders->ordertype = $ordertype;
        $augmontOrders->lockPrice = $augOrderData['lockPrice'];
        $augmontOrders->blockId = $augOrderData['blockId'];
        if($ordertype=="SIP") {
            $augmontOrders->razorpaySubscriptionId = $razorRes->id;
            $augmontOrders->description = $augOrderData['sipInvestmentPurpose'];
            $augmontOrders->totalAmount = $augOrderData['amount'];
        } else {
            $augmontOrders->quantity = $augOrderData['quantity'];
            $augmontOrders->razorpayOrderId = $razorRes->id;
        }
        $saveOrderStatus = $augmontOrders->save();
        if($saveOrderStatus) {
            return $augmontOrders;
        } else {
            return false;
        }
        
    }

    public function orderResponse(Request $request) {
        if($request->session()->has('orderData')) {
            $orderData = session()->get('orderData');
            $orderData['razorpayOrderId'] = $request->razorpay_order_id;
            $razorpay = new RazorpayController();
            $invoice = new InvoiceAugmontController();
            $razorPayResById = $razorpay->getSpecificPayment($request->razorpay_payment_id);
            $razorVerifyRes = $razorpay->verifySignature($request);
            // if ($razorVerifyRes) {
                $augmontOrder = AugmontOrders::where([
                    'razorpayOrderId' => $request->razorpay_order_id,
                ])->first();
                // dd($augmontOrder);
                $augmontOrder->razorpayId = $request->razorpay_payment_id;
                $augmontOrder->razorpayStatus = $razorVerifyRes;
                $saveOrderStatus = $augmontOrder->save();
                $form_params = [
                    'lockPrice' => $augmontOrder->lockPrice,
                    'emailId' => $augmontOrder->emailId,
                    'metalType' => $augmontOrder->metalType,
                    'quantity' => floatval($augmontOrder->quantity),
                    'merchantTransactionId' => $augmontOrder->merchantTransactionId,
                    'userName' => $augmontOrder->userName,
                    'userAddress' => $augmontOrder->userAddress,
                    'userCity' => $augmontOrder->userCity,
                    'userState' => $augmontOrder->userState,
                    'userPincode' => $augmontOrder->userPincode,
                    'uniqueId' => $augmontOrder->uniqueId,
                    'blockId' => $augmontOrder->blockId,
                    'mobileNumber' => $augmontOrder->mobileNumber,
                    'modeOfPayment' => $augmontOrder->modeOfPayment
                ];
                $augOrderRes = $this->postOrderToAugmont($form_params);
                $upstatus = AugmontOrders::where('razorpayOrderId', $request->razorpay_order_id)->update([
                    'description' => json_encode($augOrderRes)
                ]);
                if($augOrderRes->statusCode==422) {
                    $errors =  $augOrderRes->errors;
                    foreach ($errors as $key => $values) {
                        if($key=="blockId") {
                            foreach ($values as $key1 => $value) {
                                if($value->code == 4690) {
                                    $currentRates_content = json_decode((new RatesAugmontController)->currentRates());
                                    $currentRates = $currentRates_content->result->data;
                                    if($augmontOrder->metalType=="silver") {
                                        $form_params['lockPrice'] = $currentRates->rates->sBuy;
                                    } else {
                                        if($augmontOrder->metalType=="gold") {
                                            $form_params['lockPrice'] = $currentRates->rates->gBuy;
                                        }   
                                    }
                                    $form_params['blockId'] = $currentRates->blockId;
                                }
                            }
                            $augOrderRes = $this->postOrderToAugmont($form_params);
                        }
                    }
                }
                $orderData['augstatusCode'] = $augOrderRes->statusCode;
                if(isset( $augOrderRes->errors)) {
                    $orderData['errors'] = $augOrderRes->errors;
                }
                $statusCode = $augOrderRes->statusCode; 
                if($statusCode==200) {
                    session()->put('orderData',$orderData);
                    $augmontRes = $augOrderRes->result->data;
                    $augmontOrder->statusCode = "200";
                    $augmontOrder->preTaxAmount = $augmontRes->preTaxAmount;
                    $augmontOrder->transactionId = $augmontRes->transactionId;
                    $augmontOrder->goldBalance = $augmontRes->goldBalance;
                    $augmontOrder->silverBalance = $augmontRes->silverBalance;
                    $augmontOrder->totalTaxAmount = $augmontRes->taxes->totalTaxAmount;
                    $augmontOrder->taxSplit_cgst_taxPerc = $augmontRes->taxes->taxSplit[0]->taxPerc;
                    $augmontOrder->taxSplit_cgst_taxAmount = $augmontRes->taxes->taxSplit[0]->taxAmount;
                    $augmontOrder->taxSplit_sgst_taxPerc = $augmontRes->taxes->taxSplit[1]->taxPerc;
                    $augmontOrder->taxSplit_sgst_taxAmount = $augmontRes->taxes->taxSplit[1]->taxAmount;
                    $augmontOrder->invoiceNumber = $augmontRes->invoiceNumber;
                    $saveOrderStatus = $augmontOrder->save();
                    $res2 = (new EmailController)->send_purchase_success($augmontRes->transactionId);
                    $dataInv = $invoice->object_to_array($invoice->getInvoiceData($augmontRes->transactionId)->result->data);
                    // dd($dataInv);
                    session()->forget('orderData');
                    return View::make('augmont.razorpayView', $dataInv);
                } else {
                    if($statusCode=422) {
                        $orderData['message'] = "<p>The payment was successful! but the live price of gold/silver has changed slightly since the last update.</p><p>Please confirm the below details and submit (You will not be charged again).</p>";
                        session()->put('orderData',$orderData);
                        $prevPath = (new GeneralController)->previousPath();
                        // dd($orderData);
                        if(Str::contains($prevPath, 'goldBuy')) {
                            return View::make('augmont.buygold', $orderData);
                        } else {
                            return View::make('augmont.buysilver', $orderData);
                        }
                    }
                    $augmontOrder->statusCode = "200";
                    $saveOrderStatus = $augmontOrder->save();
                    // return redirect()->intended('augmont/silverBuy');
                }
            // } else {
            //     return false;
            // }
        } else {
            return redirect()->intended('dashboard/index');
        }
        
        
    }

    public function sipOrderResponse(Request $request) {
        if($request->session()->has('orderData')) {
            $orderData = session()->get('orderData');
            // dd($orderData);
            $orderData['razorpayOrderId'] = $request->razorpay_subscription_id;
            $razorpay = new RazorpayController();
            $invoice = new InvoiceAugmontController();
            $razorVerifyRes = $razorpay->verifySignature($request);
            // if ($razorVerifyRes) {
                $augmontOrder = AugmontOrders::where([
                    'razorpaySubscriptionId' => $request->razorpay_subscription_id,
                ])->first();
                $augmontOrder->razorpayId = $request->razorpay_payment_id;
                $augmontOrder->razorpayStatus = $razorVerifyRes;
                $saveOrderStatus = $augmontOrder->save();
                $form_params = [
                    'lockPrice' => $augmontOrder->lockPrice,
                    'emailId' => $augmontOrder->emailId,
                    'metalType' => $augmontOrder->metalType,
                    'amount' => floatval($augmontOrder->totalAmount),
                    'merchantTransactionId' => $augmontOrder->merchantTransactionId,
                    'userName' => $augmontOrder->userName,
                    'userAddress' => $augmontOrder->userAddress,
                    'userCity' => $augmontOrder->userCity,
                    'userState' => $augmontOrder->userState,
                    'userPincode' => $augmontOrder->userPincode,
                    'uniqueId' => $augmontOrder->uniqueId,
                    'blockId' => $augmontOrder->blockId,
                    'referenceType' => "sip",
                    'referenceId' => "aug_opty_".$request->razorpay_payment_id,
                    'mobileNumber' => $augmontOrder->mobileNumber,
                    'modeOfPayment' => $augmontOrder->modeOfPayment
                ];
                $augOrderRes = $this->postOrderToAugmont($form_params);
                if($augOrderRes->statusCode==422) {
                    $errors =  $augOrderRes->errors;
                    foreach ($errors as $key => $values) {
                        if($key=="blockId") {
                            foreach ($values as $key1 => $value) {
                                if($value->code == 4690) {
                                    $currentRates_content = json_decode((new SIPAugmontController)->sipRates());
                                    $currentRates = $currentRates_content->result->data;
                                    if($augmontOrder->metalType=="silver") {
                                        $form_params['lockPrice'] = $currentRates->rates->sBuy;
                                    } else {
                                        if($augmontOrder->metalType=="gold") {
                                            $form_params['lockPrice'] = $currentRates->rates->gBuy;
                                        }   
                                    }
                                    $form_params['blockId'] = $currentRates->blockId;
                                }
                            }
                            $augOrderRes = $this->postOrderToAugmont($form_params);
                        }
                    }
                }
                $orderData['augstatusCode'] = $augOrderRes->statusCode;
                if(isset( $augOrderRes->errors)) {
                    $orderData['errors'] = $augOrderRes->errors;
                } else {
                    $orderData['errors'] = "";
                }
                $statusCode = $augOrderRes->statusCode; 
                if($statusCode==200) {
                    session()->put('orderData',$orderData);
                    $augmontRes = $augOrderRes->result->data;
                    $augmontOrder->statusCode = "200";
                    $augmontOrder->preTaxAmount = $augmontRes->preTaxAmount;
                    $augmontOrder->transactionId = $augmontRes->transactionId;
                    $augmontOrder->goldBalance = $augmontRes->goldBalance;
                    $augmontOrder->silverBalance = $augmontRes->silverBalance;
                    $augmontOrder->totalTaxAmount = $augmontRes->taxes->totalTaxAmount;
                    $augmontOrder->taxSplit_cgst_taxPerc = $augmontRes->taxes->taxSplit[0]->taxPerc;
                    $augmontOrder->taxSplit_cgst_taxAmount = $augmontRes->taxes->taxSplit[0]->taxAmount;
                    $augmontOrder->taxSplit_sgst_taxPerc = $augmontRes->taxes->taxSplit[1]->taxPerc;
                    $augmontOrder->taxSplit_sgst_taxAmount = $augmontRes->taxes->taxSplit[1]->taxAmount;
                    $augmontOrder->invoiceNumber = $augmontRes->invoiceNumber;
                    $saveOrderStatus = $augmontOrder->save();
                    $res2 = (new EmailController)->send_purchase_success($augmontRes->transactionId);
                    $dataInv = $invoice->object_to_array($invoice->getInvoiceData($augmontRes->transactionId)->result->data);
                    // dd($dataInv);
                    session()->forget('orderData');
                    return View::make('augmont.razorpayView', $dataInv);
                } else {
                    if($statusCode=422) {
                        $orderData['message'] = "<p>The payment was successful! but the live price of gold/silver has changed slightly since the last update.</p><p>Please confirm the below details and submit (You will not be charged again).</p>";
                        session()->put('orderData',$orderData);
                        $prevPath = (new GeneralController)->previousPath();
                        // dd($orderData);
                        if(Str::contains($prevPath, 'goldBuy')) {
                            return View::make('augmont.buysipgold', $orderData);
                        } else {
                            return View::make('augmont.buysipsilver', $orderData);
                        }
                    }
                    $augmontOrder->statusCode = "200";
                    $saveOrderStatus = $augmontOrder->save();
                    // return redirect()->intended('augmont/silverBuy');
                }
            // } else {
            //     return false;
            // }
        } else {
            return redirect()->intended('dashboard/index');
        }
    }

    public function createAugmontOrder(Type $var = null) {
        $form_params = [
            'lockPrice' => $augmontOrder->lockPrice,
            'emailId' => $augmontOrder->emailId,
            'metalType' => $augmontOrder->metalType,
            'quantity' => floatval($augmontOrder->quantity),
            'merchantTransactionId' => $augmontOrder->merchantTransactionId,
            'userName' => $augmontOrder->userName,
            'userAddress' => $augmontOrder->userAddress,
            'userCity' => $augmontOrder->userCity,
            'userState' => $augmontOrder->userState,
            'userPincode' => $augmontOrder->userPincode,
            'uniqueId' => $augmontOrder->uniqueId,
            'blockId' => $augmontOrder->blockId,
            'mobileNumber' => $augmontOrder->mobileNumber,
            'modeOfPayment' => $augmontOrder->modeOfPayment
        ];
        $augOrderRes = $this->postOrderToAugmont($form_params);
        $orderData['augstatusCode'] = $augOrderRes->statusCode;
        if(isset( $augOrderRes->errors)) {
            $orderData['errors'] = $augOrderRes->errors;
        }
        $statusCode = $augOrderRes->statusCode; 
        if($statusCode==200) {
            $augmontRes = $augOrderRes->result->data;
            $augmontOrder->statusCode = "200";
            $augmontOrder->preTaxAmount = $augmontRes->preTaxAmount;
            $augmontOrder->transactionId = $augmontRes->transactionId;
            $augmontOrder->goldBalance = $augmontRes->goldBalance;
            $augmontOrder->silverBalance = $augmontRes->silverBalance;
            $augmontOrder->totalTaxAmount = $augmontRes->taxes->totalTaxAmount;
            $augmontOrder->taxSplit_cgst_taxPerc = $augmontRes->taxes->taxSplit[0]->taxPerc;
            $augmontOrder->taxSplit_cgst_taxAmount = $augmontRes->taxes->taxSplit[0]->taxAmount;
            $augmontOrder->taxSplit_sgst_taxPerc = $augmontRes->taxes->taxSplit[1]->taxPerc;
            $augmontOrder->taxSplit_sgst_taxAmount = $augmontRes->taxes->taxSplit[1]->taxAmount;
            $augmontOrder->invoiceNumber = $augmontRes->invoiceNumber;
            $saveOrderStatus = $augmontOrder->save();
            $res2 = (new EmailController)->send_purchase_success($augmontRes->transactionId);
            $dataInv = $invoice->object_to_array($invoice->getInvoiceData($augmontRes->transactionId)->result->data);
            session()->forget('orderData');
            return View::make('augmont.razorpayView', $dataInv);
        } else {
            if($statusCode=422) {
                $orderData['message'] = "<p>The payment was successful! but the live price of gold/silver has changed slightly since the last update.</p><p>Please confirm the below details and submit (You will not be charged again).</p>";
                session()->put('orderData',$orderData);
                $prevPath = (new GeneralController)->previousPath();
                // dd($orderData);
                if(Str::contains($prevPath, 'goldBuy')) {
                    return View::make('augmont.buygold', $orderData);
                } else {
                    return View::make('augmont.buysilver', $orderData);
                }
            }
            $augmontOrder->statusCode = "200";
            $saveOrderStatus = $augmontOrder->save();
            // return redirect()->intended('augmont/silverBuy');
        }
    }

    public function getPurchaseAmount($id, $amt) {
        if(\Carbon\Carbon::now()->month>3) {
            $yr = \Carbon\Carbon::now()->year;
        } else {
            $yr = \Carbon\Carbon::now()->year-1;
        }
        $s_date = '01/04/'.$yr;
        $e_date = '31/03/'.($yr+1);
        $startDate = \Carbon\Carbon::createFromFormat('d/m/Y', $s_date);
        $endDate = \Carbon\Carbon::createFromFormat('d/m/Y', $e_date);

        $availCount = AugmontOrders::where(['user_id' => $id])->whereNotNull('transactionId')->orderBy("created_at", "desc")->get(['goldBalance','silverBalance'])->first();
        if($availCount) {
            $currentRates_content = json_decode((new RatesAugmontController)->currentRates());
            $currentRates = $currentRates_content->result->data->rates;
            $sum = $currentRates->gBuy*$availCount->goldBalance+$currentRates->sBuy*$availCount->silverBalance;
        } else {
            $sum=0.00;
        }
        $totalPurchase = floatval($sum)+floatval($amt);
        if(floatval($totalPurchase)>180000) {
            return true;
        } else {
            return false;
        }
    }

    public function manualPostOrder(Request $request) {
        $invoice = new InvoiceAugmontController();
        $orderAugmontcrtl = new OrdersAugmontController();
        $general = new GeneralController();
        $orderData = $orderAugmontcrtl->OrdersByTransactionId($request->ao_orders);
        $upstatus = AugmontOrders::where('id', $request->ao_orders)->update([
            'razorpayId' => $request->ao_transaction_id
        ]);
        
        $mop = $request->ao_mop;
        $purchasedPrice = $orderData->preTaxAmount;
        $form_params = [
            'lockPrice' => $orderData->lockPrice,
            'emailId' => $orderData->emailId,
            'metalType' => $orderData->metalType,
            'quantity' => $orderData->quantity,
            'merchantTransactionId' => $orderData->merchantTransactionId,
            'userName' => $orderData->userName,
            'userAddress' => $orderData->userAddress,
            'userCity' => $orderData->userCity,
            'userState' => $orderData->userState,
            'userPincode' => $orderData->userPincode,
            'uniqueId' => $orderData->uniqueId,
            'blockId' => $orderData->blockId,
            'mobileNumber' => $orderData->mobileNumber,
            'modeOfPayment' => $mop
        ];
        $currentRates_content = json_decode((new RatesAugmontController)->currentRates());
        $currentRates = $currentRates_content->result->data;
        if($orderData->metalType=="silver") {
            $form_params['lockPrice'] = $currentRates->rates->sBuy;
            $form_params['blockId'] = $currentRates->blockId;
            $form_params['quantity'] = round($purchasedPrice/$currentRates->rates->sBuy, 3);
        } else {
            if($orderData->metalType=="gold") {
                $form_params['lockPrice'] = $currentRates->rates->gBuy;
                $form_params['blockId'] = $currentRates->blockId;
                $form_params['quantity'] = round($purchasedPrice/$currentRates->rates->gBuy, 3);
            }   
        }
        $augOrderRes = $this->postOrderToAugmont($form_params);
        $upstatus = AugmontOrders::where('id', $request->ao_orders)->update([
            'description' => json_encode($augOrderRes),
            'augmont_input' => json_encode($form_params)
        ]);
        
        if($augOrderRes->statusCode==422) {
            $errors =  $augOrderRes->errors;
            foreach ($errors as $key => $values) {
                if($key=="blockId") {
                    foreach ($values as $key1 => $value) {
                        if($value->code == 4690) {
                            $currentRates_content = json_decode((new RatesAugmontController)->currentRates());
                            $currentRates = $currentRates_content->result->data;
                            if($orderData->metalType=="silver") {
                                $form_params['lockPrice'] = $currentRates->rates->sBuy;
                            } else {
                                if($orderData->metalType=="gold") {
                                    $form_params['lockPrice'] = $currentRates->rates->gBuy;
                                }   
                            }
                            $form_params['blockId'] = $currentRates->blockId;
                        }
                    }
                    $augOrderRes = $this->postOrderToAugmont($form_params);
                } else {
                    if($key=="block_rate") {
                        foreach ($values as $key1 => $value) {
                            if($value->code == 4220 || $value->code == 4221) {
                                $currentRates_content = json_decode((new RatesAugmontController)->currentRates());
                                $currentRates = $currentRates_content->result->data;
                                if($orderData->metalType=="silver") {
                                    $form_params['lockPrice'] = $currentRates->rates->sBuy;
                                } else {
                                    if($orderData->metalType=="gold") {
                                        $form_params['lockPrice'] = $currentRates->rates->gBuy;
                                    }   
                                }
                                $form_params['blockId'] = $currentRates->blockId;
                            }
                        }
                        $augOrderRes = $this->postOrderToAugmont($form_params);
                    } else {
                        if($key=="merchantTransactionId") {
                            $merchantTransactionId = "AUGOM_".$request->ao_cust_id."_".$general->uniqueNumericId(5)."_".$general->uniqueNumericId(10);
                            $form_params['merchantTransactionId'] = $merchantTransactionId;
                            $upstatus = AugmontOrders::where('id', $request->ao_orders)->update([
                                'merchantTransactionId' => $merchantTransactionId
                            ]);
                            $augOrderRes = $this->postOrderToAugmont($form_params);
                        }
                    }
                }
            }
        }
        // $orderData['augstatusCode'] = $augOrderRes->statusCode;
        if(isset( $augOrderRes->errors)) {
            $orderData['errors'] = $augOrderRes->errors;
        }
        $statusCode = $augOrderRes->statusCode; 
        $augmontRes = $augOrderRes->result->data;
        $orderData->statusCode = "200";
        $orderData->blockId = $currentRates->blockId;
        $orderData->lockPrice = $form_params['lockPrice'];
        $orderData->preTaxAmount = $augmontRes->preTaxAmount;
        $orderData->transactionId = $augmontRes->transactionId;
        $orderData->goldBalance = $augmontRes->goldBalance;
        $orderData->silverBalance = $augmontRes->silverBalance;
        $orderData->totalTaxAmount = $augmontRes->taxes->totalTaxAmount;
        $orderData->taxSplit_cgst_taxPerc = $augmontRes->taxes->taxSplit[0]->taxPerc;
        $orderData->taxSplit_cgst_taxAmount = $augmontRes->taxes->taxSplit[0]->taxAmount;
        $orderData->taxSplit_sgst_taxPerc = $augmontRes->taxes->taxSplit[1]->taxPerc;
        $orderData->taxSplit_sgst_taxAmount = $augmontRes->taxes->taxSplit[1]->taxAmount;
        $orderData->invoiceNumber = $augmontRes->invoiceNumber;
        $saveOrderStatus = $orderData->save();
        // $res2 = (new EmailController)->send_purchase_success($augmontRes->transactionId);
        $dataInv = $invoice->object_to_array($invoice->getInvoiceData($augmontRes->transactionId)->result->data);
        // dd($dataInv);
        session()->forget('orderData');
        $myObj = array();
        $myObj['augOrder'] = $augOrderRes;
        $myObj['savestatus'] = $saveOrderStatus;
        return $myObj;
        // return View::make('augmont.razorpayView', $dataInv); 
    }
}
