<?php

namespace App\Models;

use App\Traits\Slugable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blogs extends Model
{
    use HasFactory, Slugable;

    protected $table = 'blogs';
    protected $primaryKey = 'id';
    
    

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'post_date';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = 'post_modified';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 
        'post_author', 
        'post_content', 
        'title', 
        'post_keywords', 
        'status', 
        'comment_status', 
        'name', 
        'slug', 
        'post_category', 
        'post_parent', 
        'guid', 
        'blog_cat', 
        'comment_count', 
        'post_img', 
        'post_meta_id', 
        'alt_attr', 
        'post_created_by', 
        'post_modified_by', 
        'post_created_ip', 
        'post_modified_ip',
        'meta_keywords',
        'meta_description',
        'coverimage',
        'thumbnailimage',
        'iconimage'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'post_date' => 'datetime',
        'post_modified' => 'datetime',
    ];

    public function created_by(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'post_created_by');
    }

}
