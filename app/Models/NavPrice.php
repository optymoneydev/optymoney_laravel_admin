<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NavPrice extends Model
{
    protected $table = 'mf_nav_price';
    protected $primaryKey = 'pk_price_id';
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pk_price_id', 
        'fr_nav_id', 
        'price_date', 
        'net_asset_value', 
        'repurchase_price', 
        'sale_price', 
        'ISIN', 
        'fr_unique_no', 
        'fr_scheme_code', 
        'fr_scheme_name', 
        'price_creation_timestamp', 
        'dividend_reinvest'
    ];

}
