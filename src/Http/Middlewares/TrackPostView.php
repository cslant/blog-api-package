<?php

namespace CSlant\Blog\Api\Http\Middlewares;

use Carbon\Carbon;
use Closure;
use CSlant\Blog\Api\Models\PostView;
use CSlant\Blog\Core\Models\Post;

class TrackPostView
{
    public function handle($request,Closure $next)
    {
        $postId = $request->route('id');
        $ipAddress = $request->ip();

        $post = Post::find($postId);
        if(!$post) return $next($request);

        $postView = PostView::where('post_id', $postId)
            ->where('ip_address', $ipAddress)
            ->first();

        $shouldIncrementView = false;

        if(!$postView)
        {
            // Access this post for the first time from this IP
            PostView::create([
                'post_id' => $postId,
                'ip_address' => $ipAddress,
                'time_check' => Carbon::now()->addHours(),
            ]);
            $shouldIncrementView = true;
        }
        else
        {
            // Check if field time_check is passed
            if(Carbon::now()->isAfter($postView->time_check))
            {
                // Update the field time_check
                $postView->update([
                    'time_check' => Carbon::now()->addHours(),
                ]);
                $shouldIncrementView = true;
            }
        }

        if($shouldIncrementView)
        {
            $post->increment('views');
        }

        return $next($request);
    }
}
