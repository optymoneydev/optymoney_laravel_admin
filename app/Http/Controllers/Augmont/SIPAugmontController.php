<?php

namespace App\Http\Controllers\Augmont;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Augmont\AugmontController;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class SIPAugmontController extends Controller
{
    public function sipRates() {
        try {
            $tokentype = "Bearer ";
            $authToken = $tokentype.(new AugmontController)->merchantAuth();

            if($authToken==401) {
                return json_encode([
                    "statusCode" => 401,
                    "message" => "You are not authrorized to perform this request."
                  ]);
            } else {
                return json_encode((new AugmontController)->clientRequests('GET', 'merchant/v1/sip/rates', ''));
            }
        } catch (\Exception $e) {
            \Log::channel('itsolution')->info($request->session()->get('id')." : ".$e->getMessage());
            return $e->getMessage();
        }
    }
}
