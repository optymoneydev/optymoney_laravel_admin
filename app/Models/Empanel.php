<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empanel extends Model
{
    protected $table = 'em_panel';
    protected $primaryKey = 'em_panel_id';
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'em_panel_id', 'name', 'email', 'mobile_no', 'city', 'gender', 'education', 'high_q_r', 'interest', 'training', 'about_yourself', 'cv', 'status', 'em_panelcol'
    ];

}
