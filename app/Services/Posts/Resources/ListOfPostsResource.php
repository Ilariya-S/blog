<?php

namespace app\Services\Posts\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ListOfPostsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'short_text' => Str::limit($this->body, 150, '...'),
            'comments_count' => $this->comments_count,
            'views_count' => $this->views,
            'published_at' => $this->created_at->format('d.m.Y H:i'),
            'user' => [
                'name' => $this->users->name,
                'profile_url' => route('profile.show', $this->users->id),
            ],
            'tags' => $this->tags->map(function ($tag) {
                return ['title' => $tag->title];
            }),
            'links' => [
                'detail' => route('posts.show', $this->id),
                'edit' => $this->user_id === auth()->id()
                          ? route('posts.edit', $this->id)
                          : null,
            ],
        ];
    }
}
