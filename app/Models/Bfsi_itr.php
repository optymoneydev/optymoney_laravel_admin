<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bfsi_itr extends Model
{
    protected $table = 'bfsi_itr';
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pk_itr_id', 
        'fr_form_id', 
        'fr_user_id', 
        'fr_customer_id', 
        'asses_year', 
        'itr_status', 
        'form_created_date', 
        'itr_pan', 
        'itr_xml', 
        'token_no', 
        'itr_v', 
        'sec_80c',
        'sec_80d',
        'itrv_comp_file',
        'created_by', 
        'created_ip',
        'created_at', 
        'updated_ip',
        'updated_by', 
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
