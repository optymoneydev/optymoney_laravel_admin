<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\OrdersAugmontController;
use Illuminate\Http\Request;
Use App\Models\AugmontOrders;
Use App\Models\Bfsi_user;
Use App\Models\Razorpay_Subscription;
use Razorpay\Api\Api;
use Session;
use Exception;
Use hash_hmac;

class RazorpayWebhookController extends Controller
{
    public function createAPI() {
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
        return $api;
    }

    public function createSIPAPI() {
        $api = new Api(env('RAZORPAY_SIP_KEY'), env('RAZORPAY_SIP_SECRET'));
        return $api;
    }

    public function handle(Request $request) {
        
        $data = $request->all();
        Razorpay_Subscription::where('id', 1)->update([
            'status' => "active"
        ]);
        return response()->json([
            'status' => 'ok'
        ]);
    }
    
}
