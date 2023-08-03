<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaigns extends Model
{
    use HasFactory;
    protected $table = 'campaigns';
    protected $primaryKey = 'pk_goal_id';
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pk_goal_id', 'user_id', 'signup_ip', 'mobile_no', 'mygoal_timestamp', 'campaign_code', 'city', 'par_name', 'par_myphoto', 'created_at', 'updated_at'
    ];

}
