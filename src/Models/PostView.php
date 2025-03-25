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
 * @property Carbon $time_check
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<\CSlant\Blog\Api\Models\PostView> query()
 */
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

    /**
     * Get the post associated with this view.
     *
     * @return BelongsTo<\CSlant\Blog\Core\Models\Post, \CSlant\Blog\Api\Models\PostView>
     * @phpstan-ignore generics.notSubtype
     */
    public function post(): BelongsTo
    {
        // @phpstan-ignore-next-line
        return $this->belongsTo(Post::class, 'post_id');
    }
}
