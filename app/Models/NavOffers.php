<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NavOffers extends Model
{
    use HasFactory;
    protected $table = 'mf_nav_offer';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 
        'offer_name', 
        'offer_group', 
        'offer_status', 
        'created_at',
        'updated_at',
        'created_by', 
        'updated_by', 
        'created_ip', 
        'updated_ip'
    ];
    
}
