<?php

namespace App\Models;

use App\Traits\Slugable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Careerpanel extends Model
{
    use HasFactory, Slugable;

    protected $table = 'careerpanel';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'id', 
        'fr_user_id', 
        'candidate_name', 
        'contact_no', 
        'email', 
        'profileDoc'
    ];

}
