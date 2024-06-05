<?php

namespace App\Http\Controllers\Augmont;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Augmont\AugmontController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\KycController;
use App\Http\Controllers\Payments\RazorpayController;
use App\Http\Controllers\Payments\RazorpaySIPController;
use App\Http\Controllers\Payments\RazorpayMandateController;
use App\Http\Controllers\Augmont\InvoiceAugmontController;
use App\Http\Controllers\Augmont\RatesAugmontController;
use App\Http\Controllers\Augmont\User\UserAugmontController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\Nsdl\SignzyController;
use App\Http\Controllers\Nsdl\NsdlController;
use App\Http\Controllers\Augmont\SIPAugmontController;
use App\Http\Controllers\Payments\RazorpayPlanController;
use App\Http\Controllers\Payments\RazorpaySubscriptionController;
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
Use App\Models\Razorpay_Subscription;
use Illuminate\Support\Str;
use Razorpay\Api\Api;

class BuyAugmontController extends Controller
{
    /**
        * @OA\Post(
        * path="/api/customer/augmont/silverBuy",
        * operationId="silverBuy",
        * tags={"Augmont/Buy"},
        * summary="Buy Silver",
        * description="Buy Silver",
        * security={{"bearerAuth":{}}}, 
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"silverGrams", "silverAmount", "silverPrice", "silverGST", "silverBlockId"},
        *               @OA\Property(property="silverGrams", type="text"),
        *               @OA\Property(property="silverAmount", type="text"),
        *               @OA\Property(property="silverPrice", type="text"),
        *               @OA\Property(property="silverGST", type="text"),
        *               @OA\Property(property="silverBlockId", type="text")
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Silver buy order created",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Silver buy order created",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */
    public function buySilverAugmont(Request $request) {
        $user = auth('userapi')->user();
        if($user) {
            $id = $user->pk_user_id;
            $userProfile = (new UsersController)->getUserDataByUID($id);
            $data = ["metal" => "silver", "silverGrams" => $request->silverGrams, "silverAmount" => $request->silverAmount, "silverPrice" => $request->silverPrice, "silverGST" => $request->silverGST, "silverBlockId" => $request->silverBlockId];
            $amt = $request->silverAmount;
            $redirectURL = '../augmont/buysilver';
            
            $data["kycRequired"] = $this->getPurchaseAmount($id, $amt);
            $data["redirectURL"] = $redirectURL;
            
            Session::put('orderData', $data);
            if($userProfile->augid != null) {
                if($data["kycRequired"] == true) {
                    $augKycStatus = (new KycController)->aug_kyc_db_check($id);
                    if($augKycStatus=="pending" || $augKycStatus=="rejected") {    
                        $prevPath = (new GeneralController)->previousPath();
                        $data["redirectURL"] = $prevPath;
                        $res = [
                            "statusCode" => 200,
                            "kycRequires" => "no",
                            "message" => $data,
                            "augKycStatus" => $augKycStatus
                        ];
                        return $res;
                    } else {
                        if($augKycStatus=="approved") {
                            $data["redirectURL"] = '../augmont/kyc';
                            $res = [
                                "statusCode" => 200,
                                "kycRequires" => "no",
                                "message" => $data
                            ];
                            return $res;
                        } else {
                            if($augKycStatus==null || $augKycStatus=="") {
                                $data["redirectURL"] = '../augmont/kyc';
                                $res = [
                                    "statusCode" => 200,
                                    "kycRequires" => "no",
                                    "message" => $data
                                ];
                                return $res;
                            }
                        }
                    }
                    $res = [
                        "statusCode" => 200,
                        "kycRequired" => "yes",
                        "message" => $data
                    ];
                    return $res;
                } else {
                    $res = [
                        "statusCode" => 200,
                        "kycRequires" => "no",
                        "message" => $data
                    ];
                    return $res;
                }
            } else {
                if($userProfile->augcity==null || $userProfile->augstate==null || $userProfile->augcity=="" || $userProfile->augstate=="" || $userProfile->dob==null || $userProfile->dob=="" || $userProfile->address1==null || $userProfile->address1=="" || $userProfile->address2==null || $userProfile->address2=="") {
                    $data["redirectURL"] = '../augmont/augmontReq';
                    $res = [
                        "statusCode" => 302,
                        "message" => $data
                    ];
                    return $res;
                } else {
                    $augid = (new UserAugmontController)->checkUpdateAugmont($userProfile);
                    if($augid!="" || $augid!=NULL) {
                        $res = [
                            "statusCode" => 302,
                            "message" => $data
                        ];
                        return $res;
                    }
                }
            }
        } else {
            $data = [
                "statusCode" => 401,
                "message" => "Unauthenticated_data."
            ];
            return $data;
        }
    }

    /**
        * @OA\Post(
        * path="/api/customer/augmont/goldBuy",
        * operationId="goldBuy",
        * tags={"Augmont/Buy"},
        * summary="Buy Gold",
        * description="Buy Gold",
        * security={{"bearerAuth":{}}}, 
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"goldGrams", "goldAmount", "goldPrice", "goldGST", "goldBlockId"},
        *               @OA\Property(property="goldGrams", type="text"),
        *               @OA\Property(property="goldAmount", type="text"),
        *               @OA\Property(property="goldPrice", type="text"),
        *               @OA\Property(property="goldGST", type="text"),
        *               @OA\Property(property="goldBlockId", type="text")
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Gold buy order created",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Gold buy order created",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */
    public function buyGoldAugmont(Request $request) {
        $user = auth('userapi')->user();
        if($user) {
            $id = $user->pk_user_id;
            $userProfile = (new UsersController)->getUserDataByUID($id);
            if(Str::contains(url()->current(), 'goldBuy')) {
                $data = ["metal" => "gold", "goldGrams" => $request->goldGrams, "goldAmount" => $request->goldAmount, "goldPrice" => $request->goldPrice, "goldGST" => $request->goldGST, "goldBlockId" => $request->goldBlockId];
                $amt = $request->goldAmount;
                $redirectURL = '../augmont/buygold';
            }
            $data["kycRequired"] = $this->getPurchaseAmount($id, $amt);
            $data["redirectURL"] = $redirectURL;
            
            Session::put('orderData', $data);
            if($userProfile->augid != null) {
                if($data["kycRequired"] == true) {
                    $augKycStatus = (new KycController)->aug_kyc_db_check($id);
                    if($augKycStatus=="pending" || $augKycStatus=="rejected") {    
                        $prevPath = (new GeneralController)->previousPath();
                        $data["redirectURL"] = $prevPath;
                        $res = [
                            "statusCode" => 200,
                            "kycRequires" => "no",
                            "message" => $data,
                            "augKycStatus" => $augKycStatus
                        ];
                        return $res;
                    } else {
                        if($augKycStatus=="approved") {
                            return View::make($redirectURL, $data);
                        } else {
                            if($augKycStatus==null || $augKycStatus=="") {
                                $data["redirectURL"] = '/augmont/kyc';
                                $res = [
                                    "statusCode" => 200,
                                    "kycRequires" => "no",
                                    "message" => $data,
                                    "augKycStatus" => $augKycStatus
                                ];
                                return $res;
                            }
                        }
                    }
                    $res = [
                        "statusCode" => 200,
                        "kycRequired" => "yes",
                        "message" => $data
                    ];
                    return $res;
                } else {
                    $res = [
                        "statusCode" => 200,
                        "kycRequires" => "no",
                        "message" => $data
                    ];
                    return $res;
                }
            } else {
                if($userProfile->augcity==null || $userProfile->augstate==null || $userProfile->augcity=="" || $userProfile->augstate=="" || $userProfile->dob==null || $userProfile->dob=="") {
                    $data["redirectURL"] = '../augmont/augmontReq';
                    $res = [
                        "statusCode" => 302,
                        "message" => $data
                    ];
                    return $res;
                } else {
                    $augid = (new UserAugmontController)->checkUpdateAugmont($userProfile);
                    if($augid!="" || $augid!=NULL) {
                        $res = [
                            "statusCode" => 200,
                            "message" => $data
                        ];
                        return $res;
                    }
                }
            }
        } else {
            $data = [
                "statusCode" => 401,
                "message" => "Unauthenticated_data."
            ];
            return $data;
        }
    }

    /**
        * @OA\Post(
        * path="/api/customer/augmont/silverSipBuy",
        * operationId="silverSipBuy",
        * tags={"Augmont/Buy"},
        * summary="Buy Silver SIP",
        * description="Buy Silver SIP",
        * security={{"bearerAuth":{}}}, 
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"silverSipDate", "silverSipCycleDate", "silverSipInvestmentPurpose", "metalType", "silverSipAmount", "silverSipPrice", "silverSipGST", "silverSipBlockId", "trans_type"},
        *               @OA\Property(property="silverSipDate", type="text"),
        *               @OA\Property(property="silverSipCycleDate", type="text"),
        *               @OA\Property(property="metalType", type="text", value="silver"),
        *               @OA\Property(property="silverAmount", type="text"),
        *               @OA\Property(property="silverPrice", type="text"),
        *               @OA\Property(property="silverGST", type="text"),
        *               @OA\Property(property="silverBlockId", type="text"),
        *               @OA\Property(property="trans_type", type="text" value="sip")
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Silver sip buy order created",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Silver sip buy order created",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
        *      @OA\Response(response=404, description="Resource Not Found"),
        * )
        */
    public function buySIPSilverAugmont(Request $request) {
        $user = auth('userapi')->user();
        if($user) {
            $id = $user->pk_user_id;
            $userProfile = (new UsersController)->getUserDataByUID($id);
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
            $redirectURL = '../augmont/buysipsilver';
            
            $data["redirectURL"] = $redirectURL;
            $data['kycRequired']=true;
            if($userProfile->augid!=null) {
                if($userProfile->pan_number==null) {
                    $data["redirectURL"] = '/augmont/kyc';
                    $res = [
                        "statusCode" => 200,
                        "kycRequires" => "no",
                        "message" => $data
                    ];
                    return $res;
                } else {
                    $nsdl_response = (new NsdlController)->getPasscodeEncyrt($userProfile->pan_number, $userProfile->contact_no);
                    if(isset($nsdl_response['data']['APP_NAME'])) {
                        if($userProfile->cust_name!=$nsdl_response['data']['APP_NAME']) {
                            $upstatus = Bfsi_users_detail::where('fr_user_id', $id)->update([
                                'cust_name' => $nsdl_response['data']['APP_NAME']
                            ]);
                            $userProfile->cust_name = $nsdl_response['data']['APP_NAME'];
                        }
                    }
                    $result = (new NsdlController)->nsdlResponseUpdate($nsdl_response, $request);
                    $augKycStatus = (new KycController)->aug_kyc_db_check($id);
                    if($augKycStatus=="pending" || $augKycStatus=="rejected") {    
                        $prevPath = (new GeneralController)->previousPath();
                        $data["redirectURL"] = $prevPath;
                        $res = [
                            "statusCode" => 200,
                            "kycRequires" => "no",
                            "message" => $data,
                            "augKycStatus" => $augKycStatus
                        ];
                        return $res;
                    } else {
                        if($augKycStatus=="approved") {
                            $prevPath = (new GeneralController)->previousPath();
                            $res = [
                                "statusCode" => 200,
                                "kycRequires" => "no",
                                "message" => $data,
                                "augKycStatus" => $augKycStatus
                            ];
                            return $res;
                        } else {
                            if($augKycStatus==null || $augKycStatus=="") {
                                $data["redirectURL"] = '/augmont/kyc';
                                $res = [
                                    "statusCode" => 200,
                                    "kycRequires" => "no",
                                    "message" => $data,
                                    "augKycStatus" => $augKycStatus
                                ];
                                return $res;
                            }
                        }
                    }
                }
            } else {
                if($userProfile->augcity==null || $userProfile->augstate==null || $userProfile->augcity=="" || $userProfile->augstate=="" || $userProfile->dob==null || $userProfile->dob=="") {
                    $data["redirectURL"] = '../augmont/augmontReq';
                    $res = [
                        "statusCode" => 302,
                        "message" => $data
                    ];
                    return $res;
                } else {
                    $augid = (new UserAugmontController)->checkUpdateAugmont($userData);
                    if($augid!="" || $augid!=NULL) {
                        $res = [
                            "statusCode" => 200,
                            "message" => $data
                        ];
                        return $res;
                    }
                }
            }
        } else {
            $data = [
                "statusCode" => 401,
                "message" => "Unauthenticated_data."
            ];
            return $data;
        }
    }

    public function buySIPGoldAugmont(Request $request) {
        $user = auth('userapi')->user();
        if($user) {
            $id = $user->pk_user_id;
            $userProfile = (new UsersController)->getUserDataByUID($id);
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
                $redirectURL = '../augmont/buysipgold';
            }
            $data["redirectURL"] = $redirectURL;
            $data['kycRequired']=true;
            if($userProfile->augid!=null) {
                if($userProfile->pan_number==null) {
                    $data["redirectURL"] = '/augmont/kyc';
                    $res = [
                        "statusCode" => 200,
                        "kycRequires" => "no",
                        "message" => $data
                    ];
                    return $res;
                } else {
                    $nsdl_response = (new NsdlController)->getPasscodeEncyrt($userProfile->pan_number, $userProfile->contact_no);
                    if(isset($nsdl_response['data']['APP_NAME'])) {
                        if($userProfile->cust_name!=$nsdl_response['data']['APP_NAME']) {
                            $upstatus = Bfsi_users_detail::where('fr_user_id', $id)->update([
                                'cust_name' => $nsdl_response['data']['APP_NAME']
                            ]);
                            $userProfile->cust_name = $nsdl_response['data']['APP_NAME'];
                        }
                    }
                    $result = (new NsdlController)->nsdlResponseUpdate($nsdl_response, $request);
                    $augKycStatus = (new KycController)->aug_kyc_db_check($id);
                    if($augKycStatus=="pending" || $augKycStatus=="rejected") {    
                        $prevPath = (new GeneralController)->previousPath();
                        $data["redirectURL"] = $prevPath;
                        $res = [
                            "statusCode" => 200,
                            "kycRequires" => "no",
                            "message" => $data,
                            "augKycStatus" => $augKycStatus
                        ];
                        return $res;
                    } else {
                        if($augKycStatus=="approved") {
                            $res = [
                                "statusCode" => 200,
                                "kycRequires" => "no",
                                "message" => $data,
                                "augKycStatus" => $augKycStatus
                            ];
                            return $res;
                        } else {
                            if($augKycStatus==null || $augKycStatus=="") {
                                $data["redirectURL"] = '/augmont/kyc';
                                $res = [
                                    "statusCode" => 200,
                                    "kycRequires" => "no",
                                    "message" => $data,
                                    "augKycStatus" => $augKycStatus
                                ];
                                return $res;
                            }
                        }
                    }
                }

                
            } else {
                if($userProfile->augcity==null || $userProfile->augstate==null || $userProfile->augcity=="" || $userProfile->augstate=="" || $userProfile->dob==null || $userProfile->dob=="") {
                    $data["redirectURL"] = '../augmont/augmontReq';
                    $res = [
                        "statusCode" => 302,
                        "message" => $data
                    ];
                    return $res;
                } else {
                    $augid = (new UserAugmontController)->checkUpdateAugmont($userData);
                    if($augid!="" || $augid!=NULL) {
                        $res = [
                            "statusCode" => 200,
                            "message" => $data
                        ];
                        return $res;
                    }
                }
            }
        } else {
            $data = [
                "statusCode" => 401,
                "message" => "Unauthenticated_data."
            ];
            return $data;
        }
    }

    public function createOrder(Request $request) {
        $user = auth('userapi')->user();
        if($user) {
            $id = $user->pk_user_id;
            $orderData = json_encode($request->all());
            if( isset( $orderData['razorpayOrderId'] ) ){
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
                $merchantTransactionId = "AUGOM_".$id."_".$general->uniqueNumericId(5)."_".$general->uniqueNumericId(10);
                
                $userData = Bfsi_user::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'bfsi_user.pk_user_id')
                    ->where('bfsi_user.pk_user_id', $id)
                    ->get(['bfsi_user.*', 'bfsi_users_details.*'])->first();
                if($userData->augid==null) {
                    $userData->augid = (new UserAugmontController)->checkUpdateAugmont($userData);
                }
                if(env('RAZORPAY_MODE') == "test") {
                    $razorpay_key = env('RAZORPAY_KEY_TEST');
                } else {
                    $razorpay_key = env('RAZORPAY_KEY');
                }
                $razorRes = $razorpay->createOrder($data, $merchantTransactionId, $userData, str_replace('.','', number_format($data['totalAmount'], 2, '.', '')));
                $up_stat = $this->insertOrder($id, 'Buy', $userData, $data, $razorRes, $merchantTransactionId);
                $post_data = new stdClass();
                $post_data->id = $razorRes->id;
                $post_data->entity = $razorRes->entity;
                $post_data->amount = $razorRes->amount;
                $post_data->amount_paid = $razorRes->amount_paid;
                $post_data->amount_due = $razorRes->amount_due;

                $post_data->key = $razorpay_key; // Enter the Key ID generated from the Dashboard
                $post_data->amount = $razorRes->amount; // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
                $post_data->currency = "INR";
                $post_data->name = "Optymoney";
                $post_data->description = "Transaction : ".$merchantTransactionId;
                $post_data->image = "https://optymoney.com/static/opty_theme/img/optymoney_icon.png";
                $post_data->order_id = $razorRes->id; //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
                $post_data->callback_url = url('/augmont/orderResponse');
                $post_data->prefill = array(
                    'name'=>$userData->cust_name,
                    'email' => $userData->login_id, 
                    'contact'=>$userData->contact_no
                );
                $post_data->customer = array(
                    'name'=>$userData->cust_name,
                    'email' => $userData->login_id, 
                    'contact'=>$userData->contact_no
                );
                $post_data->notes = array(
                    "address"=> "Razorpay Corporate Office",
                    "descr" =>$merchantTransactionId
                );
                $post_data->theme = array(
                    "color"=> "#D33633"
                );
                \Log::channel('itsolution')->info(json_encode(['id' => $id, 'input' => $post_data, 'function' => "createOrder"]));
                return $post_data;
            }        
        } else {
            $data = [
                "statusCode" => 401,
                "message" => "Unauthenticated_data."
            ];
            return $data;
        }
        
    }

    public function createSipOrder(Request $request) {
        $user = auth('userapi')->user();
        if($user) {
            $id = $user->pk_user_id;
            $general = new GeneralController();
            $razorpaysip = new RazorpaySIPController();
            $data = $request->all();
            if(env('RAZORPAY_MODE') == "test") {
                $razorpay_key = env('RAZORPAY_SIP_KEY_TEST');
            } else {
                $razorpay_key = env('RAZORPAY_KEY');
            }
            try {
                $orderData = json_encode($request->all());
                if( isset( $orderData['razorpayOrderId'] ) ){
                    if($orderData['razorpayOrderId']!="") {
                        if(Arr::exists($orderData, 'errors')) {
                            if($orderData['errors'] == "") {
                                $augmontOrder = AugmontOrders::where([
                                    'razorpaySubscriptionId' => $orderData['razorpayOrderId'],
                                ])->first();
                                $augOrderRes = $this->createAugmontOrder($augmontOrder);
                            } else {
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
                        } else {
                            return '/dashboard/index';
                        }
                    } else {
                        return '/dashboard/index';
                    }
                } else {
                    $merchantTransactionId = "AUGOM_".$id."_".$general->uniqueNumericId(5)."_".$general->uniqueNumericId(10);
                    $userData = Bfsi_user::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'bfsi_user.pk_user_id')
                        ->where('bfsi_user.pk_user_id', $id)
                        ->get(['bfsi_user.*', 'bfsi_users_details.*'])->first();
                    if($userData->contact==null) {
                        $contact = $userData->contact_no;
                    } else {
                        $contact = $userData->contact;
                    }
                    if($userData->rzpCustId==null) {
                        $rzpcustomer = (new RazorpayController)->createCustomer($userData);
                    } else {
                        $rzpcustomer = $userData->rzpCustId;
                    }
                    $razorAmtFormat = str_replace('.','', number_format($data['amount'], 2, '.', ''));
                    
                    // Razorpay Mandate Subscription Process
                    // $rzp_mandate = new RazorpayMandateController();
                    // $rzp_mandate_reg_stat = $rzp_mandate->createSubscription($request);
                    // $up_stat = $this->insertOrder($id, 'mandate', $userData, $data, $rzp_mandate_reg_stat, $merchantTransactionId);
                    // $postObj = $this->mandateAuthPaymentObj($razorpay_key, $rzp_mandate_reg_stat->order_id, $rzpcustomer, $merchantTransactionId, $data);
                    
                    // Razorpay Plan Subscription Process
                    $planObj = $this->planObj($id, $merchantTransactionId, $data, $razorAmtFormat);
                    $mytime = Carbon::now();
                    $plan = (new RazorpayPlanController)->createPlan($planObj);
                    $subscriptionObj = $this->subscriptionObj($id, $merchantTransactionId, $data, $razorAmtFormat, $plan->razor_plan_id, $userData->login_id, $contact);
                    $subscription = (new RazorpaySubscriptionController)->createSubscription($subscriptionObj);
                    $up_stat = $this->insertOrder($id, 'SIP', $userData, $data, $subscription, $merchantTransactionId);
                    $postObj = $this->paymentObj($razorpay_key, $subscription->razor_subscription_id, $userData, $merchantTransactionId, $data);

                    return $postObj;
                    // \Log::channel('itsolution')->info(json_encode(['id' => $id, 'input' => $post_data, 'function' => "createSipOrder"]));
                }
            } catch (\Exception $e) {
                \Log::channel('itsolution')->error(json_encode(['id' => $id, 'input' => $request, 'function' => "createSipOrder", 'exception' => $e]));
                return $e;
            }
        } else {
            $data = [
                "statusCode" => 401,
                "message" => "Unauthenticated_data."
            ];
            return $data;
        }
    }

    public function saveOrder(Request $request) {
        $user = auth('userapi')->user();
        if($user) {
            try {
                $general = new GeneralController();
                $razorpay = new RazorpayController();
    
                $data = $request->all();
                $id = $user->pk_user_id;
                $merchantTransactionId = "AUGOM_".$id."_".$general->uniqueNumericId(5)."_".$general->uniqueNumericId(10);
                
                $userData = Bfsi_user::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'bfsi_user.pk_user_id')
                    ->where('bfsi_user.pk_user_id', $id)
                    ->get(['bfsi_user.*', 'bfsi_users_details.*'])->first();
                
                // $orderData = session()->get('orderData');
                // $orderData['totalAmount'] = $data['totalAmount'];
                // session()->put('orderData',$orderData);
                $razorRes = $razorpay->payment($data, $merchantTransactionId, $userData, str_replace('.','', number_format($data['totalAmount'], 2, '.', '')), 'test');
                \Log::channel('itsolution')->info(json_encode(['id' => $request->session()->get('id'), 'input' => $orderData, 'function' => "saveOrder", 'output' => $razorRes]));
                
                $up_stat = $this->insertOrder($id, 'Buy', $userData, $data, $razorRes, $merchantTransactionId);
                return response()->json(['success'=>$razorRes->short_url]);
            } catch (\Exception $e) {
                \Log::channel('itsolution')->error(json_encode(['id' => $user->pk_user_id, 'input' => $request, 'function' => "saveOrder", 'exception' => $e]));
                return $e;
            }
        } else {
            $data = [
                "statusCode" => 401,
                "message" => "Unauthenticated_data."
            ];
            return $data;
        }
    }

    public function saveSipOrder(Request $request) {
        $general = new GeneralController();
        $razorpaysip = new RazorpaySIPController();

        $data = $request->all();
        $id = $request->session()->get('id');
        $merchantTransactionId = "AUGOM_SIP_".$id."_".$general->uniqueNumericId(5)."_".$general->uniqueNumericId(10);
        
        $userData = Bfsi_user::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'bfsi_user.pk_user_id')
              ->where('bfsi_user.pk_user_id', $request->session()->get('id'))
              ->get(['bfsi_user.*', 'bfsi_users_details.*'])->first();
        
        $orderData = session()->get('orderData');
        $orderData['totalAmount'] = $data['totalAmount'];
        session()->put('orderData',$orderData);
        $razorRes = $razorpaysip->sip_payment($data, $merchantTransactionId, $userData->pk_user_id);

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
        $augmontOrders->emailId = $userData->login_id;
        $augmontOrders->metalType = $augOrderData['metalType'];
        $augmontOrders->merchantTransactionId = $merchantTransactionId;
        $augmontOrders->userName = $userData->cust_name;
        $augmontOrders->userAddress = $userData->address1.", ".$userData->address2;
        $augmontOrders->userCity = $userData->augcity;
        $augmontOrders->userState = $userData->augstate;
        $augmontOrders->userPincode = $userData->pincode;
        $augmontOrders->uniqueId = $userData->augid;
        $augmontOrders->mobileNumber = $userData->contact_no;
        $augmontOrders->ordertype = $ordertype;
        $augmontOrders->lockPrice = $augOrderData['lockPrice'];
        $augmontOrders->blockId = $augOrderData['blockId'];
        if($ordertype=="SIP") {
            $augmontOrders->razorpaySubscriptionId = $razorRes->id;
            $augmontOrders->description = $augOrderData['sipInvestmentPurpose'];
            $augmontOrders->totalAmount = $augOrderData['amount'];
            $augmontOrders->quantity = floatval($augOrderData['amount'])/floatval($augOrderData['lockPrice']);
        } else {
            if($ordertype=="mandate") {
                $augmontOrders->razorpayOrderId = $razorRes->order_id;
                $augmontOrders->description = $augOrderData['sipInvestmentPurpose'];
                $augmontOrders->totalAmount = $augOrderData['amount'];
            } else {
                $augmontOrders->quantity = $augOrderData['quantity'];
                $augmontOrders->razorpayOrderId = $razorRes->id;
                $augmontOrders->totalAmount = $augOrderData['totalAmount'];
            }
        }
        $saveOrderStatus = $augmontOrders->save();
        if($saveOrderStatus) {
            return $augmontOrders;
        } else {
            return false;
        }
        
    }

    public function orderResponse(Request $request) {
        $invoice = new InvoiceAugmontController();
        try {
            $razorpay = new RazorpayController();
            $invoice = new InvoiceAugmontController();
            $general = new GeneralController();
            $rp = $razorpay->verifySignature($request);
            $augmontOrder = AugmontOrders::where([
                'razorpayOrderId' => $request->razorpay_order_id,
            ])->first();
            $orderData = $augmontOrder;
            $augmontOrder->razorpayId = $request->razorpay_payment_id;
            $augmontOrder->razorpayStatus = $rp->statusCode;
            $saveOrderStatus = $augmontOrder->save();
            if($rp->statusCode == 200) {
                $orderData['razorpayOrderId'] = $request->razorpay_order_id;
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
                    'modeOfPayment' => ""//$razorPayResById->method
                ];
                $upstatus = AugmontOrders::where('razorpayOrderId', $request->razorpay_order_id)->update([
                    'augmont_input' => $form_params
                ]);
                $augOrderRes = $this->postOrderToAugmont($form_params);
                \Log::channel('itsolution')->info(json_encode(['id' => $augmontOrder->user_id, 'input' => $form_params, 'function' => "orderResponse", 'output' => $augOrderRes]));
                $upstatus = AugmontOrders::where('razorpayOrderId', $request->razorpay_order_id)->update([
                    'description' => json_encode($augOrderRes)
                ]);
                if($augOrderRes->statusCode==422) {
                    $errors =  $augOrderRes->errors;
                    foreach ($errors as $key => $values) {
                        if($key=="blockId") {
                            foreach ($values as $key1 => $value) {
                                if($value->code == 4220 || $value->code == 4221 || $value->code == 4690) {
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
                            \Log::channel('itsolution')->info(json_encode(['id' => $augmontOrder->user_id, 'input' => $form_params, 'function' => "orderResponse", 'output' => $augOrderRes, 'message' => "tried again after the Block Id"]));
                        } else {
                            if($key=="block_rate") {
                                foreach ($values as $key1 => $value) {
                                    if($value->code == 4220 || $value->code == 4221 || $value->code == 4690) {
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
                                $form_params['blockId'] = $currentRates->blockId;
                                $merchantTransactionId = "AUGOM_".$request->ao_cust_id."_".$general->uniqueNumericId(5)."_".$general->uniqueNumericId(10);
                                $form_params['merchantTransactionId'] = $merchantTransactionId;
                                $upstatus = AugmontOrders::where('id', $request->ao_orders)->update([
                                    'merchantTransactionId' => $merchantTransactionId
                                ]);
                                $augOrderRes = $this->postOrderToAugmont($form_params);
                                \Log::channel('itsolution')->info(json_encode(['id' => $augmontOrder->user_id, 'input' => $form_params, 'function' => "orderResponse", 'output' => $augOrderRes, 'message' => "tried again after the Block rate"]));
                            } else {
                                if($key=="merchantTransactionId") {
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
                                    $merchantTransactionId = "AUGOM_".$request->ao_cust_id."_".$general->uniqueNumericId(5)."_".$general->uniqueNumericId(10);
                                    $form_params['merchantTransactionId'] = $merchantTransactionId;
                                    $upstatus = AugmontOrders::where('id', $request->ao_orders)->update([
                                        'merchantTransactionId' => $merchantTransactionId
                                    ]);
                                    $augOrderRes = $this->postOrderToAugmont($form_params);
                                    \Log::channel('itsolution')->info(json_encode(['id' => $augmontOrder->user_id, 'input' => $form_params, 'function' => "orderResponse", 'output' => $augOrderRes, 'message' => "tried again after the merchant Id error"]));
                                }
                            }
                        }
                    }
                }
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
                    \Log::channel('itsolution')->info(json_encode(['id' => $augmontOrder->user_id, 'input' => $augmontOrder, 'function' => "orderResponse", 'output' => $augOrderRes]));
                    $saveOrderStatus = $augmontOrder->save();
                    $res2 = (new EmailController)->send_purchase_success($augmontRes->transactionId);
                    $dataInv = $invoice->object_to_array($invoice->getInvoiceData($augmontRes->transactionId)->result->data);
                    return Redirect::to(env('GOLD_URL').'augmont/razorpayView/'.$augmontRes->transactionId, 302);
                } else {
                    if($statusCode==422) {
                        $orderData['message'] = "<p>The payment was successful! but the live price of gold/silver has changed slightly since the last update.</p><p>Please confirm the below details and submit (You will not be charged again).</p>";
                        \Log::channel('itsolution')->info(json_encode(['id' => $augmontOrder->user_id, 'input' => $augmontOrder, 'function' => "orderResponse", 'output' => $augOrderRes, 'message' => "Payment successful but order not placed"]));
                        session()->put('orderData',$orderData);
                        $augmontOrder->statusCode = 422;
                        $saveOrderStatus = $augmontOrder->save();
                        $prevPath = (new GeneralController)->previousPath();
                        if(Str::contains($prevPath, 'goldBuy')) {
                            return View::make('augmont.buygold', $orderData);
                        } else {
                            return View::make('augmont.buysilver', $orderData);
                        }
                    }
                }
            } else {
                if($request->session()->has('orderData')) {
                    $orderData = session()->get('orderData');
                    $orderData['razorpayOrderId'] = $request->razorpay_order_id;
                    $orderData['message'] = "<p>Payment Verification failed.</p><p>Please contact the customer support.</p>";
                    \Log::channel('itsolution')->info(json_encode(['id' => $augmontOrder->user_id, 'input' => $orderData, 'function' => "orderResponse", 'message' => "Payment verification failed"]));
                    session()->put('orderData',$orderData);
                    $prevPath = (new GeneralController)->previousPath();
                    if(Str::contains($prevPath, 'goldBuy')) {
                        return View::make('augmont.buygold', $orderData);
                    } else {
                        return View::make('augmont.buysilver', $orderData);
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::channel('itsolution')->error(json_encode(['input' => json_encode($request), 'function' => "orderResponse", 'error' => $e]));
            return $e;
        }
    }

    public function sipOrderResponse(Request $request) {
        $invoice = new InvoiceAugmontController();
        try {
            $razorpay = new RazorpayController();
            $invoice = new InvoiceAugmontController();
            $razorVerifyRes = $razorpay->verifySipSignature($request);
            // if ($razorVerifyRes) {
                $subscription = Razorpay_Subscription::where([
                    'razor_subscription_id' => $request->razorpay_subscription_id,
                ])->first();
                $augmontOrder = AugmontOrders::where([
                    'razorpaySubscriptionId' => $subscription->id,
                ])->first();
                $augmontOrder->razorpayId = $request->razorpay_payment_id;
                $augmontOrder->razorpayStatus = json_encode($razorVerifyRes);
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
                $upstatus = AugmontOrders::where('razorpaySubscriptionId', $request->razorpay_subscription_id)->update([
                    'augmont_input' => $form_params
                ]);
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
                $upstatus = AugmontOrders::where('razorpaySubscriptionId', $request->razorpay_subscription_id)->update([
                    'description' => json_encode($augOrderRes)
                ]);
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
                    return Redirect::to(env('GOLD_URL').'augmont/razorpayView/'.$augmontRes->transactionId, 302);
                } else {
                    if($statusCode=422) {
                        $orderData['message'] = "<p>The payment was successful! but the live price of gold/silver has changed slightly since the last update.</p><p>Please confirm the below details and submit (You will not be charged again).</p>";
                        $prevPath = (new GeneralController)->previousPath();
                        if($augmontOrder->metalType == 'gold') {
                            return Redirect::to(env('GOLD_URL').'augmont/buysipgold/'.$augmontRes->transactionId, 302);
                        } else {
                            return Redirect::to(env('GOLD_URL').'augmont/buysipsilver/'.$augmontRes->transactionId, 302);
                        }
                    }
                }
            // } else {
            //     return false;
            // }
        } catch (\Exception $e) {
            \Log::channel('itsolution')->error(json_encode(['input' => json_encode($request), 'function' => "sipOrderResponse", 'error' => $e]));
            return $e;
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
        try {
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
                'modeOfPayment' => ""
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
            // return $form_params;
            $augOrderRes = $this->postOrderToAugmont($form_params);
            
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
            \Log::channel('itsolution')->error(json_encode(['id' => $request, 'function' => "manualPostOrder", 'orderres' => $augOrderRes]));
            $upstatus = AugmontOrders::where('id', $request->ao_orders)->update([
                'description' => json_encode($augOrderRes),
                'augmont_input' => json_encode($form_params)
            ]);
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
        } catch (\Exception $e) {
            \Log::channel('itsolution')->error(json_encode(['id' => $request, 'function' => "manualPostOrder", 'exception' => $e]));
            return $e;
        }
    }

    public function getBuyInfo($merchantTransactionId, $uniqueId) {
        try {
            $tokentype = "Bearer ";
            $authToken = $tokentype.(new AugmontController)->merchantAuth();
            if($authToken==401) {
                return 401;
            } else {
                $res = (new AugmontController)->clientRequests('GET', 'merchant/v1/buy/'.$merchantTransactionId.'/'.$uniqueId, "");
                if($res->statusCode == 200) {
                    $data = $res->result->data;
                    if($augmontOrder->invoiceNumber == null) { 
                        $augmontOrder = AugmontOrders::where('merchantTransactionId', $merchantTransactionId)->get()->first();
                        $augmontOrder->statusCode = "200";
                        $augmontOrder->preTaxAmount = $data->preTaxAmount;
                        $augmontOrder->transactionId = $data->transactionId;
                        $augmontOrder->goldBalance = $data->goldBalanceInGM;
                        $augmontOrder->silverBalance = $data->silverBalanceInGM;
                        $augmontOrder->totalTaxAmount = $data->taxes->totalTaxAmount;
                        $augmontOrder->taxSplit_cgst_taxPerc = $data->taxes->taxSplit[0]->taxPerc;
                        $augmontOrder->taxSplit_cgst_taxAmount = $data->taxes->taxSplit[0]->taxAmount;
                        $augmontOrder->taxSplit_sgst_taxPerc = $data->taxes->taxSplit[1]->taxPerc;
                        $augmontOrder->taxSplit_sgst_taxAmount = $data->taxes->taxSplit[1]->taxAmount;
                        $augmontOrder->invoiceNumber = $data->invoiceNumber;
                        $saveOrderStatus = $augmontOrder->save();
                        return json_encode($saveOrderStatus);
                    } else {
                        return true;
                    }
                } else {
                    return $res;
                }
            }
        } catch (\Exception $e) {
            \Log::channel('itsolution')->error(json_encode(['id' => $uniqueId, 'function' => "getBuyInfo", 'exception' => $e]));
            return $e;
        }
    }

    public function planObj($id, $merchantTransactionId, $data, $razorAmtFormat) {
        $razor_sip_plan_data = array(
            'period'=> 'monthly', 
            'interval'=> 1, 
            'item' => array(
                'name' => $id.'_'.$data['sipInvestmentPurpose'], 
                'description' => $data['sipInvestmentPurpose'].' with the duration of '.$data['amount'], 
                'amount' => $razorAmtFormat, 
                'currency' => 'INR'
            ),
            'notes'=>array(
                'merchantTransactionId'=> $merchantTransactionId,
                'userid' => $id
            ),
        );
        return $razor_sip_plan_data;
    }

    public function subscriptionObj($id, $merchantTransactionId, $data, $razorAmtFormat, $razor_plan_id, $email, $contact) {
        $razor_sip_subscription_data = array(
            'plan_id' => $razor_plan_id, 
            'customer_notify' => 0,
            'total_count' => 600, 
            'start_at' => strtotime($data['sipDate']),
            "expire_by" => '',
            'addons' => array(
                array(
                    'item' => array(
                        'name' => 'Purchase of Gold/Silver', 
                        'amount' => $razorAmtFormat, 
                        'currency' => 'INR'
                    )
                )
            ),
            'notes'=> array(
                'merchantTransactionId'=> $merchantTransactionId,
                'userid' => $id,
                'name' => $data['sipInvestmentPurpose'], 
                'description' => $data['sipInvestmentPurpose'].' with the amount of Rs.'.$data['amount'], 
                'amount' => $data['amount'], 
                'currency' => 'INR',
                'metalType' => $data['metalType']
            ),
            'notify_info'=>array(
                'notify_phone' => $contact,
                'notify_email'=> $email
            )
        );
        return $razor_sip_subscription_data;
    }

    public function paymentObj($razorpay_key, $subscription_id, $userData, $merchantTransactionId, $data) {
        $post_data = new stdClass();
        $post_data->key = $razorpay_key;
        $post_data->subscription_id = $subscription_id;
        $post_data->name = $data['sipInvestmentPurpose'];
        $post_data->description = $data['sipInvestmentPurpose'].". Auth txn for ".$subscription_id;
        $post_data->image = "https://optymoney.com/assets/img/brand/logo.png";
        $post_data->prefill = array(
            'name'=>$userData->cust_name,
            'email' => $userData->login_id, 
            'contact'=>$userData->contact_no
        );
        $post_data->callback_url = url('/augmont/sipOrderResponse');
        $post_data->notes = array(
            "address"=> "Razorpay Corporate Office",
            "descr" =>$merchantTransactionId
        );
        $post_data->theme = array(
            "color"=> "#D33633"
        );
        return $post_data;
    }

    public function mandateAuthPaymentObj($razorpay_key, $order_id, $customer_id, $merchantTransactionId, $data) {
        $post_data = new stdClass();
        $post_data->key = $razorpay_key;
        $post_data->order_id = $order_id;
        $post_data->customer_id = $customer_id;
        $post_data->recurring = "1";
        $post_data->callback_url = url('/augmont/mandateOrderResponse');
        $post_data->notes = array(
            "address"=> "Razorpay Corporate Office",
            "descr" => $merchantTransactionId,
            "name" => $data['sipInvestmentPurpose'],
            "description" => $data['sipInvestmentPurpose'],
            "image" => "https://optymoney.com/assets/img/brand/logo.png"
        );
        $post_data->theme = array(
            "color"=> "#D33633"
        );
        return $post_data;
    }

    public function mandateOrderResponse(Request $request) {
        $invoice = new InvoiceAugmontController();
        try {
            $razorpay = new RazorpayController();
            $invoice = new InvoiceAugmontController();
            if(env('RAZORPAY_MODE') == "test") {
                $api = new Api(env('RAZORPAY_SIP_KEY_TEST'), env('RAZORPAY_SIP_SECRET_TEST'));
            } else {
                $api = new Api(env('RAZORPAY_SIP_KEY'), env('RAZORPAY_SIP_SECRET'));
            }
            $payment_fetch = $api->payment->fetch($request->razorpay_payment_id);
            $mandate = [];
            foreach ($payment_fetch as $key=>$value) { 
                $mandate[$key] = $value; 
            }
            if($mandate['status'] == "captured") {
                $augmontOrderParams = $this->createAugmontOrderParams($request, $mandate);
                return Redirect::to(env('GOLD_URL').'augmont/razorpayView/'.$augmontOrderParams->transactionId, 302);
            } else {
                return "not captured";
            }
        } catch (\Exception $e) {
            \Log::channel('itsolution')->error(json_encode(['input' => json_encode($request), 'function' => "sipOrderResponse", 'error' => $e]));
            return $e;
        }
    }

    public function createAugmontOrderParams($request, $mandate) {
        $augmontOrder = AugmontOrders::where([
            'razorpayOrderId' => $request->razorpay_order_id,
        ])->first();
        $orderData = $augmontOrder;
        // $qty = round($augmontOrder->totalAmount/$augmontOrder->lockPrice, 3);
        $augmontOrder->razorpayId = $request->razorpay_payment_id;
        $augmontOrder->razorpayStatus = $mandate['status'];
        // $augmontOrder->quantity = $qty;
        $saveOrderStatus = $augmontOrder->save();
        
        $orderData['razorpayOrderId'] = $request->razorpay_order_id;
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
            'mobileNumber' => $augmontOrder->mobileNumber,
            'modeOfPayment' => ""//$razorPayResById->method
        ];
        $upstatus = AugmontOrders::where('razorpayOrderId', $request->razorpay_order_id)->update([
            'augmont_input' => $form_params
        ]);
        $augOrderRes = $this->postOrderToAugmont($form_params);
        \Log::channel('itsolution')->info(json_encode(['id' => $augmontOrder->user_id, 'input' => $form_params, 'function' => "orderResponse", 'output' => $augOrderRes]));
        $upstatus = AugmontOrders::where('razorpayOrderId', $request->razorpay_order_id)->update([
            'description' => json_encode($augOrderRes)
        ]);
        if($augOrderRes->statusCode==422) {
            $general = new GeneralController();
            $errors =  $augOrderRes->errors;
            foreach ($errors as $key => $values) {
                if($key=="blockId") {
                    foreach ($values as $key1 => $value) {
                        if($value->code == 4220 || $value->code == 4221 || $value->code == 4690) {
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
                    \Log::channel('itsolution')->info(json_encode(['id' => $augmontOrder->user_id, 'input' => $form_params, 'function' => "orderResponse", 'output' => $augOrderRes, 'message' => "tried again after the Block Id"]));
                } else {
                    if($key=="block_rate") {
                        foreach ($values as $key1 => $value) {
                            if($value->code == 4220 || $value->code == 4221 || $value->code == 4690) {
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
                        $form_params['blockId'] = $currentRates->blockId;
                        $merchantTransactionId = "AUGOM_".$request->ao_cust_id."_".$general->uniqueNumericId(5)."_".$general->uniqueNumericId(10);
                        $form_params['merchantTransactionId'] = $merchantTransactionId;
                        $upstatus = AugmontOrders::where('id', $request->ao_orders)->update([
                            'merchantTransactionId' => $merchantTransactionId
                        ]);
                        $augOrderRes = $this->postOrderToAugmont($form_params);
                        \Log::channel('itsolution')->info(json_encode(['id' => $augmontOrder->user_id, 'input' => $form_params, 'function' => "orderResponse", 'output' => $augOrderRes, 'message' => "tried again after the Block rate"]));
                    } else {
                        if($key=="merchantTransactionId") {
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
                            $merchantTransactionId = "AUGOM_".$request->ao_cust_id."_".$general->uniqueNumericId(5)."_".$general->uniqueNumericId(10);
                            $form_params['merchantTransactionId'] = $merchantTransactionId;
                            $upstatus = AugmontOrders::where('id', $request->ao_orders)->update([
                                'merchantTransactionId' => $merchantTransactionId
                            ]);
                            $augOrderRes = $this->postOrderToAugmont($form_params);
                            \Log::channel('itsolution')->info(json_encode(['id' => $augmontOrder->user_id, 'input' => $form_params, 'function' => "orderResponse", 'output' => $augOrderRes, 'message' => "tried again after the merchant Id error"]));
                        }
                    }
                }
            }
        }
        $orderData['augstatusCode'] = $augOrderRes->statusCode;
        if(isset( $augOrderRes->errors)) {
            $orderData['errors'] = $augOrderRes->errors;
        }
        $statusCode = $augOrderRes->statusCode; 
        if($statusCode==200) {
            $augmontRes = $augOrderRes->result->data;
            $augmontOrder->statusCode = "200";
            $augmontOrder->quantity = $augmontRes->quantity;
            $augmontOrder->description = json_encode($augOrderRes);
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
            \Log::channel('itsolution')->info(json_encode(['id' => $augmontOrder->user_id, 'input' => json_encode($augmontOrder), 'function' => "orderResponse", 'output' => json_encode($augOrderRes)]));
            $saveOrderStatus = $augmontOrder->save();
            // $res2 = (new EmailController)->send_purchase_success($augmontRes->transactionId);
            $invoice = new InvoiceAugmontController();
            $dataInv = $invoice->object_to_array($invoice->getInvoiceData($augmontRes->transactionId)->result->data);
            return $augmontOrder;
        } else {
            if($statusCode==422) {
                $orderData['message'] = "<p>The payment was successful! but the live price of gold/silver has changed slightly since the last update.</p><p>Please confirm the below details and submit (You will not be charged again).</p>";
                \Log::channel('itsolution')->info(json_encode(['id' => $augmontOrder->user_id, 'input' => $augmontOrder, 'function' => "orderResponse", 'output' => $augOrderRes, 'message' => "Payment successful but order not placed"]));
                $augmontOrder->statusCode = 422;
                $saveOrderStatus = $augmontOrder->save();
                return $augmontOrder;
            }
        }
    }
}
