<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\OrdersAugmontController;
use Illuminate\Http\Request;
Use App\Models\AugmontOrders;
Use App\Models\Bfsi_user;
Use App\Models\Razorpay_Response;
use Razorpay\Api\Api;
use Razorpay\Api\Payment;
use Session;
use Exception;
Use hash_hmac;
use \stdClass;
use Illuminate\Support\Facades\DB;

class RazorpayController extends Controller
{
    public function razorpay() {        
        return view('razorpay');
    }
    
    public function createAPI() {
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
        return $api;
    }

    public function createSIPAPI() {
        $api = new Api(env('RAZORPAY_SIP_KEY'), env('RAZORPAY_SIP_SECRET'));
        return $api;
    }

    public function payment($augOrderData, $merchantTransactionId, $userData, $amount, $razorLogin) {
        try {
            if($razorLogin == "test") {
                $api = new Api(env('RAZORPAY_KEY_TEST'), env('RAZORPAY_SECRET_TEST'));
            } else {
                $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
            }
            
            $razorPostData = array(
                'amount'=>(int)$amount, 
                'currency'=>'INR', 
                'accept_partial'=>false,
                'first_min_partial_amount'=>0, 
                'description' => 'Purchase of '.$augOrderData['metalType'], 
                "reference_id"=> $merchantTransactionId, 
                'customer' => array(
                    'name'=>$userData->cust_name,
                    'email' => $userData->login_id, 
                    'contact'=>$userData->contact
                ),
                'notify'=>array('sms'=>true, 'email'=>true) ,
                'reminder_enable'=>true ,
                'notes'=>array(
                    'Metal Type'=> $augOrderData['metalType'],
                    'razorLog' => $razorLogin
                ),
                // 'callback_url' => url('/augmont/orderResponse'),
                // 'callback_method'=>'get', 
                "options"=> array(
                    "checkout"=> array(
                        "theme"=>array(
                            "hide_topbar"=> true
                        ), 
                        "method"=>array(
                            "netbanking"=> "1",
                            "card"=> "1",
                            "upi"=> "1",
                            "wallet"=>"1",
                            "lazypay"=>"0"
                        ), 
                        "name"=> "Optymoney",
                        "readonly"=>array(
                            "email"=> "1",
                            "contact"=> "1"
                        )
                    )
                )
            );
            $order = $api->paymentLink->create($razorPostData);
            \Log::channel('itsolution')->info($userData->pk_user_id." -> save order io ".json_encode($razorPostData).", o/p : ". json_encode($order) );
            dd($order);
        } catch (\Exception $e) {
            \Log::channel('itsolution')->info($userData->pk_user_id." : ".$e);
            return $e;
        }
    }

    public function createOrder($augOrderData, $merchantTransactionId, $userData, $amount) {
        try {
            if(env('RAZORPAY_MODE') == "test") {
                $api = new Api(env('RAZORPAY_KEY_TEST'), env('RAZORPAY_SECRET_TEST'));
            } else {
                $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
            }
            
            $razorPostData = array(
                'receipt' => $merchantTransactionId, 
                'amount' => (int)$amount, 
                'currency' => 'INR', 
                'notes'=> array(
                    'Metal Type'=> $augOrderData['metalType']
                )
            );

            $order = $api->order->create($razorPostData); 
            return $order;
        } catch (\Exception $e) {
            \Log::channel('itsolution')->info($request->session()->get('id')." : ".$e->getMessage());
            return $e->getMessage();
        }
    }

    public function verifySignature($request) {
        if(env('RAZORPAY_MODE') == "test") {
            $api = new Api(env('RAZORPAY_KEY_TEST'), env('RAZORPAY_SECRET_TEST'));
        } else {
            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
        }
        try{
            $attributes = array(
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature
            );
            $res = $api->utility->verifyPaymentSignature($attributes);
            \Log::channel('itsolution')->info($request->session()->get('id')." -> verifySignature io ".json_encode($attributes).", o/p : ". $res );
            $response = new stdClass;
            $response->msg = "success";
            $response->statusCode = 200;
            return $response;
        }
        catch(SignatureVerificationError $e){
            \Log::channel('itsolution')->info($request->session()->get('id')." : verifySignature : ".$e->getMessage());
            $response = new stdClass;
            $response->msg = "failure";
            $response->error = $e->getMessage();
            $response->statusCode = 400;
            return $response;
        }        
    }

    public function verifySipSignature($request) {
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
        try{
            $attributes = array(
                'razorpay_order_id' => $request->razorpay_subscription_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature
            );
            // dd($attributes);
            $res = $api->utility->verifyPaymentSignature($attributes);
            \Log::channel('itsolution')->info($request->session()->get('id')." -> verifySignature io ".json_encode($attributes).", o/p : ". $res );
            $response = new stdClass;
            $response->msg = "success";
            $response->statusCode = 200;
            return $response;
        }
        catch(SignatureVerificationError $e){
            \Log::channel('itsolution')->error($request->session()->get('id')." : verifySignature : ".$e->getMessage());
            $response = new stdClass;
            $response->msg = "failure";
            $response->error = $e->getMessage();
            $response->statusCode = 400;
            return $response;
        }        
    }
    
    public function paymentLumpsumAuthorized(Request $data) {
        try {
            $file = 'paymentLumpsumAuthorized'.time() .rand(). '_file.json';
            $destinationPath=public_path()."/uploads/";
            if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
            File::put($destinationPath.$file,$data);
            $payment_data = $data->payload['payment']['entity'];
            $method = $data->payload['payment']['entity']['method'];
            
            $subscription_detail = Razorpay_Subscription::where([
                'customer_id' => $data->account_id
            ])->first();
            $plan_detail = Razorpay_Plan::where([
                'razor_plan_id' => $subscription_detail['razor_plan_id']
            ])->first();
            if($method=="card") {
                $card_data = $data->payload['payment']['entity']['card'];
                $razorpay_card_status = $this->insertCardDetails($card_data, $subscription_detail['fr_user_id']);
                $card_id = $razorpay_card_status->id;
            } else {
                $card_id = null;
            }
            $razorpay_payment_status = $this->insertPaymentDetails($payment_data, $subscription_detail['fr_user_id'], $card_id, $subscription_detail->id, $plan_detail->id);
            return response()->json([
                'status' => '200'
            ]);
        } catch (\Exception $e) {
            \Log::channel('itsolution')->info($data." : ".$e);
            return $e->getMessage();
        }
    }

    public function paymentFailed(Request $data) {
        try {
            $file = 'paymentFailed_'.time() .rand(). '_file.json';
            $destinationPath=public_path()."/uploads/";
            if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
            $payment_data = $data->payload['payment']['entity'];
            $method = $data->payload['payment']['entity']['method'];
            File::put($destinationPath.$file,$data);
            $api = (new RazorpayController)->createSIPAPI();
            // $invDetails = $api->payment->fetch($payment_data['id']);
            // $invDetails = $api->order->fetch($payment_data['order_id']);
            $invDetails =  $api->invoice->fetch($payment_data['invoice_id']);
            $subscription_detail = Razorpay_Subscription::where([
                'razor_subscription_id' => $invDetails['subscription_id']
            ])->first();
            $userData = (new UsersController)->getUserDataByUID($subscription_detail['fr_user_id']);
            $plan_detail = Razorpay_Plan::where([
                'razor_plan_id' => $subscription_detail['razor_plan_id']
            ])->first();
            if(isset($payment_data['card_id'])) {
                $card_detail = CardDetails::where([
                    'razor_card_id' => $payment_data['card_id']
                ])->first();
                $cardid = $card_detail->id;
            } else {
                $cardid = "";
            }
            $razorpay_payment_status = $this->insertPaymentDetails($payment_data, $subscription_detail['fr_user_id'], $cardid, $subscription_detail->id, $plan_detail->id);
            $res2 = (new EmailController)->send_subscription_failed($userData['login_id'], $userData['cust_name'], "", $plan_detail, $subscription_detail->razor_subscription_id);
            return response()->json([
                'status' => '200'
            ]);
        } catch (\Exception $e) {
            \Log::channel('itsolution')->info($request->session()->get('id')." : ".$e->getMessage());
            return $e->getMessage();
        }
    }

    public function paymentLumpsumCaptured(Request $data) {
        try {
            $file = 'paymentCaptured_'.time() .rand(). '_file.json';
            $destinationPath=public_path()."/uploads/payments/";
            if (!is_dir($destinationPath)) {  
                mkdir($destinationPath,0777,true); 
            }
            $payment_data = $data->payload['payment']['entity'];
            $method = $data->payload['payment']['entity']['method'];
            File::put($destinationPath.$file,$data);
            $api = $this->createAPI();
            // $invDetails = $api->payment->fetch($payment_data['id']);
            // $invDetails = $api->order->fetch($payment_data['order_id']);
            $invDetails =  $api->invoice->fetch($payment_data['invoice_id']);
            $subscription_detail = Razorpay_Subscription::where([
                'razor_subscription_id' => $invDetails['subscription_id']
            ])->first();
            $userData = (new UsersController)->getUserDataByUID($subscription_detail['fr_user_id']);
            $plan_detail = Razorpay_Plan::where([
                'razor_plan_id' => $subscription_detail['razor_plan_id']
            ])->first();
            if(isset($payment_data['card_id'])) {
                $card_detail = CardDetails::where([
                    'razor_card_id' => $payment_data['card_id']
                ])->first();
                $cardid = $card_detail->id;
            } else {
                $cardid = "";
            }
            $razorpay_payment_status = $this->insertPaymentDetails($payment_data, $subscription_detail['fr_user_id'], $cardid, $subscription_detail->id, $plan_detail->id);
            // $res2 = (new EmailController)->send_subscription_failed($userData['login_id'], $userData['cust_name'], "", $plan_detail, $subscription_detail->razor_subscription_id);
            return response()->json([
                'status' => '200'
            ]);
        } catch (\Exception $e) {
            \Log::channel('itsolution')->info($data);
            return $e->getMessage();
        }
    }

    public function getSpecificPayment($rpi) {
        $api = $this->createAPI();
        try {
            // $attributes = array(
            //     'razorpay_order_id' => "order_MgB5ByK1p3gDix",
            //     'razorpay_payment_id' => "pay_MgB5NyBt54wURB",
            //     'razorpay_signature' => "e532fa5852beb5846674037de1715defb15e9aec299e7aaf5dd780067fbbe109"
            // );
            // $res = $api->utility->verifyPaymentSignature($attributes);
            // $res = $api->order->fetch("order_MgV0a65wcbPSvF");
            $res = $api->payment->fetch($rpi);
            if($res == "Invalid signature passed") {
                return "failed";
            } else {
                return "success";
            }
            $rpiData = $api->payment->fetch($rpi);
            $res = $this->saveRazorpayResponse($rpiData);
            return response()->json($res);
        } catch (\Exception $e) {
            \Log::channel('itsolution')->info($e->getMessage());
            return "sai :".$e->getMessage();
        }
    }
    
    public function saveRazorpayResponse($response) {
        try {
            $rpr = new Razorpay_Response();
            $rid = $response->toArray();
            // dd($response);
            $products = array( 
                "pay_id" => $rid['id'], 
                "entity" => $rid['entity'], 
                "amount" => $rid['amount'], 
                "currency" => $rid['currency'], 
                "status" => $rid['status'],
                "order_id" => $rid['order_id'],
                "invoice_id" => $rid['invoice_id'],
                "international" => $rid['international'],
                "method" => $rid['method'],
                "amount_refunded" => $rid['amount_refunded'],
                "refund_status" => $rid['refund_status'],
                "captured" => $rid['captured'],
                "description" => $rid['description'],
                "wallet" => $rid['wallet'],
                "vpa" => $rid['vpa'],
                "email" => $rid['email'],
                "contact" => $rid['contact'],
                "fee" => $rid['fee'],
                "tax" => $rid['tax'],
                "error_code" => $rid['error_code'],
                "error_description" => $rid['error_description']
            );
            $products["method"] = $rid['method'];
            if($rid['method'] == "card") {
                $products["card_name"] = $rid['card']['name'];
                $products["card_last4"] = $rid['card']['last4'];
                $products["card_network"] = $rid['card']['network'];
                $products["card_type"] = $rid['card']['type'];
                $products["card_issuer"] = $rid['card']['issuer'];
                $products["card_international"] = $rid['card']['international'];
                $products["card_emi"] = $rid['card']['emi'];
                $products["card_sub_type"] = $rid['card']['sub_type'];
                $products["card_token_iin"] = $rid['card']['token_iin'];
            } else {
                if($rid['method'] == "bank") {
                    $products["bank"] = $rid['bank'];
                    $products["bank_transaction_id"] = $rid['bank_transaction_id'];
                } else {
                    
                }
            }
            if($rid['notes']) {
                $products["notes_address"] = $rid['notes']['address'];
                $products["notes_descr"] = $rid['notes']['descr'];
            }
            if($rid['acquirer_data']) {
                $products["acquirer_auth_code"] = $rid['acquirer_data']['auth_code'];
                $products["acquirer_authentication_reference_number"] = isset($rid['acquirer_data']['authentication_reference_number'])?$rid['acquirer_data']['authentication_reference_number']:null;
                $products["acquirer_arn"] = isset($rid['acquirer_data']['arn'])?$rid['acquirer_data']['arn']:null;
            }
            // dd($products);
            $dbres = DB::table('razorpay_response')->insert($products);
            // dd($dbres);
            return $dbres;
        } catch (\Exception $e) {
            \Log::channel('itsolution')->info($request->session()->get('id')." : ".$e->getMessage());
            return $e->getMessage();
        }
    }

}
