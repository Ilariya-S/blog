<?php

namespace App\Services\Posts\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\Posts\Models\Post;

class Tag extends Model
{
    protected $fillable = [
        'name',
    ];
    public function posts()
    {
        return $this->belongsToMany(related: Post::class);
    }
}
