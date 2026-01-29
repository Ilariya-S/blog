<?php

namespace App\Services\Comments\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'author_name' => $this->user ? $this->user->name : 'Anonymous',
            'body' => $this->body,
            'created_at' => $this->created_at->format('Y-m-d H:i'),
            'replies' => CommentResource::collection($this->whenLoaded('replies')),
        ];
    }
}
