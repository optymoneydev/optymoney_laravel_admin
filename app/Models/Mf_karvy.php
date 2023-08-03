<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mf_karvy extends Model
{
    protected $table = 'mf_karvy';
    protected $primaryKey = 'pk_karvy_id';
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fmcode', 'td_fund', 'td_acno', 'schpln', 'funddesc', 'td_purred', 'td_trno', 'smcode', 'chqno', 'invname', 'trnmode', 'trnstat', 'td_branch', 'isctrno', 'td_trdt', 'td_prdt', 
        'td_pop', 'td_units', 'td_amt', 'td_agent', 'td_broker', 'brokper', 'brokcomm', 'invid', 'crdate', 'crtime', 'trnsub', 'td_appno', 'unqno', 'trdesc', 'td_trtype', 'chqdate', 
        'chqbank', 'divopt', 'puramt', 'purdate', 'sfunddt', 'trflag', 'td_nav', 'td_ptrno', 'stt', 'loadper', 'load1', 'purunits', 'ihno', 'branchcode', 'inwardnum0', 'pan1', 
        'nctremarks', 'navdate', 'pan2', 'pan3', 'tdsamount', 'sch1', 'pln1', 'prcode1', 'td_trxnmo1', 'clientid', 'dpid', 'status', 'rejtrnoor2', 'subtrtype', 'trcharges', 
        'atmcardst3', 'atmcardre4', 'brok_entdt', 'schemeisin', 'citycateg5', 'portdt', 'newunqno', 'euin', 'subarncode', 'evalid', 'edeclflag', 'assettype', 'sipregdt', 'divper', 
        'guardpanno', 'can', 'exchorgtr6', 'electrxnf7', 'sipregslno', 'cleared', 'invstate', 'tercat', 'pk_karvy_id', 'in_report', 'imported_date', 'in_c_report', 'reinvest_flag', 
        'created_at', 'updated_at', 'avail_units', 'avail_amount', 'active_flag', 'stamp_duty'
    ];
}
