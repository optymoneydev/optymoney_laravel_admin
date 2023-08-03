<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $table = 'faq';
    protected $primaryKey = 'faq_id';
    use HasFactory;

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'faq_created_date';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'faq_modified_date';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'faq_id', 
        'faq_category', 
        'faq_question', 
        'faq_answer', 
        'faq_keywords', 
        'faq_status', 
        'faq_created_by', 
        'faq_modified_by', 
        'faq_created_ip', 
        'faq_modified_ip'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'faq_created_date' => 'datetime',
        'faq_modified_date' => 'datetime',
    ];

}
