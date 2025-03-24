<?php

namespace CSlant\Blog\Api\Models;

use CSlant\Blog\Core\Models\Post;
use Illuminate\Database\Eloquent\Model;

class PostView extends Model
{
    protected $table = 'post_views';

    protected $fillable = [
        'post_id',
        'ip_address',
        'time_check',
    ];

    protected $casts = [
        'time_check' => 'datetime',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}
