<?php

namespace CSlant\Blog\Api\Services;

use Carbon\Carbon;
use CSlant\Blog\Api\Models\VisitorLogs;
use CSlant\Blog\Core\Models\Post;
use Illuminate\Support\Facades\DB;

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

        /** @var Post */
        return DB::transaction(function () use ($postId, $ipAddress, $userAgent, $expirationMinutes): Post {
            $post = Post::findOrFail($postId);
            $entityType = get_class($post);
            $entityId = $post->getKey();
            $now = Carbon::now();

            /** @var null|VisitorLogs $existingView */
            $existingView = VisitorLogs::query()
                ->where('viewable_id', '=', $entityId)
                ->where('viewable_type', '=', $entityType)
                ->where('ip_address', '=', $ipAddress)
                ->first();

            if ($existingView === null) {
                VisitorLogs::create([
                    'viewable_id' => $entityId,
                    'viewable_type' => $entityType,
                    'ip_address' => $ipAddress,
                    'user_agent' => $userAgent,
                    'expired_at' => $now->copy()->addMinutes($expirationMinutes),
                ]);

                $post->increment('views');
            } else {
                if ($now->isAfter($existingView->expired_at)) {
                    $existingView->update([
                        'user_agent' => $userAgent,
                        'expired_at' => $now->copy()->addMinutes($expirationMinutes),
                    ]);

                    $post->increment('views');
                }
            }

            return $post->refresh();
        });
    }
}
