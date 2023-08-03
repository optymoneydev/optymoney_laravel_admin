<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Razorpay_Response extends Model
{
    protected $table = 'razorpay_response';
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "id", "pay_id", "entity", "amount", "currency", "status", "order_id", "invoice_id", "international", "method", "amount_refunded", "refund_status", "captured", "description", 
        "card_name", "card_last4", "card_network", "card_type", "card_issuer", "card_international", "card_emi", "card_sub_type", "card_token_iin", "bank", "bank_transaction_id", 
        "wallet", "vpa", "email", "contact", "notes_address", "notes_descr", "fee", "tax", "error_code", "error_description", "error_source", "error_step", "error_reason", 
        "acquirer_auth_code", "acquirer_arn", "acquirer_authentication_reference_number", "created_at", "updated_at"
    ];
}
