<?php

namespace CSlant\Blog\Api\Services;

use Carbon\Carbon;
use CSlant\Blog\Api\Enums\StatusEnum;
use CSlant\Blog\Api\Models\VisitorLog;
use CSlant\Blog\Core\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VisitorLogsService
{
    /**
     * @param  int  $postId
     * @param  null|string  $ipAddress
     * @param  null|string  $userAgent
     *
     * @return Post
     * @throws ModelNotFoundException
     */
    public function trackPostView(
        int $postId,
        ?string $ipAddress,
        ?string $userAgent = null
    ): Post {
        $now = Carbon::now();

        /** @var Post $post */
        $post = Post::query()->lockForUpdate()->findOrFail($postId);

        if ($post->status !== StatusEnum::PUBLISHED->value) {
            return $post;
        }

        $visitorLog = VisitorLog::query()->firstOrNew([
            'viewable_id' => $post->getKey(),
            'viewable_type' => Post::class,
            'ip_address' => $ipAddress ?: '',
        ]);

        $shouldCountView = !$visitorLog->exists || $now->isAfter($visitorLog->expired_at ?? $now->copy()->subMinute());

        if ($shouldCountView) {
            $visitorLog->fill([
                'user_agent' => $userAgent,
                'expired_at' => $now->copy()->addMinutes((int) config('blog-core.view_throttle_minutes')),
            ]);
            $visitorLog->save();

            Post::where('id', $postId)->increment('views');
            $post->refresh();
        }

        return $post;
    }
}
