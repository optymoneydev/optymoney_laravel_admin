<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mf_cams extends Model
{
    protected $table = 'mf_cam';
    protected $primaryKey = 'pk_cam_id';
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amc_code', 
        'folio_no', 
        'prodcode', 
        'scheme', 
        'inv_name', 
        'trxntype', 
        'trxnno', 
        'trxnmode', 
        'trxnstat', 
        'usercode', 
        'usrtrxno', 
        'traddate', 
        'postdate', 
        'purprice', 
        'units', 
        'amount', 
        'brokcode', 
        'subbrok', 
        'brokperc', 
        'brokcomm', 
        'altfolio', 
        'rep_date', 
        'time1', 
        'trxnsubtyp', 
        'application_no', 
        'trxn_nature', 
        'tax', 
        'total_tax', 
        'te_15h', 
        'micr_no', 
        'remarks', 
        'swflag', 
        'old_folio', 
        'seq_no', 
        'reinvest_flag', 
        'mult_brok', 
        'stt', 
        'location', 
        'scheme_type', 
        'tax_status', 
        'load_1', 
        'scanrefno', 
        'pan', 
        'inv_iin', 
        'targ_src_scheme', 
        'trxn_type_flag', 
        'ticob_trtype', 
        'ticob_trno', 
        'ticob_posted_date', 
        'dp_id', 
        'trxn_charges', 
        'eligib_amt', 
        'src_of_txn', 
        'trxn_suffix', 
        'siptrxnno', 
        'ter_location', 
        'euin', 
        'euin_valid', 
        'euin_opted', 
        'sub_brk_arn', 
        'exch_dc_flag', 
        'src_brk_code', 
        'sys_regn_date', 
        'ac_no', 
        'bank_name', 
        'reversal_code', 
        'exchange_flag', 
        'ca_initiated_date', 
        'gst_state_code', 
        'igst_amount', 
        'cgst_amount', 
        'sgst_amount', 
        'rev_remark', 
        'original_t', 
        'stamp_duty', 
        'folio_old', 
        'scheme_fol', 
        'amc_ref_no', 
        'request_re', 
        'pk_cam_id', 
        'in_report', 
        'imported_date', 
        'in_c_report',
        'avail_units',
        'avail_amount',
        'reinvest_flag',
        'trxn_type_flag',
        'active_flag'
    ];

}
