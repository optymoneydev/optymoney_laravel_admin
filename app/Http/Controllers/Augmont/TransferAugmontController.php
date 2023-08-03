<?php

namespace App\Http\Controllers\Augmont;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Augmont\AugmontController;
use Illuminate\Http\Request;

class TransferAugmontController extends Controller
{
    public function transferGoldSilver() {
        $authToken = (new AugmontController)->merchantAuth();
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => '{{url}}/merchant/v1/transfer',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('sender[uniqueId]' => '{{unique_id}}','receiver[uniqueId]' => '{{receiver_unique_id}}','receiver[mobileNumber]' => '{{receiver_mobile}}','receiver[name]' => 'Kamlesh','receiver[state]' => '{{state}}','metalType' => 'gold','quantity' => '0.1','merchantTransactionId' => '99faedca-bf53-4160-9078-877bcfed56f0'),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer {{token}}',
                'Accept: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }

    public function getTransferInfo() {
        $authToken = (new AugmontController)->merchantAuth();
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => '{{url}}/merchant/v1/transfer/{{transfer_merchant_txn_id}}/{{unique_id}}',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer {{token}}',
                'Accept: application/json',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }

    public function getTransferListing() {
        $authToken = (new AugmontController)->merchantAuth();
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => '{{url}}/merchant/v1/transfer',
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
