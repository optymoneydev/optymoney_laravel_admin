<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Razorpay_Subscription extends Model
{
    protected $table = 'razor_subscription';
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fr_user_id',
        'razor_plan_id',
        'razor_subscription_id',
        'subscription_plan',
        'customer_id',
        'acc_id',
        'entity',
        'status',
        'current_start',
        'current_end',
        'ended_at',
        'quantity',
        'charge_at',
        'start_at',
        'end_at',
        'auth_attempts',
        'total_count',
        'paid_count',
        'customer_notify',
        'expiry_by',
        'short_url',
        'has_scheduled_charges',
        'change_scheduled_at',
        'source',
        'offer_id',
        'remaining_count'
    ];
}
