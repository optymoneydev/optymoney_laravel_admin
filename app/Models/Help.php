<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Help extends Model
{
    protected $table = 'help';
    protected $primaryKey = 'help_id';
    use HasFactory;

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'help_created_date';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'help_modified_date';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'help_id', 
        'help_category', 
        'help_sub_category', 
        'help_question', 
        'help_answer', 
        'help_keywords', 
        'help_status', 
        'help_created_by', 
        'help_modified_by', 
        'help_created_ip', 
        'help_modified_ip'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'help_created_date' => 'datetime',
        'help_modified_date' => 'datetime',
    ];

}
