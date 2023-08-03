<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KycStatus extends Model
{
    protected $table = 'kyc_status';
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "id",
        "fr_user_id",
        "accountId",
        "uniqueId",
        "merchantId",
        "panNumber",
        "panAttachment",
        "aadharNumber",
        "aadharAttachment",
        "aug_doc_submit",
        "aug_kyc_status",
        "nsdl_response",
        "nsdl_status",
        "rejectedReason",
        "signzyId",
        "signzy_username",
        "signzy_submit_date",
        "autoLoginUrL",
        "signzy_status",
        "errorCode"
    ];
}
