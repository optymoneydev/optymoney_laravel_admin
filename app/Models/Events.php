<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    use HasFactory;
    protected $table = 'events';
    protected $primaryKey = 'event_id';
    use HasFactory;

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'event_created_date';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'event_modified_date';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_id'	,
        'event_name',	
        'event_code',	
        'event_url',
        'event_date',	
        'event_status',	
        'event_img',	
        'event_meta_keywords',	
        'event_meta_description',	
        'event_content',	
        'event_img_code',	
        'event_subject',	
        'event_created_by',	
        'event_created_ip',	
        'event_modified_by',	
        'event_modified_ip'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'event_created_date' => 'datetime',
        'event_modified_date' => 'datetime',
    ];
}
