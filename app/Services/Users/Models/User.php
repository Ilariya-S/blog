<?php

namespace App\Services\Users\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\Posts\Models\Post;
use App\Services\Comments\Models\Comment;

class User extends Model
{
    protected $fillable = [
        'name',
        'email',
        'image',
    ];
    protected $hidden = [
        'password',
    ];
    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
