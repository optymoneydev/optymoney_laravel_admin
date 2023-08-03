<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsolidatedData extends Model
{
    protected $table = 'mf_consolidated_data';
    protected $primaryKey = 'mf_con_id';
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mf_con_id', 
        'mf_con_pan', 
        'mf_con_sch_type', 
        'mf_con_sch_name', 
        'mf_con_sch_code', 
        'mf_con_folio', 
        'mf_con_tot_inv', 
        'mf_con_cur_val', 
        'mf_con_profit', 
        'mf_con_tran_ids', 
        'mf_con_updated_date', 
        'mf_con_stamp_duty', 
        'mf_con_isin', 
        'mf_con_tot_units', 
        'mf_con_nav_id', 
        'mf_con_amc',
        'created_dt',
        'updated_dt'
    ];

}
