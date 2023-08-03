<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchemeOffers extends Model
{
    use HasFactory;
    protected $table = 'sch_offers';
    protected $primaryKey = 'id';

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'created_date';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'modified_date';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pk_sch_offer_id', 
        'sch_id', 
        'offer_id', 
        'priority',
        'created_by', 
        'modified_by', 
        'created_ip', 
        'modified_ip'
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_date' => 'datetime',
        'modified_date' => 'datetime',
    ];
}
