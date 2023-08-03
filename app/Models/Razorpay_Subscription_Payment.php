<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Razorpay_Subscription_Payment extends Model
{
    protected $table = 'razor_subscription_payment';
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'fr_user_id', 'razor_plan_id', 'razor_subscription_id', 'razor_payment_id', 'amount', 'currency', 'status', 'order_id', 'invoice_id', 
        'international', 'method', 'amount_refunded', 'amount_transferred', 'refund_status', 'captured', 'description', 'card_id', 'bank', 'wallet', 
        'vpa', 'email', 'contact', 'customer_id', 'token_id', 'fee', 'tax', 'error_code', 'error_description', 'bank_transaction_id', 'auth_code', 'rrn'
    ];
}
