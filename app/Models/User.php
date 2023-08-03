<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'father_name',
        'mother_name',
        'nominee_name',
        'nominee_dob',
        'nominee_relation',
        'gender',
        'dob',
        'age',
        'nationality',
        'religion',
        'address1',
        'address2',
        'address3',
        'city',
        'state',
        'pincode',
        'country',
        'cor_address1',
        'cor_address2',
        'cor_address3',
        'cor_city',
        'cor_state',
        'cor_pincode',
        'cor_country',
        'email',
        'password',
        'mobile',
        'pan',
        'aadhaar',
        'bse_id',
        'kyc_status',
        'fatca_status',
        'signature',
        'signature_url',
        'cancelled_cheque',
        'cancelled_cheque_url',
        'ucc_submission',
        'ucc_form',
        'ucc_form_url',
        'profile_img',
        'paid',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'mobile_verified_at' => 'datetime',
        'bsc_verified_at' => 'datetime',
        'kyc_verified_at' => 'datetime',
        'fatca_verified_at' => 'datetime',
        'ucc_submit_at' => 'datetime',
    ];
}
