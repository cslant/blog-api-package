<?php

namespace CSlant\Blog\Api\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property string $viewable_type
 * @property int $viewable_id
 * @property string $ip_address
 * @property null|string $user_agent
 * @property Carbon $expired_at
 * @property null|Carbon $created_at
 * @property null|Carbon $updated_at
 * @property-read \Eloquent|Model $viewable
 */
class VisitorLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'viewable_type',
        'viewable_id',
        'ip_address',
        'user_agent',
        'expired_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'expired_at' => 'datetime',
    ];

    /**
     * Get the parent viewable model.
     *
     * @phpstan-return \Illuminate\Database\Eloquent\Relations\MorphTo<\Illuminate\Database\Eloquent\Model, \CSlant\Blog\Api\Models\VisitorLog>
     */
    public function viewable(): MorphTo
    {
        /** @phpstan-var \Illuminate\Database\Eloquent\Relations\MorphTo<\Illuminate\Database\Eloquent\Model, \CSlant\Blog\Api\Models\VisitorLog> $relation */
        $relation = $this->morphTo();

        return $relation;
    }
}
