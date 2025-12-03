<?php

namespace App\Service\Comments\Models;

use Illuminate\Database\Eloquent\Model;
use App\Service\Posts\Models\Post;
use App\Service\Users\Models\User;



class Comment extends Model
{
    protected $fillable = [
        'body',
        'user_id',
        'post_id',
        'parent_id',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*На майбутнє - подивтися
        /**
         * Відношення "Багато до одного" (BelongsTo) до батьківського коментаря.
*/
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
    /*
     * Відношення "Один до багатьох" (HasMany) до дочірніх коментарів (відповідей).
     *
    public function replies(): HasMany
    {
        // Або HasMany, якщо ви хочете отримати всі відповіді на одному рівні
        return $this->hasMany(Comment::class, 'parent_id');
    }

    /**
     * Аксесор для отримання імені автора коментаря.
     * Якщо user_id null, повертає 'Анонім'[cite: 64].
     * @return string
     *
    public function getAuthorNameAttribute(): string
    {
        // Перевіряємо, чи існує відношення user і чи встановлено ім'я
        return $this->user ? $this->user->name : 'Анонім';
    }
    */
}
