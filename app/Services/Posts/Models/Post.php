<?php

namespace App\Services\Posts\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\Users\Models\User;
use App\Services\Posts\Models\Tag;
use App\Services\Comments\Models\Comment;


class Post extends Model
{
    protected $fillable = [
        'title',
        'body',
        'user_id',
        'views',
    ];
    public function users()
    {
        return $this->belongsTo(User::class);
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag', 'post_id', 'tag_id');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    /* на майбутьнє
    // --- Аксесори для функціоналу ---

    /**
     * Аксесор для отримання прев'ю тексту (короткий текст - 150 символів).
     * Використовується на сторінці списку постів[cite: 40].
     * @return string

    public function getPreviewTextAttribute(): string
    {
        // У міграції 'bode', але використовуємо 'body'
        return Str::limit($this->body, 150, '...');
    }

    /**
     * Аксесор, щоб дізнатися, чи є "Пост без відповіді"[cite: 51].
     * @return bool
     *
    public function getIsUnansweredAttribute(): bool
    {
        return $this->comments()->count() === 0;
    }*/

}
