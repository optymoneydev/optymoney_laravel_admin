<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class AugmontOrders extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'lockPrice',
        'emailId',
        'metalType',
        'quantity',
        'totalAmount',
        'merchantTransactionId',
        'userName',
        'userAddress',
        'userCity',
        'userState',
        'userPincode',
        'uniqueId',
        'mobileNumber',
        'modeOfPayment',
        'blockId',
        'statusCode',
        'preTaxAmount',
        'rate',
        'transactionId',
        'goldBalance',
        'silverBalance',
        'totalTaxAmount',
        'taxSplit_cgst_taxPerc',
        'taxSplit_cgst_taxAmount',
        'taxSplit_sgst_taxPerc',
        'taxSplit_sgst_taxAmount',
        'invoiceNumber',
        'ordertype',
        'razorpayURL',
        'razorpayId',
        'razorpayStatus',
        'razorpaySubscriptionId',
        'accountNumber',
        'ifscCode',
        'description',
        'augmont_input',
        'augmont_sell_status',
        'bankTransactionId',
        'paymentDate'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_date' => 'datetime',
        
    ];

}
