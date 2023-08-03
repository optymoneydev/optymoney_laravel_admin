<?php

namespace App\Http\Controllers\Subscription;

use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailController;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
Use App\Models\Subscription;
use View;


class SubscriptionController extends Controller
{
    public function getSubscription(Request $request) {
        $subscriptionData = Subscription::get()
              ->sortByDesc("sub_id")
              ->toJson();
        return $subscriptionData;
    }

    public function subscriptionAPI(Request $request) {
        $subscription = new Subscription();
        $subscription->sub_name = $request->formname;
        $subscription->sub_email = $request->formemail;
        $subscription->sub_mobile = $request->formnumber;
        $subscription_infoSave = $subscription->save();
        if($subscription_infoSave) {
            $res2 = (new EmailController)->send_subscription_success_pre($request->formemail, $request->formname, "") ;
            $data = [
                "statusCode" => 201,
                "data" => $subscription_infoSave
            ];
        } else {
            $data = [
                "statusCode" => 401,
                "message" => "Submission failed, please try again"
            ];
        }
        return $data;
    }

}
