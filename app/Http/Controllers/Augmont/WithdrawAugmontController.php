<?php

namespace App\Http\Controllers\Augmont;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Augmont\AugmontController;
use Illuminate\Http\Request;

class WithdrawAugmontController extends Controller
{
    public function withdrawInfo ($txn_id, $unique_id) {
        $tokentype = "Bearer ";
        $authToken = $tokentype.(new AugmontController)->merchantAuth();

        if($authToken==401) {
            return 401;
            // return json_encode({
            //     "statusCode": 401,
            //     "message": "You are not authrorized to perform this request."
            //   });
        } else {
            $res = (new AugmontController)->clientRequests('GET', 'merchant/v1/withdraw/OM291316513007338155368653/Augo2052', '');
            return $res;
        }
    }

}
