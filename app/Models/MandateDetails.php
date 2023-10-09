<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MandateDetails extends Model
{
    protected $table = 'mandatedetails';
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mandateid', 'mandate_code', 'client_code', 'status', 'bankId', 'amount', 'approvedDate', 'collectionType', 
        'regnDate', 'remarks', 'mandateStatus', 'umrnno', 'uploadDate', 'created_at', 'updated_at'
    ];

}
