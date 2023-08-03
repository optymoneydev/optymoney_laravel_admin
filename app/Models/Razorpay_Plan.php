<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Razorpay_Plan extends Model
{
    protected $table = 'razor_plan';
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fr_user_id',
        'razor_plan_id',
        'entity',
        'interval',
        'period',
        'active',
        'name',
        'description',
        'amount',
        'unit_amount',
        'currency',
        'type',
        'unit',
        'tax_inclusive',
        'hsn_code',
        'sac_code',
        'tax_rate',
        'tax_id',
        'tax_group_id'
    ];
}
