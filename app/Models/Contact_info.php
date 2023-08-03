<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact_info extends Model
{
    protected $table = 'contact_info';
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'contact_id',
        'con_name',
        'con_email',
        'con_mobile',
        'con_msg'
    ];
}
