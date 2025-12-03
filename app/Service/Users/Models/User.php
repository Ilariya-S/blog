<?php

namespace App\Service\Users\Models;

use Illuminate\Database\Eloquent\Model;
use App\Service\Posts\Models\Post;
use App\Service\Comments\Models\Comment;

class User extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
    ];
    public function post()
    {
        return $this->hasMany(Post::class, 'user_id');
    }
    public function comment()
    {
        return $this->hasMany(Comment::class);
    }
}
