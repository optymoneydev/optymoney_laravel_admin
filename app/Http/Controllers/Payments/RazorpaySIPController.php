<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\OrdersAugmontController;
use Illuminate\Http\Request;
Use App\Models\AugmontOrders;
Use App\Models\Bfsi_user;
use Razorpay\Api\Api;
use Carbon\Carbon;
use Session;
use Exception;
Use hash_hmac;

use App\Http\Controllers\Payments\RazorpayPlanController;
use App\Http\Controllers\Payments\RazorpaySubscriptionController;

class RazorpaySIPController extends Controller
{
    public function razorpay() {        
        return view('razorpay');
    }

    public function sip_payment($data, $merchantTransactionId, $userdata, $amount) {
        // Create Razor Pay Plan
        // Period : daily, weekly, monthly, yearly
        // For daily plans, the minimum interval is 7.
        if($userdata->contact==null) {
            $contact = $userdata->contact_no;
        } else {
            $contact = $userdata->contact;
        }
        $razor_sip_plan_data = array(
            'period'=> 'monthly', 
            'interval'=> 1, 
            'item' => array(
                'name' => $userdata->pk_user_id.'_'.$data['sipInvestmentPurpose'], 
                'description' => $data['sipInvestmentPurpose'].' with the duration of '.$data['amount'], 
                'amount' => $amount, 
                'currency' => 'INR'
            ),
            'notes'=>array(
                'merchantTransactionId'=> $merchantTransactionId,
                'userid' => $userdata->pk_user_id
            ),
        );
        // dd($razor_sip_plan_data);
        $mytime = Carbon::now();
        $plan = (new RazorpayPlanController)->createPlan($razor_sip_plan_data);
        $razor_sip_subscription_data = array(
            'plan_id' => $plan->id, 
            'customer_notify' => 0,
            'total_count' => 600, 
            'start_at' => strtotime($data['sipDate']),
            "expire_by" => strtotime($mytime) + 3600,
            'addons' => array(
                array(
                    'item' => array(
                        'name' => 'Purchase of Gold/Silver', 
                        'amount' => $amount, 
                        'currency' => 'INR'
                    )
                )
            ),
            'notes'=> array(
                'merchantTransactionId'=> $merchantTransactionId,
                'userid' => $userdata->pk_user_id,
                'name' => $userdata->pk_user_id.'_'.$data['sipInvestmentPurpose'], 
                'description' => $data['sipInvestmentPurpose'].' with the duration of '.$data['amount'], 
                'amount' => $data['amount'], 
                'currency' => 'INR',
                'metalType' => $data['metalType']
            ),
            'notify_info'=>array(
                'notify_phone' => $contact,
                'notify_email'=> $userdata->login_id
            )
        );
        // dd($razor_sip_subscription_data);
        $subscription = (new RazorpaySubscriptionController)->createSubscription($razor_sip_subscription_data);
        return $subscription;
    }

    public function cancelSubscription($data) {
        $api = new Api($key_id, $secret);
        $api->subscription->fetch($subscriptionId)->cancel($options);
    }
    
}
