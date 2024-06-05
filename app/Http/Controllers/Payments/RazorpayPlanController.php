<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\Payments\RazorpayController;
use App\Http\Controllers\OrdersAugmontController;
use Illuminate\Http\Request;
Use App\Models\AugmontOrders;
Use App\Models\Bfsi_user;
Use App\Models\Razorpay_Plan;
use Razorpay\Api\Api;
use Session;
use Exception;
Use hash_hmac;

class RazorpayPlanController extends Controller
{
    public function createPlan($razor_sip_plan_data) {
        try {
            $api = (new RazorpayController)->createSIPAPI();
            $planStatus = $api->plan->create($razor_sip_plan_data);
            $plan = new Razorpay_Plan();
            $plan->fr_user_id = $planStatus->notes->userid;
            $plan->razor_plan_id = $planStatus->id;
            $plan->entity = $planStatus->entity;
            $plan->interval = $planStatus->interval;
            $plan->period = $planStatus->period;
            $plan->active = $planStatus->item->active;
            $plan->name = $planStatus->item->name;
            $plan->description = $planStatus->item->description;
            $plan->amount = $planStatus->item->amount;
            $plan->unit_amount = $planStatus->item->unit_amount;
            $plan->currency = $planStatus->item->currency;
            $plan->type = $planStatus->item->type;
            $plan->unit = $planStatus->item->unit;
            $plan->tax_inclusive = $planStatus->item->tax_inclusive;
            $plan->hsn_code = $planStatus->item->hsn_code;
            $plan->sac_code = $planStatus->item->sac_code;
            $plan->tax_rate = $planStatus->item->tax_rate;
            $plan->tax_id = $planStatus->item->tax_id;
            $plan->tax_group_id = $planStatus->item->tax_group_id;
            $savePlan = $plan->save();
            return $plan;
        } catch (\Exception $e) {
            \Log::channel('itsolution')->error(json_encode(['input' => $razor_sip_plan_data, 'function' => "createPlan", 'exception' => $e]));
            return $e;
        }
    }

    public function fetchAllPlans() {
        $api = (new RazorpayController)->createSIPAPI();
        $razorPostData = array(
            "plan_id"=> 1,
            "from"=>1,
            "to"=> true,
            "count"=>1,
            "skip"=> "1"
        );
        $plans = $api->plan->all($options);
        return $plans;
    }

    public function fetchPlanById($planId) {
        $api = (new RazorpayController)->createSIPAPI();
        $plans = $api->plan->fetch($planId);
        return $plans;
    }
    
}
