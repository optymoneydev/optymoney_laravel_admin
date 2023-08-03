<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupons extends Model
{
    use HasFactory;
    protected $table = 'coupon_list';
    protected $primaryKey = 'cou_id';
    use HasFactory;

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'cou_created_date';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'cou_modified_date';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cou_id',
        'cou_name',
        'cou_per',
        'cou_quantity',
        'cou_partner_cmpny',
        'cou_validity',
        'cou_code',
        'cou_created_date',
        'cou_modified_date',
        'cou_created_by',
        'cou_created_ip',	
        'cou_modified_by',	
        'cou_modified_ip'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'cou_created_date' => 'datetime',
        'cou_modified_date' => 'datetime',
    ];
}
