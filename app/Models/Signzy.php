<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Signzy extends Model
{
    use HasFactory;

    protected $table = 'signzy';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 
        'user_id', 
        'signzy_username', 
        'email', 
        'name',
        'phone',
        'signzy_id',
        'customerId',
        'initialNamespace',
        'channelInfo_id',
        'channelInfo_username',
        'channelInfo_name',
        'eventualNamespace',
        'applicationUrl',
        'mobileLoginUrl',
        'autoLoginUrL',
        'mobileAutoLoginUrl',
        'signzy_response'
    ];

}
