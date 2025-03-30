<?php

namespace CSlant\Blog\Api\Services;

use Carbon\Carbon;
use CSlant\Blog\Api\Enums\StatusEnum;
use CSlant\Blog\Api\Models\VisitorLog;
use CSlant\Blog\Core\Models\Post;
use Illuminate\Database\Eloquent\Model;

class VisitorLogsService
{
    /**
     * @param  int  $postId
     * @param  null|string  $ipAddress
     * @param  null|string  $userAgent
     *
     * @return null|Model
     */
    public function trackPostView(
        int $postId,
        ?string $ipAddress,
        ?string $userAgent = null
    ): Model|null {
        $now = Carbon::now();

        /** @var Post $post */
        $post = Post::query()->lockForUpdate()->findOrFail($postId);

        if (!$post instanceof Post && $post->status !== StatusEnum::PUBLISHED->value) {
            return null;
        }

        $visitorLog = VisitorLog::query()->firstOrNew([
            'viewable_id' => $post->getKey(),
            'viewable_type' => Post::class,
            'ip_address' => $ipAddress ?: '',
        ]);

        $shouldCountView = !$visitorLog->exists || $now->isAfter($visitorLog->expired_at);

        if ($shouldCountView) {
            $visitorLog->fill([
                'user_agent' => $userAgent,
                'expired_at' => $now->copy()->addMinutes((int) config('blog-core.view_throttle_minutes')),
            ]);
            $visitorLog->save();

            Post::where('id', $postId)->increment('views');
        }

        return $post->refresh();
    }
}
