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
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class VisitorLog extends Model
{
    protected $primaryKey = 'id';

    public $incrementing = true;

    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'viewable_type',
        'viewable_id',
        'ip_address',
        'user_agent',
        'expired_at',
        'created_at',
        'updated_at',
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
     * @return MorphTo<Model, $this>
     */
    public function viewable(): MorphTo
    {
        return $this->morphTo();
    }
}
