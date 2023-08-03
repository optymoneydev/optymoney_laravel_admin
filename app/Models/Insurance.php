<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    protected $table = 'insurance';
    protected $primaryKey = 'ins_id';
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ins_id', 
        'ins_cust_id', 
        'ins_prod_type', 
        'ins_comp_name', 
        'ins_comp_branch', 
        'ins_policy_name', 
        'ins_policy_no', 
        'ins_policy_issued_date', 
        'ins_policy_maturity_date', 
        'ins_policy_prem_amt', 
        'ins_policy_sa_amt', 
        'ins_policy_term_years', 
        'ins_policy_pay_mode', 
        'ins_policy_next_prem_date', 
        'ins_policy_plan_type', 
        'ins_policy_money_back', 
        'ins_policy_acci_death_benefit', 
        'ins_policy_status', 
        'ins_policy_nominee_name', 
        'ins_policy_nominee_relation', 
        'ins_policy_veh_type', 
        'ins_policy_veh_reg_no', 
        'ins_policy_veh_model', 
        'ins_policy_loan_taken', 
        'ins_policy_loan_date', 
        'ins_policy_bal_units', 
        'ins_policy_bal_date', 
        'ins_policy_cur_value', 
        'ins_policy_exp_maturity_value', 
        'ins_policy_remarks', 
        'ins_policy_document', 
        'ins_created_by', 
        'ins_created_ip', 
        'ins_modified_by', 
        'ins_modified_ip'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'ins_created_date' => 'datetime',
        'ins_modified_date' => 'datetime',
    ];

    public function getAuthPassword()
    {
      return $this->aug_pswd;
    }
}
