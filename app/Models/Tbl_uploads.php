<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tbl_uploads extends Model
{
    protected $table = 'tbl_uploads';
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 
        'pan',
        'fathers_name',
        'aadhaar',
        'amount', 
        'user_id', 
        'description', 
        'address', 
        'file', 
        'type', 
        'size', 
        'file_status', 
        'return_file', 
        'dobofusr', 
        'bank', 
        'acno', 
        'ifsc', 
        'tax_userid', 
        'tax_pwd', 
        'c_acnt', 
        'f_travel', 
        'e_bill', 
        'upload_date', 
        'itr_e', 
        'noticeCopy', 
        'itrfiledcopy', 
        'addeassest', 
        'fileitr', 
        'addfileitr',
        'taxplan'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'upload_date' => 'datetime',
        
    ];

}
