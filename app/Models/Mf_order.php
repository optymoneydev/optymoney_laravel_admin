<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mf_order extends Model
{
    protected $table = 'mf_order';
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pk_order_id', 
        'order_ref_no', 
        'unique_ref_no', 
        'bse_order_id', 
        'fr_user_id', 
        'folio_no', 
        'scheme_code', 
        'scheme_name', 
        'scheme_type', 
        'inv_name', 
        'trxntype', 
        'trxnno', 
        'trxnmode', 
        'trxnstatus', 
        'traddate', 
        'postdate', 
        'purprice', 
        'units', 
        'amount', 
        'pan', 
        'euin', 
        'pipe_value', 
        'bse_remarks', 
        'payment_option', 
        'order_date',
        'created_ip',
        'created_at', 
        'updated_ip',
        'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'form_created_date' => 'datetime',
        
    ];

}
