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
use Illuminate\Support\Collection;
use Session;
use Exception;
Use hash_hmac;

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

    // dummy
    public function payment($augOrderData, $merchantTransactionId, $userData, $amount) {
        
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
        
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
                'Metal Type'=> $augOrderData['metalType']
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
                        "wallet"=>"0",
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
        return $order;
    }

    public function createOrder($augOrderData, $merchantTransactionId, $userData, $amount) {
        
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
        
        $razorPostData = array(
            'receipt' => $merchantTransactionId, 
            'amount' => (int)$amount, 
            'currency' => 'INR', 
            'notes'=> array(
                'Metal Type'=> $augOrderData['metalType']
            )
        );

        $order = $api->order->create($razorPostData); 
        // $order = $api->paymentLink->create($razorPostData);
        return $order;
    }

    public function verifySignature($data) {
        // $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
        // $attributes  = array('razorpay_signature'  => $data->razorpay_signature,  'razorpay_payment_id'  => $data->razorpay_payment_id,  'razorpay_order_id' => $data->razorpay_order_id);
        // $order  = $api->utility->verifyPaymentSignature($attributes);
        $razorpay_signature = $data->razorpay_signature;
        $generated_signature = hash_hmac('sha256', $data->razorpay_order_id . "|" . $data->razorpay_payment_id, env('RAZORPAY_KEY'));
        if ($generated_signature == $razorpay_signature) {
            return true;
        } else {
            return $razorpay_signature."----".$generated_signature;
        }
        
    }

    public function getSpecificPayment($rpi) {
        try {
            $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
            $rpiData = $api->payment->fetch($rpi);
            // $res = $this->saveRazorpayResponse($rpiData);
            return response()->json($rpiData->status);
        } catch (Exception $e) {
            return  $e->getMessage();
        }
    }
    
    public function saveRazorpayResponse($response) {
        $rpr = new Razorpay_Response();
        $rpr->pay_id = $response->id;
        $rpr->entity = $response->entity;
        $rpr->amount = $response->amount;
        $rpr->currency = $response->currency;
        $rpr->status = $response->status;
        $rpr->order_id = $response->order_id;
        $rpr->invoice_id = $response->invoice_id;
        $rpr->international = $response->international;
        $rpr->method = $response->method;
        $rpr->amount_refunded = $response->amount_refunded;
        $rpr->refund_status = $response->refund_status;
        $rpr->captured = $response->captured;
        $rpr->description = $response->description;
        if($response->method == "card") {
            $rpr->card_name = $response->card->name;
            $rpr->card_last4 = $response->card->last4;
            $rpr->card_network = $response->card->network;
            $rpr->card_type = $response->card->type;
            $rpr->card_issuer = $response->card->issuer;
            $rpr->card_international = $response->card->international;
            $rpr->card_emi = $response->card->emi;
            $rpr->card_sub_type = $response->card->sub_type;
            $rpr->card_token_iin = $response->card->token_iin;
        }
        if($response->method == "bank") {
            $rpr->bank = $response->bank;
            $rpr->bank_transaction_id = $response->bank_transaction_id;
        }
        $rpr->wallet = $response->wallet;
        $rpr->vpa = $response->vpa;
        $rpr->email = $response->email;
        $rpr->contact = $response->contact;
        if($response->notes) {
            $rpr->notes_address = $response->notes->address;
            $rpr->notes_descr = $response->notes->descr;
        }
        $rpr->fee = $response->fee;
        $rpr->tax = $response->tax;
        $rpr->error_code = $response->error_code;
        $rpr->error_description = $response->error_description;
        if($response->acquirer_data) {
            $rpr->acquirer_auth_code = isset($response->acquirer_data->auth_code) ? $response->acquirer_data->auth_code : null;
            $rpr->acquirer_arn = isset($response->acquirer_data->arn) ? $response->acquirer_data->arn : null;
            $rpr->bank_transaction_id = isset($response->acquirer_data->bank_transaction_id) ? $response->acquirer_data->bank_transaction_id : null;
            $rpr->acquirer_authentication_reference_number = isset($response->acquirer_data->authentication_reference_number)?$response->acquirer_data->authentication_reference_number:null;
        }
        $rprstat = $rpr->save();
        return $rprstat;
    }
}
