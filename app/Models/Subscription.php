<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;
    protected $table = 'subscription';
    protected $primaryKey = 'sub_id';
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sub_id',
        'sub_name',	
        'sub_email',
        'sub_mobile',	
        'sub_message',	
        'sub_date',
        'sub_status'
    ];

}
