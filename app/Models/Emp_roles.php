<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emp_roles extends Model
{
    protected $table = 'emp_roles';
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 
        'roleName', 
        'created_by', 
        'created_at', 
        'updated_by',
        'updated_at', 
        'roles'
    ];

}
