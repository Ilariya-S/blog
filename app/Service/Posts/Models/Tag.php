<?php

namespace App\Service\Posts\Models;

use Illuminate\Database\Eloquent\Model;
use App\Service\Posts\Models\Post;

class Tag extends Model
{
    protected $fillable = [
        'name',
    ];
    public function post()
    {
        return $this->belongsToMany(related: Post::class);
    }
}
