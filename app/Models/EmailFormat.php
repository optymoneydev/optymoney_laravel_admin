<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailFormat extends Model
{
    use HasFactory;
    protected $table = 'emailformat';
    protected $primaryKey = 'emailformat_id';
    use HasFactory;

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'emailformat_created_date';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'emailformat_modified_date';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'emailformat_id', 
        'emailformat_name', 
        'emailformat_type', 
        'emailformat_content', 
        'emailformat_status', 
        'emailformat_created_by', 
        'emailformat_created_ip', 
        'emailformat_modified_by', 
        'emailformat_modified_ip'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'emailformat_created_date' => 'datetime',
        'emailformat_modified_date' => 'datetime',
    ];
}
