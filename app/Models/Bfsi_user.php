<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Bfsi_user extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;
    protected $table = 'bfsi_user';
    protected $primaryKey = 'pk_user_id';

    /**
     * Guard for the model
     *
     * @var string
     */
    protected $guard = 'userapi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pk_user_id',
        'fr_customer_id',
        'bse_id',
        'alternate_email_id',
        'login_id',
        'communication_email',
        'signup_date',
        'signup_ip',
        'user_status',
        'profile_image',
        'last_login',
        'nach_update',
        'p_code',
        'user_org',
        'mpin',
        'details_modified_by',
        'augid',
        'contact',
        'steps',
        'created_from'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'aug_pswd',
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

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }
}
