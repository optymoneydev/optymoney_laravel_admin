<?php

namespace App\Http\Controllers\Augmont;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Augmont\AugmontController;
use Illuminate\Http\Request;

class RedeemAugmontController extends Controller
{
    public function orderRedeem() {
        $authToken = (new AugmontController)->merchantAuth();
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => '{{url}}/merchant/v1/order',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('uniqueId' => '{{unique_id}}','mobileNumber' => '{{mobile_number}}','merchantTransactionId' => '5e7c9d5e-5a86-40c1-b8fc-32614f5c54bd','user[shipping][addressId]' => '{{user_address_id}}','product[0][sku]' => 'AU999GC01R','product[0][quantity]' => '1'),
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Authorization: Bearer {{token}}'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }

    public function orderList() {
        $authToken = (new AugmontController)->merchantAuth();
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => '{{url}}/merchant/v1/{{unique_id}}/order',
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

    public function orderInfo() {
        $authToken = (new AugmontController)->merchantAuth();
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => '{{url}}/merchant/v1/order/{{redeem_merchant_txn_id}}/{{unique_id}}',
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
