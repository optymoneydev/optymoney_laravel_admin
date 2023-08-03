<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpertAssistance extends Model
{
    use HasFactory;
    protected $table = 'expertassistance';
    protected $primaryKey = 'ea_id';
    use HasFactory;

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'ea_created_date';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'ea_modified_date';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ea_id',	
        'ea_name',	
        'ea_email',	
        'ea_mobile',	
        'ea_expected',
        'ea_status'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'ea_created_date' => 'datetime',
        'ea_modified_date' => 'datetime',
    ];
}
