<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mfscheme extends Model
{
    protected $table = 'mf_master';
    protected $primaryKey = 'pk_nav_id';
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'unique_no', 
        'scheme_code', 
        'rta_scheme_code', 
        'amc_scheme_code', 
        'isin', 
        'amc_code', 
        'scheme_type', 
        'scheme_plan', 
        'scheme_name', 
        'purchase_allowed', 
        'purchase_transaction_mode', 
        'minimum_purchase_amount', 
        'additional_purchase_amount', 
        'maximum_purchase_amount', 
        'purchase_amount_multiplier', 
        'purchase_cutoff_time', 
        'redemption_allowed', 
        'redemption_transaction_mode', 
        'minimum_redemption_qty', 
        'redemption_qty_multiplier', 
        'maximum_redemption_qty', 
        'redemption_amount_minimum', 
        'redemption_amount_maximum', 
        'redemption_amount_multiple', 
        'redemption_cutoff_time', 
        'rta_agent_code', 
        'amc_active_flag', 
        'dividend_reinvestment_flag', 
        'sip_flag', 
        'stp_flag', 
        'swp_flag', 
        'switch_flag', 
        'settlement_type', 
        'amc_ind', 
        'face_value', 
        'start_date', 
        'end_date', 
        'exit_load_flag', 
        'exit_load', 
        'lockin_period_flag', 
        'lockin_period', 
        'channel_partner_code', 
        'astp_transaction_mode', 
        'astp_in_minimum_installment_amount', 
        'astp_in_maximum_installment_amount', 
        'astp_in_multiplier_amount', 
        'astp_out_minimum_installment_amount', 
        'astp_out_maximum_installment_amount', 
        'astp_out_multiplier_amount', 
        'astp_minimum_installment_units', 
        'astp_maximum_installment_units', 
        'astp_multiplier_units', 
        'astp_minimum_installment_numbers', 
        'astp_maximum_installment_numbers', 
        'astp_reg_in', 
        'astp_reg_out', 
        'astp_frequency', 
        'astp_dates', 
        'astp_minimum_gap', 
        'astp_maximum_gap', 
        'astp_installment_gap', 
        'astp_status', 
        'sip_transaction_mode', 
        'sip_frequency', 
        'sip_dates', 
        'sip_minimum_gap', 
        'sip_maximum_gap', 
        'sip_installment_gap', 
        'sip_status', 
        'sip_minimum_installment_amount', 
        'sip_maximum_installment_amount', 
        'sip_multiplier_amount', 
        'sip_minimum_installment_numbers', 
        'sip_maximum_installment_numbers', 
        'pause_flag', 
        'pause_minimum_installments', 
        'pause_maximum_installments', 
        'pause_modification_count', 
        'created_timestamp', 
        'rec_up', 
        'sch_risk', 
        'sch_category', 
        'sch_popularity', 
        'sch_fundsize', 
        'offer', 
        'sch_dataanalytics', 
        'recommended', 
        'sch_priority',
        'price_date', 
        'net_asset_value', 
        'repurchase_price', 
        'sale_price',
        'one_year_return',
        'three_year_return',
        'five_year_return'
    ];

}
