<?php

namespace App\Http\Controllers\Augmont;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Augmont\AugmontController;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class RatesAugmontController extends Controller
{
    /**
        * @OA\Get(
        * path="/api/augmont/currentRates",
        * operationId="currentRates",
        * tags={"Augmont"},
        * summary="Get Augmont Current Rates",
        * description="Get Augmont Current Rates",
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
    public function currentRates() {

        $tokentype = "Bearer ";
        $authToken = $tokentype.(new AugmontController)->merchantAuth();
        if($authToken==401) {
            return 401;
            // return json_encode({
            //     "statusCode": 401,
            //     "message": "You are not authrorized to perform this request."
            //   });
        } else {
            return json_encode((new AugmontController)->clientRequests('GET', 'merchant/v1/rates', ''));
        }
    }

    /**
        * @OA\Get(
        * path="/api/augmont/sipRates",
        * operationId="sipRates",
        * tags={"Augmont"},
        * summary="Get Augmont SIP Rates",
        * description="Get Augmont SIP Rates",
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
    public function sipRates() {
        $tokentype = "Bearer ";
        $authToken = $tokentype.(new AugmontController)->merchantAuth();

        if($authToken==401) {
            return 401;
            // return json_encode({
            //     "statusCode": 401,
            //     "message": "You are not authrorized to perform this request."
            //   });
        } else {
            return json_encode((new AugmontController)->clientRequests('GET', 'merchant/v1/sip/rates', ''));
        }
    }
}
