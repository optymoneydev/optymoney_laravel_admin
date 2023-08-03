<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bfsi_users_detail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pk_user_detail_id', 'fr_user_id', 'cust_name', 'father_name', 'pan_number', 'aadhaar_no', 'isd', 'contact_no', 'landline', 'email', 'sex', 'dob', 
        'age', 'nationality', 'religion', 'company_name', 'address1', 'address2', 'address3', 'city', 'state', 'pincode', 'country', 'cor_addr1', 'cor_addr2', 
        'cor_addr3', 'cor_city', 'cor_state', 'cor_country', 'cor_zip', 'profession', 'mother_name', 'occupation', 'nominee_name', 'nominee_dob', 
        'r_of_nominee_w_app', 'fatcaInput', 'fatcaOutput', 'fatcaStatus', 'taxstatus', 'will_assets', 'custodianselected', 'kyc_onboarding_id', 'kyc_status', 'nsdl_kyc_status', 
        'nsdl_kyc_res', 'mode_holding', 'pi_place', 'pi_date', 'cor_as_perm', 'signature', 'cancelledcheque', 'signatureURL', 'cancelledchequeURL', 'ucc_submission', 
        'ucc_form_url', 'ucc_form_filename', 'pan_file', 'aug_kyc_status', 'details_modified_by', 'occupationCode', 'clientHolding', 'sourceOfWealth'
        
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_date' => 'datetime',
        'detail_created_date' => 'datetime',
        'detail_modified_date' => 'datetime',
        
    ];
}
