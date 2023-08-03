<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchemeFilters extends Model
{
    protected $table = 'scheme_filters';
    protected $primaryKey = 'id';
    use HasFactory;

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'option_created_date';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'option_modified_date';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'scheme_id',
        'options',
        'data',
        'option_created_by',
        'option_modified_by',
        'option_created_ip',
        'option_modified_ip'	

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'option_created_date' => 'datetime',
        'option_modified_date' => 'datetime',
    ];

}
