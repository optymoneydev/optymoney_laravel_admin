<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileUploads extends Model
{
    protected $table = 'file_uploads';
    protected $primaryKey = 'id';
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 
        'fr_user_id', 
        'fileName', 
        'fileType', 
        'fileSize', 
        'fileURL', 
        'uploadedFrom', 
        'display_name',
        'created_by', 
        'modified_by', 
        'created_ip', 
        'modified_ip',
        'created_at',
        'updated_at',
    ];

}
