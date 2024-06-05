<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\Payments\RazorpayController;
use App\Http\Controllers\OrdersAugmontController;
use App\Http\Controllers\Augmont\RatesAugmontController;
use App\Http\Controllers\Augmont\AugmontController;
use App\Http\Controllers\Augmont\BuyAugmontController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\Users\UsersController;
use Illuminate\Http\Request;
Use App\Models\AugmontOrders;
Use App\Models\Bfsi_user;
Use App\Models\Razorpay_Plan;
Use App\Models\Razorpay_Subscription;
Use App\Models\Razorpay_Subscription_Payment;
Use App\Models\CardDetails;
use Razorpay\Api\Api;
use Session;
use Exception;
Use hash_hmac;
use File;

class RazorpaySubscriptionController extends Controller
{
    public function createSubscription($data) {
        try {
            $api = (new RazorpayController)->createSIPAPI();
            $subscriptionStatus = $api->subscription->create($data);

            $userProfile = (new UsersController)->getUserDataByUID($subscriptionStatus->notes->userid);
            $subscription = new Razorpay_Subscription();
            $subscription->fr_user_id = $subscriptionStatus->notes->userid;
            $subscription->razor_subscription_id = $subscriptionStatus->id;
            $subscription->subscription_plan = $subscriptionStatus->notes->name;
            $subscription->entity = $subscriptionStatus->entity;
            $subscription->razor_plan_id = $subscriptionStatus->plan_id;
            $subscription->status = $subscriptionStatus->status;
            $subscription->current_start = $subscriptionStatus->current_start;
            $subscription->current_end = $subscriptionStatus->current_end;
            $subscription->ended_at = $subscriptionStatus->ended_at;
            $subscription->quantity = $subscriptionStatus->quantity;
            $subscription->charge_at = $subscriptionStatus->charge_at;
            $subscription->start_at = $subscriptionStatus->start_at;
            $subscription->end_at = $subscriptionStatus->end_at;
            $subscription->auth_attempts = $subscriptionStatus->auth_attempts;
            $subscription->total_count = $subscriptionStatus->total_count;
            $subscription->paid_count = $subscriptionStatus->paid_count;
            $subscription->customer_notify = $subscriptionStatus->customer_notify;
            $subscription->expire_by = $subscriptionStatus->expire_by;
            $subscription->short_url = $subscriptionStatus->short_url;
            $subscription->has_scheduled_changes = $subscriptionStatus->has_scheduled_changes;
            $subscription->change_scheduled_at = $subscriptionStatus->change_scheduled_at;
            $subscription->source = $subscriptionStatus->source;
            $subscription->remaining_count = $subscriptionStatus->remaining_count;
            $savesubscription = $subscription->save();
            if($savesubscription) {
                $message = 'Your subscription with OPTYMONEY will start on '.date("Y-m-d", $subscriptionStatus->start_at).'.';
                $res2 = (new EmailController)->send_subscription_success($userProfile->login_id, $userProfile->cust_name, $message, $subscriptionStatus);
            } else {
                $message = 'Your subscription with OPTYMONEY got failed, try after sometime';
                $res2 = (new EmailController)->send_subscription_success($userProfile->login_id, $message, $subscriptionStatus);
            }
            return $subscription;
        } catch (\Exception $e) {
            \Log::channel('webhooks')->error(json_encode(['input' => $data, 'function' => "createSubscription", 'exception' => $e]));
            return $e;
        }
    }

    public function createSubscriptionLink($data) {
        $api = (new RazorpayController)->createSIPAPI();
        $subscriptionStatus = $api->subscription->create($data);
        return $subscriptionStatus;
    }

    public function fetchAllSubscriptions() {
        try {
            $api = (new RazorpayController)->createSIPAPI();
            $razorPostData = array(
                "plan_id"=> 1,
                "from"=>1,
                "to"=> true,
                "count"=>1,
                "skip"=> "1"
            );
        
            $subscriptions = $api->subscription->all($options);
            return $subscriptions;
        } catch (\Exception $e) {
            \Log::channel('webhooks')->info($request->session()->get('id')." : ".$e->getMessage());
            return $e->getMessage();
        }
    }

    public function fetchSubscriptionById($subscriptionId) {
        try {
            $api = (new RazorpayController)->createSIPAPI();
        
            $subscription_data = $api->subscription->fetch($subscriptionId);
            $razorpay_subscription_status = Razorpay_Subscription::where('razor_subscription_id', $subscription_data['id'])->update([
                'status' => $subscription_data['status'],
                'total_count' => $subscription_data['total_count'],
                'paid_count' => $subscription_data['paid_count'],
                'expire_by' => $subscription_data['expire_by'],
                'remaining_count' => $subscription_data['remaining_count']
            ]);
            return $razorpay_subscription_status;
        } catch (\Exception $e) {
            \Log::channel('webhooks')->info($request->session()->get('id')." : ".$e);
            return $e;
        }
    }

    public function cancelSubscriptionById(Request $request) {
        try {
            $subscription_details = Razorpay_Subscription::where('id', $request->sub_id)->get();
            $api = (new RazorpayController)->createSIPAPI();
            $subscription = $api->subscription->fetch($subscription_details[0]->razor_subscription_id)->cancel();
            $razorpay_subscription_status = Razorpay_Subscription::where('razor_subscription_id', $subscription_details['id'])->update([
                'status' => $subscription['status']
            ]);
            return $subscription['status'];
        } catch (\Exception $e) {
            \Log::channel('webhooks')->info($request->session()->get('id')." : ".$e->getMessage());
            return $e->getMessage();
        }
    }

    // Webhooks for razorpay
    public function subscriptionCharged(Request $data) {
        \Log::channel('webhooks')->info("subscription charged : ".$data);
        try {
            $general = new GeneralController();
            $subscription_data = $data->payload['subscription']['entity'];
            $payment_data = $data->payload['payment']['entity'];
            $method = $data->payload['payment']['entity']['method'];
            $subscriptionData = Razorpay_Subscription::where('razor_subscription_id', $subscription_data['id'])->first();
            if($subscriptionData) {
                $plan_detail = Razorpay_Plan::where(['razor_plan_id' => $subscriptionData->razor_plan_id])->first();
                $razorpay_subscription_status = Razorpay_Subscription::where('razor_subscription_id', $subscription_data['id'])->update([
                    'customer_id' => $subscription_data['customer_id'],
                    'acc_id' => $data->account_id,
                    'status' => $subscription_data['status'],
                    'current_start' => $subscription_data['current_start'],
                    'current_end' => $subscription_data['current_end'],
                    'ended_at' => $subscription_data['ended_at'],
                    'quantity' => $subscription_data['quantity'],
                    'total_count' => $subscription_data['total_count'],
                    'paid_count' => $subscription_data['paid_count'],
                    'expire_by' => $subscription_data['expire_by'],
                    'remaining_count' => $subscription_data['remaining_count'],
                    'charge_at' => $subscription_data['charge_at'],
                    'start_at' => $subscription_data['start_at'],
                    'end_at' => $subscription_data['end_at'],
                    'auth_attempts' => $subscription_data['auth_attempts'],
                    'total_count' => $subscription_data['total_count'],
                    'paid_count' => $subscription_data['paid_count'],
                    'customer_notify' => $subscription_data['customer_notify'],
                    'expire_by' => $subscription_data['expire_by'],
                    'short_url' => $subscription_data['short_url'],
                    'has_scheduled_changes' => $subscription_data['has_scheduled_changes'],
                    'change_scheduled_at' => $subscription_data['change_scheduled_at'],
                    'source' => $subscription_data['source'],
                    'offer_id' => $subscription_data['offer_id'],
                    'remaining_count' => $subscription_data['remaining_count'],
                    'change_scheduled_at' => $subscription_data['change_scheduled_at']
                ]);
                
                if($method=="card") {
                    $card_data = $data->payload['payment']['entity']['card'];
                    $razorpay_card_status = $this->insertCardDetails($card_data, $subscriptionData->fr_user_id);
                    $card_id = $razorpay_card_status->id;
                } else {
                    $card_id = null;
                }
                $razorpay_payment_status = $this->insertPaymentDetails($payment_data, $subscriptionData->fr_user_id, $card_id, $subscriptionData->id, $plan_detail->id);
                \Log::channel('webhooks')->info("payment : ".$razorpay_payment_status);
                if($razorpay_payment_status==409) {
                    \Log::channel('webhooks')->info("Insert payment details failed");
                    return response()->json([
                        'status' => '409'
                    ]);
                } else {
                    \Log::channel('webhooks')->info("Augmont Order Started : ".json_encode($subscription_data));
                    \Log::channel('webhooks')->info("payment Data : ".json_encode($payment_data));
                    $id = $subscriptionData->fr_user_id;
                    $metalType = $subscription_data['notes']['metalType'];
                    $amount = $payment_data['amount']/100;
                    $merchantTransactionId = "AUGOM_".$id."_".$general->uniqueNumericId(5)."_".$general->uniqueNumericId(10);
                    \Log::channel('webhooks')->info("merchant transaction Data : ".$merchantTransactionId);
                    $userData = Bfsi_user::join('bfsi_users_details', 'bfsi_users_details.fr_user_id', '=', 'bfsi_user.pk_user_id')
                            ->where('bfsi_user.pk_user_id', $id)
                            ->get(['bfsi_user.*', 'bfsi_users_details.*'])->first();;
                    \Log::channel('webhooks')->info("user Data : ".$id."-".$userData->cust_name."-".$metalType."-".$amount);
                    $augmontOrder = $this->insertSIPOrder($id, 'SIP', $metalType, $amount, $userData, $payment_data, $merchantTransactionId);
                    \Log::channel('webhooks')->info("augmontOrder : ".$augmontOrder);
                    $form_params = [
                        'lockPrice' => $augmontOrder->lockPrice,
                        'emailId' => $augmontOrder->emailId,
                        'metalType' => $augmontOrder->metalType,
                        'amount' => floatval($augmontOrder->totalAmount),
                        'merchantTransactionId' => $augmontOrder->merchantTransactionId,
                        'userName' => $augmontOrder->userName,
                        'userAddress' => $augmontOrder->userAddress,
                        'userCity' => $augmontOrder->userCity,
                        'userState' => $augmontOrder->userState,
                        'userPincode' => $augmontOrder->userPincode,
                        'uniqueId' => $augmontOrder->uniqueId,
                        'blockId' => $augmontOrder->blockId,
                        'referenceType' => "sip",
                        'referenceId' => "aug_opty_".$payment_data['id'],
                        'mobileNumber' => $augmontOrder->mobileNumber,
                        'modeOfPayment' => $augmontOrder->modeOfPayment
                    ];
                    $augmontOrder->augmont_input = $form_params;
                    $saveOrderStatus = $augmontOrder->save();
                    \Log::channel('webhooks')->info(json_encode(['id' => $id, 'function' => "subscriptionCharged"]));
                    $augOrderRes = (new BuyAugmontController)->postOrderToAugmont($form_params);
                    \Log::channel('webhooks')->info(json_encode(['post order to augmont : ' => $form_params, 'response : ' => $augOrderRes]));
                    $orderData['augstatusCode'] = $augOrderRes->statusCode;
                    if(isset( $augOrderRes->errors)) {
                        $orderData['errors'] = $augOrderRes->errors;
                    }
                    $statusCode = $augOrderRes->statusCode; 
                    \Log::channel('webhooks')->info("augmontOrder status : ".json_encode($augOrderRes->result->data));
                    if($statusCode==200) {
                        $augmontRes = $augOrderRes->result->data;
                        \Log::channel('webhooks')->info(json_encode(['augmont response data : ' => $augmontRes]));
                        $augmontOrder->statusCode = "200";
                        $augmontOrder->quantity = $augmontRes->quantity;
                        $augmontOrder->preTaxAmount = $augmontRes->preTaxAmount;
                        $augmontOrder->transactionId = $augmontRes->transactionId;
                        $augmontOrder->goldBalance = $augmontRes->goldBalance;
                        $augmontOrder->silverBalance = $augmontRes->silverBalance;
                        $augmontOrder->totalTaxAmount = $augmontRes->taxes->totalTaxAmount;
                        $augmontOrder->taxSplit_cgst_taxPerc = $augmontRes->taxes->taxSplit[0]->taxPerc;
                        $augmontOrder->taxSplit_cgst_taxAmount = $augmontRes->taxes->taxSplit[0]->taxAmount;
                        $augmontOrder->taxSplit_sgst_taxPerc = $augmontRes->taxes->taxSplit[1]->taxPerc;
                        $augmontOrder->taxSplit_sgst_taxAmount = $augmontRes->taxes->taxSplit[1]->taxAmount;
                        $augmontOrder->invoiceNumber = $augmontRes->invoiceNumber;
                        $augmontOrder->augmont_input = json_encode($form_params);
                        $augmontOrder->description = json_encode($augmontRes);
                        $saveOrderStatus = $augmontOrder->save();
                        $res2 = (new EmailController)->send_purchase_success($augmontRes->transactionId);
                    } else {
                        if($statusCode=422) {
                            
                        }
                        $augmontOrder->statusCode = "200";
                        $saveOrderStatus = $augmontOrder->save();
                    }
                    File::put($destinationPath.$file,$augmontOrder);
                    \Log::channel('webhooks')->info("Transaction Successfully completed");
                    return response()->json([
                        'status' => '200'
                    ]);
                }
            } else {
                \Log::channel('itsolution')->error(json_encode(['Subscription Data does not exist : ' => $data]));
            }
        } catch (\Exception $e) {
            \Log::channel('webhooks')->info("Error : ".$e->getMessage());
            return $e;
        }
    }

    public function subscriptionAuthenticated(Request $data) {
        try {
            $file = 'subscriptionAuthenticated'.time() .rand(). '_file.json';
            $destinationPath=public_path()."/uploads/subscriptions/";
            if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
            File::put($destinationPath.$file,$data);
            $subscription_data = $data->payload['subscription']['entity'];
            $razorpay_subscription_status = Razorpay_Subscription::where('razor_subscription_id', $subscription_data['id'])->update([
                'customer_id' => $subscription_data['customer_id'],
                'acc_id' => $data->account_id,
                'status' => $subscription_data['status'],
                'current_start' => $subscription_data['current_start'],
                'current_end' => $subscription_data['current_end'],
                'total_count' => $subscription_data['total_count'],
                'paid_count' => $subscription_data['paid_count'],
                'expire_by' => $subscription_data['expire_by'],
                'remaining_count' => $subscription_data['remaining_count']
            ]);
            if($razorpay_subscription_status>0) {
                return response()->json([
                    'status' => '200',
                    'razorpay_subscription_status' => $razorpay_subscription_status
                ]);
            } else {
                return response()->json([
                    'status' => '500',
                    'razorpay_subscription_status' => $razorpay_subscription_status
                ]);
            }
        } catch (\Exception $e) {
            \Log::channel('webhooks')->info($data->account_id." : ".$e->getMessage());
            return $e->getMessage();
        }
    }

    public function subscriptionStatusUpdate($subscription_data, $account_id) {
        \Log::channel('webhooks')->warning($subscription_data);
        try {
            $razorpay_subscription_status = Razorpay_Subscription::where('razor_subscription_id', $subscription_data['id'])->update([
                'customer_id' => $subscription_data['customer_id'],
                'acc_id' => $account_id,
                'status' => $subscription_data['status'],
                'current_start' => $subscription_data['current_start'],
                'current_end' => $subscription_data['current_end'],
                'total_count' => $subscription_data['total_count'],
                'paid_count' => $subscription_data['paid_count'],
                'expire_by' => $subscription_data['expire_by'],
                'remaining_count' => $subscription_data['remaining_count']
            ]);
            if($razorpay_subscription_status>0) {
                return response()->json([
                    'status' => '200',
                    'razorpay_subscription_status' => $razorpay_subscription_status
                ]);
            } else {
                return response()->json([
                    'status' => '500',
                    'razorpay_subscription_status' => $razorpay_subscription_status
                ]);
            }
        } catch (\Exception $e) {
            \Log::channel('webhooks')->info($data->account_id." : ".$e->getMessage());
            return $e->getMessage();
        }
    }
    
    public function subscriptionActivated(Request $data) {
        try {
            $file = 'subscriptionActivated'.time() .rand(). '_file.json';
            $destinationPath=public_path()."/uploads/subscriptions/";
            if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
            File::put($destinationPath.$file,$data);
        
            $subscription_data = $data->payload['subscription']['entity'];
            $sub = Razorpay_Subscription::where('razor_subscription_id', $subscription_data['id'])->get();
            $razorpay_subscription_status = Razorpay_Subscription::where('razor_subscription_id', $subscription_data['id'])->update([
                'customer_id' => $subscription_data['customer_id'],
                'acc_id' => $data->account_id,
                'status' => $subscription_data['status'],
                'current_start' => $subscription_data['current_start'],
                'current_end' => $subscription_data['current_end'],
                'ended_at' => $subscription_data['ended_at'],
                'quantity' => $subscription_data['quantity'],
                'total_count' => $subscription_data['total_count'],
                'paid_count' => $subscription_data['paid_count'],
                'expire_by' => $subscription_data['expire_by'],
                'remaining_count' => $subscription_data['remaining_count'],
                'charge_at' => $subscription_data['charge_at'],
                'start_at' => $subscription_data['start_at'],
                'end_at' => $subscription_data['end_at'],
                'auth_attempts' => $subscription_data['auth_attempts'],
                'total_count' => $subscription_data['total_count'],
                'paid_count' => $subscription_data['paid_count'],
                'customer_notify' => $subscription_data['customer_notify'],
                'expire_by' => $subscription_data['expire_by'],
                'short_url' => $subscription_data['short_url'],
                'has_scheduled_changes' => $subscription_data['has_scheduled_changes'],
                'change_scheduled_at' => $subscription_data['change_scheduled_at'],
                'source' => $subscription_data['source'],
                'offer_id' => $subscription_data['offer_id'],
                'remaining_count' => $subscription_data['remaining_count'],
                'change_scheduled_at' => $subscription_data['change_scheduled_at']
            ]);
            if($razorpay_subscription_status>0) {
                return response()->json([
                    'status' => '200',
                    'razorpay_subscription_status' => $razorpay_subscription_status
                ]);
            } else {
                return response()->json([
                    'status' => '500',
                    'razorpay_subscription_status' => $razorpay_subscription_status
                ]);
            }
        } catch (\Exception $e) {
            \Log::channel('webhooks')->info($data." : ".$e->getMessage());
            return $e->getMessage();
        }
    }

    public function subscriptionStatus(Request $data) {
        try {
            $file = 'subscriptionStatus'.time() .rand(). '_file.json';
            $destinationPath=public_path()."/uploads/payments/";
            if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
            File::put($destinationPath.$file,$data);

            $subscription_data = $data->payload['subscription']['entity'];
            $plan_detail = Razorpay_Plan::where([
                'razor_plan_id' => $subscription_data['plan_id']
            ])->first();
            $userData = (new UsersController)->getUserDataByUID($subscription_data['notes']['userid']);
            $razorpay_subscription_status = Razorpay_Subscription::where('razor_subscription_id', $subscription_data['id'])->update([
                'status' => $subscription_data['status']
            ]);
            if($subscription_data['status']=="halted") {
                $res2 = (new EmailController)->send_subscription_halted($userData['login_id'], $userData['cust_name'], "", $plan_detail, $subscription_data['id']);
            }
            return response()->json([
                'status' => '200'
            ]);
        } catch (\Exception $e) {
            \Log::channel('webhooks')->info($request->session()->get('id')." : ".$e->getMessage());
            return $e->getMessage();
        }
    }

    public function paymentAuthorized(Request $data) {
        try {
            $file = 'paymentAuthorized'.time() .rand(). '_file.json';
            $destinationPath=public_path()."/uploads/";
            if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
            File::put($destinationPath.$file,$data);
            $payment_data = $data->payload['payment']['entity'];
            $method = $data->payload['payment']['entity']['method'];
            
            $subscription_detail = Razorpay_Subscription::where([
                'customer_id' => $data->account_id
            ])->first();
            $plan_detail = Razorpay_Plan::where([
                'razor_plan_id' => $subscription_detail['razor_plan_id']
            ])->first();
            if($method=="card") {
                $card_data = $data->payload['payment']['entity']['card'];
                $razorpay_card_status = $this->insertCardDetails($card_data, $subscription_detail['fr_user_id']);
                $card_id = $razorpay_card_status->id;
            } else {
                $card_id = null;
            }
            $razorpay_payment_status = $this->insertPaymentDetails($payment_data, $subscription_detail['fr_user_id'], $card_id, $subscription_detail->id, $plan_detail->id);
            return response()->json([
                'status' => '200'
            ]);
        } catch (\Exception $e) {
            \Log::channel('webhooks')->error(json_encode(['input' => $data, 'function' => "paymentAuthorized", 'exception' => $e]));
            return $e->getMessage();
        }
    }

    public function paymentFailed(Request $data) {
        try {
            $file = 'paymentFailed_'.time() .rand(). '_file.json';
            $destinationPath=public_path()."/uploads/";
            if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
            $payment_data = $data->payload['payment']['entity'];
            $method = $data->payload['payment']['entity']['method'];
            File::put($destinationPath.$file,$data);
            $api = (new RazorpayController)->createSIPAPI();
            // $invDetails = $api->payment->fetch($payment_data['id']);
            // $invDetails = $api->order->fetch($payment_data['order_id']);
            $invDetails =  $api->invoice->fetch($payment_data['invoice_id']);
            $subscription_detail = Razorpay_Subscription::where([
                'razor_subscription_id' => $invDetails['subscription_id']
            ])->first();
            $userData = (new UsersController)->getUserDataByUID($subscription_detail['fr_user_id']);
            $plan_detail = Razorpay_Plan::where([
                'razor_plan_id' => $subscription_detail['razor_plan_id']
            ])->first();
            if(isset($payment_data['card_id'])) {
                $card_detail = CardDetails::where([
                    'razor_card_id' => $payment_data['card_id']
                ])->first();
                $cardid = $card_detail->id;
            } else {
                $cardid = "";
            }
            $razorpay_payment_status = $this->insertPaymentDetails($payment_data, $subscription_detail['fr_user_id'], $cardid, $subscription_detail->id, $plan_detail->id);
            $res2 = (new EmailController)->send_subscription_failed($userData['login_id'], $userData['cust_name'], "", $plan_detail, $subscription_detail->razor_subscription_id);
            return response()->json([
                'status' => '200'
            ]);
        } catch (\Exception $e) {
            \Log::channel('webhooks')->info($request->session()->get('id')." : ".$e->getMessage());
            return $e->getMessage();
        }
    }

    public function invoiceEvents(Request $data) {
        try {
            $file = 'Invoice_'.time() .rand(). '_file.json';
            $destinationPath=public_path()."/uploads/payments/";
            if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
            File::put($destinationPath.$file,$data);
            return response()->json([
                'status' => '200'
            ]);
        } catch (\Exception $e) {
            \Log::channel('webhooks')->info($request->session()->get('id')." : ".$e->getMessage());
            return $e->getMessage();
        }
    }

    public function insertCardDetails($card_data, $userid) {
        try {
            $card_detail = CardDetails::where([
                'razor_card_id' => $card_data['id']
            ])->first();
            if($card_detail) {
                return $card_detail;
            } else {
                $CardDetail = new CardDetails();
                $CardDetail->fr_user_id = $userid;
                $CardDetail->razor_card_id = $card_data['id'];
                $CardDetail->entity = $card_data['entity'];
                $CardDetail->name = $card_data['name'];
                $CardDetail->last4 = $card_data['last4'];
                $CardDetail->network = $card_data['network'];
                $CardDetail->type = $card_data['type'];
                $CardDetail->issuer = $card_data['issuer'];
                $CardDetail->international = $card_data['international'];
                $CardDetail->emi = $card_data['emi'];
                $CardDetail->expiry_month = $card_data['expiry_month'];
                $CardDetail->expiry_year = $card_data['expiry_year'];
                
                $saveCardDetailStatus = $CardDetail->save();
                $card_detail = CardDetails::where([
                    'razor_card_id' => $card_data['id']
                ])->first();
                return $card_detail;
            }
        } catch (\Exception $e) {
            \Log::channel('webhooks')->info($request->session()->get('id')." : ".$e->getMessage());
            return $e->getMessage();
        }
    }

    public function insertPaymentDetails($payment_data, $userid, $cardid, $subscriptionid, $planid) {
        try {
            $payment_detail = Razorpay_Subscription_Payment::where([
                'razor_payment_id' => $payment_data['id']
            ])->first();
            if($payment_detail) {
                return 409;
            } else {
                // fr_user_id
                $PaymentDetail = new Razorpay_Subscription_Payment();
                $PaymentDetail->fr_user_id = $userid;
                $PaymentDetail->razor_plan_id = $planid;
                $PaymentDetail->razor_subscription_id = $subscriptionid;
                $PaymentDetail->razor_payment_id = $payment_data['id'];
                $PaymentDetail->amount = $payment_data['amount'];
                $PaymentDetail->currency = $payment_data['currency'];
                $PaymentDetail->status = $payment_data['status'];
                $PaymentDetail->order_id = $payment_data['order_id'];
                $PaymentDetail->invoice_id = $payment_data['invoice_id'];
                $PaymentDetail->international = $payment_data['international'];
                $PaymentDetail->method = $payment_data['method'];
                $PaymentDetail->amount_refunded = $payment_data['amount_refunded'];
                $PaymentDetail->amount_transferred = $payment_data['amount_transferred'];
                $PaymentDetail->refund_status = $payment_data['refund_status'];
                $PaymentDetail->captured = $payment_data['captured'];
                $PaymentDetail->description = $payment_data['description'];
                
                if(isset($payment_data['card_id'])) { $PaymentDetail->card_id = $cardid; }
                if(isset($payment_data['bank'])) { $PaymentDetail->bank = $payment_data['bank']; }
                if(isset($payment_data['wallet'])) { $PaymentDetail->wallet = $payment_data['wallet']; }
                if(isset($payment_data['vpa'])) { $PaymentDetail->vpa = $payment_data['vpa']; }
                $PaymentDetail->email = $payment_data['email'];
                $PaymentDetail->contact = $payment_data['contact'];
                $PaymentDetail->customer_id = $payment_data['customer_id'];
                $PaymentDetail->token_id = $payment_data['token_id'];
                $PaymentDetail->fee = $payment_data['fee'];
                $PaymentDetail->tax = $payment_data['tax'];
                $PaymentDetail->error_code = $payment_data['error_code'];
                $PaymentDetail->error_description = $payment_data['error_description'];
                if($payment_data['method']=="netbanking") {
                    $PaymentDetail->bank_transaction_id = $payment_data['acquirer_data']['bank_transaction_id'];
                }
                if($payment_data['method']=="card") {
                    $PaymentDetail->auth_code = json_encode($payment_data['card']);
                }
                if($payment_data['method']=="upi") {
                    $PaymentDetail->rrn = $payment_data['acquirer_data']['rrn'];
                }
                $savePaymentDetailStatus = $PaymentDetail->save();
                \Log::channel('webhooks')->info("insertPaymentDetails : ".$PaymentDetail);
                if($savePaymentDetailStatus>0) {
                    return 200;
                } else {
                    return 500;
                }
            }
        } catch (\Exception $e) {
            \Log::channel('webhooks')->info($userid." : ".$e->getMessage());
            return $e;
        }
    }

    public function insertSIPOrder($id, $ordertype, $metalType, $amount, $userData, $razorRes, $merchantTransactionId) {
        \Log::channel('webhooks')->info("insertSIPOrder started ");
        try {
            $currentRates_content = json_decode((new RatesAugmontController)->sipRates());
            \Log::channel('webhooks')->info("sip rates : ".json_encode($currentRates_content->result->data));
            $currentRates = $currentRates_content->result->data;
            \Log::channel('webhooks')->info("insertSIPOrder : ".json_encode($currentRates));

            $augmontOrders = new AugmontOrders();
            $augmontOrders->user_id = $id;
            if($metalType=="silver") {
                $rate = $currentRates->rates->sBuy;
            } else {
                if($metalType=="gold") {
                    $rate = $currentRates->rates->gBuy;
                } else {
                    $rate = 0;
                }
            }
            $augmontOrders->lockPrice = $rate;
            $augmontOrders->emailId = $razorRes['email'];
            $augmontOrders->metalType = $metalType;
            $augmontOrders->ordertype = $ordertype;
            $augmontOrders->totalAmount = $amount;
            $augmontOrders->merchantTransactionId = $merchantTransactionId;
            $augmontOrders->userName = $userData->cust_name;
            $augmontOrders->userAddress = $userData->address1;
            $augmontOrders->userCity = $userData->augcity;
            $augmontOrders->userState = $userData->augstate;
            $augmontOrders->userPincode = $userData->pincode;
            $augmontOrders->uniqueId = $userData->augid;
            $augmontOrders->mobileNumber = $userData->contact;
            $augmontOrders->razorpayOrderId = $razorRes['order_id'];
            $augmontOrders->razorpayId = $razorRes['id'];
            $augmontOrders->blockId = $currentRates->blockId;
            \Log::channel('webhooks')->info("insertSIPOrder : ".$augmontOrders);
            $saveOrderStatus = $augmontOrders->save();
            if($saveOrderStatus) {
                return $augmontOrders;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            \Log::channel('webhooks')->info("insertSIPOrder : ".$e->getMessage());
            return $e->getMessage();
        }
    }

    public function subscriptionEvents(Request $data) {
        $subscription_data = $data->payload['subscription']['entity'];
        $file = $subscription_data['id'].time() .rand(). '_file.json';
        $destinationPath=public_path()."/uploads/subscriptions/";
        if (!is_dir($destinationPath)) {  mkdir($destinationPath,0777,true);  }
        File::put($destinationPath.$file,$data);
        if($data->event == "subscription.charged") {
            return $this->subscriptionCharged($data);
        } else {
            return $this->subscriptionStatusUpdate($subscription_data, $data->account_id);
        }
    }

    public function paymentWebhooks(Request $data) {
        \Log::channel('webhooks')->info("payment:".$data);
        return response()->json([
            'status' => '200'
        ]);
    }

    public function orderEventsWebhooks(Request $data) {
        \Log::channel('webhooks')->warning("orderevents:".$data);
        return response()->json([
            'status' => '200'
        ]);
    }

    public function invoiceEventsWebhooks(Request $data) {
        \Log::channel('webhooks')->warning("invoice event:".$data);
        return response()->json([
            'status' => '200'
        ]);
    }

    public function settlementEventsWebhooks(Request $data) {
        \Log::channel('webhooks')->warning("settlement event :".$data);
        return response()->json([
            'status' => '200'
        ]);
    }

    public function fund_accountEventsWebhooks(Request $data) {
        \Log::channel('webhooks')->warning($data);
        return response()->json([
            'status' => '200'
        ]);
    }

    public function payoutEventsWebhooks(Request $data) {
        \Log::channel('webhooks')->warning($data);
        return response()->json([
            'status' => '200'
        ]);
    }

    public function refundEventsWebhooks(Request $data) {
        \Log::channel('webhooks')->warning($data);
        return response()->json([
            'status' => '200'
        ]);
    }

    public function transferEventsWebhooks(Request $data) {
        \Log::channel('webhooks')->warning($data);
        return response()->json([
            'status' => '200'
        ]);
    }

    public function accountEventsWebhooks(Request $data) {
        \Log::channel('webhooks')->warning($data);
        return response()->json([
            'status' => '200'
        ]);
    }

    public function paymentLinkEventsWebhooks(Request $data) {
        \Log::channel('webhooks')->warning($data);
        return response()->json([
            'status' => '200'
        ]);
    }
    
}
