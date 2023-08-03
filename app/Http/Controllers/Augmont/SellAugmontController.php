<?php

namespace App\Http\Controllers\Augmont;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Augmont\AugmontController;
use App\Http\Controllers\GeneralController;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
Use App\Models\AugmontOrders;
Use App\Models\Bfsi_user;
Use App\Models\Bfsi_bank_details;
use Session;

class SellAugmontController extends Controller
{

    public function saveSellOrder(Request $request) {
        $general = new GeneralController();
        
        $data = $request->all();
        $id = $request->session()->get('id');
        $merchantTransactionId = "AUGOM_".$id."_".$general->uniqueNumericId(5)."_".$general->uniqueNumericId(10);
        $userData = Bfsi_user::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'bfsi_user.pk_user_id')
              ->where('bfsi_user.pk_user_id', $request->session()->get('id'))
              ->get(['bfsi_user.*', 'bfsi_users_details.*']);
        if($data['userbank']=="newBank") {
            $userBankCheck = Bfsi_bank_details::where([
                'acc_no' => $data['acc_no'],
                'fr_user_id' => $id
            ])->first();
            if(isset($userBankCheck)) {
                $data['augstatusCode'] = 1002;
                $data['message'] = "Bank Account Already Exist";
                return response()->json($data);
            } else {
                $bankAccount = new Bfsi_bank_details();
                $bankAccount->fr_user_id = $id;
                $bankAccount->acc_no = $data['acc_no'];
                $bankAccount->bank_name = $data['bank_name'];
                $bankAccount->ifsc_code = $data['ifsc_code'];
                $bankAccount->augBankStatus = 'acitve';
                $bankStatus = $bankAccount->save();
                $userBank =[
                    'accountName' => $userData[0]->cust_name,
                    'accountNumber' => $data['acc_no'],
                    'ifscCode' => $data['ifsc_code']
                ];
            }
        } else {
            $bankAccount = Bfsi_bank_details::where('pk_bank_detail_id', $data['userbank'])->get(); 
            $userBank =[
                'accountName' => $userData[0]->cust_name,
                'accountNumber' => $bankAccount[0]->acc_no,
                'ifscCode' => $bankAccount[0]->ifsc_code
            ];
        }
        
        $form_params = [
            'uniqueId' => $userData[0]->augid,
            'mobileNumber' => $userData[0]->contact,
            'lockPrice' => $data['lockPrice'],
            'blockId' => $data['blockId'],
            'metalType' => $data['metalType'],
            'quantity' => $data['quantity'],
            'merchantTransactionId' => $merchantTransactionId,
            'userBank' => $userBank,
        ];
        $augOrderRes = $this->postSellOrderToAugmont($form_params);
        $data['augstatusCode'] = $augOrderRes->statusCode;
        $data['augResponse'] = $augOrderRes;
        if(isset( $augOrderRes->errors)) {
            $data['errors'] = $augOrderRes->errors;
        }
        if($augOrderRes->statusCode==200) {
            $up_stat = $this->insertOrder($id, $userData, $data, $augOrderRes->result->data, $augOrderRes->statusCode);
            return response()->json($data);
        } else {
            return response()->json($data);
        }
        
    }

    public function postSellOrderToAugmont($form_params) {
        $tokentype = "Bearer ";
        $authToken = $tokentype.(new AugmontController)->merchantAuth();

        if($authToken==401) {
            return 401;
            // return json_encode({
            //     "statusCode": 401,
            //     "message": "You are not authrorized to perform this request."
            //   });
        } else {
            return (new AugmontController)->clientRequests('POST', 'merchant/v1/sell', $form_params);
        }

    }

    public function insertOrder($id, $userData, $data, $augOrderData, $statusCode) {
        $augmontOrders = new AugmontOrders();
        $augmontOrders->user_id = $id;
        $augmontOrders->lockPrice = $augOrderData->rate;
        $augmontOrders->emailId = $userData[0]->login_id;
        $augmontOrders->metalType = $augOrderData->metalType;
        $augmontOrders->quantity = $augOrderData->quantity;
        $augmontOrders->totalAmount = $augOrderData->totalAmount;
        $augmontOrders->merchantTransactionId = $augOrderData->merchantTransactionId;
        $augmontOrders->userName = $userData[0]->cust_name;
        $augmontOrders->userAddress = $userData[0]->address1;
        $augmontOrders->userCity = $userData[0]->city;
        $augmontOrders->userState = $userData[0]->state;
        $augmontOrders->userPincode = $userData[0]->pincode;
        $augmontOrders->uniqueId = $userData[0]->augid;
        $augmontOrders->mobileNumber = $userData[0]->contact;
        $augmontOrders->ordertype = 'sell';
        $augmontOrders->blockId = $data['blockId'];
        $augmontOrders->statusCode = $statusCode;
        $augmontOrders->transactionId = $augOrderData->transactionId;
        $augmontOrders->goldBalance = $augOrderData->goldBalance;
        $augmontOrders->silverBalance = $augOrderData->silverBalance;
        // $augmontOrder->invoiceNumber = $augmontRes->invoiceNumber;
        $augmontOrders->accountNumber = $augOrderData->bankInfo->accountNumber;
        $augmontOrders->ifscCode = $augOrderData->bankInfo->ifscCode;

        $saveOrderStatus = $augmontOrders->save();
        if($saveOrderStatus) {
            return $augmontOrders;
        } else {
            return false;
        }
        
    }

    public function getBuyInfo() {

        $authToken = (new AugmontController)->merchantAuth();

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => '{{url}}/merchant/v1/buy/{{buy_merchant_txn_id}}/{{unique_id}}',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization:  Bearer {{token}}'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }

    public function merchantBuyList() {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => '{{url}}/merchant/v1/{{unique_id}}/buy',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: Bearer {{token}}'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }

    public function orderResponse(Request $request) {
        $augmontOrder = AugmontOrders::where([
            'merchantTransactionId' => $request->razorpay_payment_link_reference_id,
        ])->first();
        $augmontOrder->razorpayId = $request->razorpay_payment_id;
        $augmontOrder->razorpayStatus = $request->razorpay_payment_link_status;
        $saveOrderStatus = $augmontOrder->save();
        if($request->razorpay_payment_link_status=="paid") {
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
            // dd($form_params);

            $augOrderRes = $this->postOrderToAugmont($form_params);
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
                Session::put('ordstatus', "Order Placed Succesfully");
                return redirect()->intended('augmont/razorpayView')->with(['ordstatus' => "Order Placed Succesfully"]);
            } else {
                $augmontOrder->statusCode = "200";
                $saveOrderStatus = $augmontOrder->save();
                return redirect()->intended('augmont/razorpayView')->with(['error'=> "Order Failed", 'invoice' => ""]);
            }
            
        } else {
            return false;
        }
    }

    public function getSellInfo() {
        $authToken = (new AugmontController)->merchantAuth();
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => '{{url}}/merchant/v1/sell/{{sell_merchant_txn_id}}/{{unique_id}}',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization:  Bearer {{token}}'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }

    public function merchantSellList() {
        $authToken = (new AugmontController)->merchantAuth();
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => '{{url}}/merchant/v1/{{unique_id}}/sell',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: Bearer {{token}}'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }
}
