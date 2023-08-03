<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyAccounts extends Model
{
    protected $table = 'family_accounts';
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'fr_user_id', 'pan', 'family_pan_mobile', 'family_pan_mobile_otp', 'status',
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
