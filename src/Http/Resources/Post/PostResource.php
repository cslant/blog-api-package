<?php

namespace CSlant\Blog\Api\Http\Resources\Post;

use CSlant\Blog\Api\Http\Resources\Author\AuthorResource;
use CSlant\Blog\Api\Http\Resources\Category\CategoryResource;
use CSlant\Blog\Api\Http\Resources\Comment\ListCommentResourceCollection;
use CSlant\Blog\Api\Http\Resources\Tag\TagResource;
use CSlant\Blog\Core\Facades\Base\Media\RvMedia;
use CSlant\Blog\Core\Models\Post;
use CSlant\Blog\Core\Models\Slug;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

/**
 * @mixin Post
 */
class PostResource extends JsonResource
{
    /**
     * @param $request
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        /** @var Post $this */
        $comments = $this->comments()
            ->orderBy((string) $request->get('order_by', 'created_at'), (string) $request->get('order', 'DESC'))
            ->paginate($request->integer('per_page', 10));

        $userId = 0;
        if (Auth::user()) {
            $userId = Auth::user()->id;
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug instanceof Slug ? $this->slug->key : $this->slug,
            'description' => $this->description,
            'content' => $this->content,
            'image' => $this->image ? RvMedia::url($this->image) : null,
            'categories' => CategoryResource::collection($this->categories),
            'tags' => TagResource::collection($this->tags),
            'author' => AuthorResource::make($this->author),
            'comments' => ListCommentResourceCollection::make($comments),
            'likes_count' => $this->likesCountDigital(),
            'is_liked' => $this->isLikedBy($userId),
            'comments_count' => $this->comments()->count(),
            'is_commented' => $this->isCommentBy($userId),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
