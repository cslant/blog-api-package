<?php

namespace CSlant\Blog\Api\Models;

use Carbon\Carbon;
use CSlant\Blog\Core\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $post_id
 * @property string $ip_address
 * @property string|null $user_agent
 * @property Carbon $time_check
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Post|null $post
 */
class PostView extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'post_id',
        'ip_address',
        'user_agent',
        'time_check',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'time_check' => 'datetime',
    ];

    /**
     * Get the post associated with this view.
     *
     * @phpstan-return \Illuminate\Database\Eloquent\Relations\BelongsTo<\Illuminate\Database\Eloquent\Model, \CSlant\Blog\Api\Models\PostView>
     */
    public function post(): BelongsTo
    {
        /** @phpstan-var class-string<\Illuminate\Database\Eloquent\Model> $postClass */
        $postClass = Post::class;

        /** @phpstan-var \Illuminate\Database\Eloquent\Relations\BelongsTo<\Illuminate\Database\Eloquent\Model, \CSlant\Blog\Api\Models\PostView> $relation */
        $relation = $this->belongsTo($postClass, 'post_id');

        return $relation;
    }
}
