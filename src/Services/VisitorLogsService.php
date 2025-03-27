<?php

namespace CSlant\Blog\Api\Services;

use Carbon\Carbon;
use CSlant\Blog\Api\Models\VisitorLog;
use CSlant\Blog\Core\Models\Post;

class VisitorLogsService
{
    /**
     * Track a view for a post by ID.
     *
     * @param int $postId The post ID
     * @param null|string $ipAddress The viewer's IP address
     * @param null|string $userAgent The viewer's user agent
     * @return Post The updated post
     */
    public function trackPostView(int $postId, ?string $ipAddress, ?string $userAgent = null): Post
    {
        $expirationMinutes = (int) config('blog-core.expiration_view_time', 60);
        $ipAddress = $ipAddress ?? '';

        $post = Post::findOrFail($postId);
        $entityType = get_class($post);
        $entityId = $post->getKey();
        $now = Carbon::now();

        /** @var null|VisitorLog $existingView */
        $existingView = VisitorLog::query()
            ->where('viewable_id', '=', $entityId)
            ->where('viewable_type', '=', $entityType)
            ->where('ip_address', '=', $ipAddress)
            ->first();

        $expiredAt = $now->copy()->addMinutes($expirationMinutes);

        $visitorData = [
            'user_agent' => $userAgent,
            'expired_at' => $expiredAt,
        ];

        if (!$existingView instanceof VisitorLog) {
            VisitorLog::create(array_merge([
                    'viewable_id' => $entityId,
                    'viewable_type' => $entityType,
                    'ip_address' => $ipAddress,
                ], $visitorData));

            $post->increment('views');
        } elseif ($now->isAfter($existingView->expired_at)) {
            $existingView->update($visitorData);
            $post->increment('views');
        }

        return $post->refresh();
    }
}
