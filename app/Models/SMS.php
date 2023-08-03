<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SMS extends Model
{
    use HasFactory;
    protected $table = 'sms';
       

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'contact',
        'sms_type',
        'sms_content',
        'sms_status',
        'sms_otp',
        'sms_verification',
    ];
    
}
