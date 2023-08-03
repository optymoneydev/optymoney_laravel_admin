<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SMS_Template extends Model
{
    use HasFactory;
    protected $table = 'sms_template';
    protected $primaryKey = 'sms_id';

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'sms_created_date';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'sms_modified_date';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sms_id',
        'sms_name',
        'sms_template_id',
        'sms_type',
        'sms_content',
        'sms_status',
        'sms_created_by',
        'sms_modified_by',
        'sms_created_ip',
        'sms_modified_ip'	

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'sms_created_date' => 'datetime',
        'sms_modified_date' => 'datetime',
    ];
    
}
