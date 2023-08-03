<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FetchEmail extends Model
{
    use HasFactory;
    protected $table = 'fetch_emails';
    protected $primaryKey = 'id';
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'email_date',
        'email_subject',
        'email_amc',
        'created_at',
        'updated_at'
    ];

}
