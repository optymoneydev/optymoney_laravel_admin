<?php

namespace App\Http\Controllers\Augmont;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Augmont\AugmontController;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class RatesAugmontController extends Controller
{
    public function currentRates() {

        $tokentype = "Bearer ";
        $authToken = $tokentype.(new AugmontController)->merchantAuth();
        // return $authToken;
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
