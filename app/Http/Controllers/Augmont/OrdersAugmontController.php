<?php

namespace App\Http\Controllers\Augmont;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Augmont\AugmontController;
use Illuminate\Http\Request;
Use App\Models\AugmontOrders;

class OrdersAugmontController extends Controller
{
    public function silverBuy() {

        $tokentype = "Bearer ";
        $authToken = $tokentype.(new AugmontController)->merchantAuth();

        $uniqueId = "Augo".$data['pk_user_id'];
        $headers = [
            'Content-Type' => 'application/json',
            'AccessToken' => 'key',
            'Authorization' => $authToken,
        ];
        $client = new Client(['verify' => false ]);
    
        $options = [
            'json' => [
                'lockPrice' => '{{g_buy_lock_price}}',
                'emailId' => '{{email}}',
                'metalType' => 'gold',
                'quantity' => '0.1',
                'merchantTransactionId' => '32475bf5-ec5b-4fce-9106-ce228492c646',
                'userName' => '{{name}}',
                'userAddress' => '{{address}}',
                'userCity' => '{{city}}',
                'userState' => '{{state}}',
                'userPincode' => '{{pincode}}',
                'uniqueId' => '{{unique_id}}',
                'blockId' => '{{block_id}}',
                'mobileNumber' => '{{mobile_number}}',
                'modeOfPayment' => 'NEFT'
            ],
            'headers' => $headers
        ]; 
        $res = $client->post(env('AUG_URL').'merchant/v1/buy', $options);

        $content = $res->getBody()->getContents();
        return $content;
    }

    public function OrdersById($id) {
        $data = AugmontOrders::where('user_id',$id)->orderBy("id", "desc")->get(); 
        return $data;
    }

    public function OrdersByUsers($id) {
        $data = AugmontOrders::where('user_id',$id)->orderBy("id", "desc")->get(); 
        return $data;
    }

    public function OrdersByTransactionId($id) {
        $data = AugmontOrders::where('id',$id)->first(); 
        return $data;
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

    /**
        * @OA\Get(
        * path="/api/augmont/getAugOrdersByUserAPI",
        * operationId="getAugOrdersByUserAPI",
        * tags={"Augmont"},
        * summary="Augmont Orders by user",
        * description="Augmont orders by user",
        * security={{"bearerAuth":{}}},
        *      @OA\Response(
        *          response=201,
        *          description="Data retrieved",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Data retrieved",
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
    public function getAugOrdersByUserAPI() {
        $user = auth('userapi')->user();
        if($user) {
            
            $id = $user->pk_user_id;
            $augmontOrdersData = AugmontOrders::where('user_id',$id)->whereNotNull('invoiceNumber')->orderBy("id", "desc")->get(); 
            $data = [
                "statusCode" => 201,
                "data" => $augmontOrdersData
            ];
		    return $data;
        } else {
            $data = [
                "statusCode" => 401,
                "message" => "Unauthenticated_data."
            ];
            return $data;
        }
    }
}
