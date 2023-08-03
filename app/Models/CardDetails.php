<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardDetails extends Model
{
    protected $table = 'card_details';
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'fr_user_id', 'razor_card_id', 'entity', 'name', 'last4', 'network', 'type', 'issuer', 'international', 'emi', 'expiry_month', 'expiry_year'
    ];
}
