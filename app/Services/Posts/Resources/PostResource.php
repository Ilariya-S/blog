<?php
namespace App\Services\Posts\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'views' => $this->views,
            'created_at' => $this->created_at->format('d.m.Y H:i'),
            'author' => [
                'name' => $this->users?->name ?? 'Невідомий автор',
            ],
            'category' => [
                'title' => $this->category?->title ?? 'Без категорії',

            ],
            'tags' => $this->tags->map(function ($tag) {
                return ['title' => $tag->title];
            }),
        ];
    }
}