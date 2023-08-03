<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventUsers extends Model
{
    use HasFactory;
    protected $table = 'event_details';
    protected $primaryKey = 'event_d_id';
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
        'event_d_id',
        'user_id',	
        'event_p_code',	
        'user_org',
        'event_timestamp'
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
