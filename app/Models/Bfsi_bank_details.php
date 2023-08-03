<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bfsi_bank_details extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pk_bank_detail_id', 'fr_user_id', 'bank_name', 'acc_no', 'ac_type', 'branch_name', 'ifsc_code', 'swift_code', 'mandate_id', 'mandate_start_dt', 'mandate_end_dt', 
        'default_bank', 'bank_created_date', 'bank_modified_date', 'ba_addr1', 'ba_addr2', 'ba_city', 'ba_state', 'ba_zip', 'ba_country', 'augBankId', 'augBankStatus'
        
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
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