<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pms extends Model
{
    protected $table = 'pms';
    protected $primaryKey = 'pms_id';
    use HasFactory;

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'pms_created_date';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'pms_modified_date';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pms_id', 
        'pms_cust_id', 
        'pms_prod_type', 
        'pms_trans_date', 
        'pms_trans_type', 
        'pms_trans_amt', 
        'pms_document', 
        'pms_created_by', 
        'pms_created_ip', 
        'pms_modified_by', 
        'pms_modified_ip'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'pms_created_date' => 'datetime',
        'pms_modified_date' => 'datetime',
    ];

    public function getAuthPassword()
    {
      return $this->aug_pswd;
    }
}
