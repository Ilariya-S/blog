<?php

namespace App\Services\Posts\Repositories;

use App\Services\Posts\Contacts\TagRepositoryInterface;
use App\Services\Posts\Models\Tag;
use Illuminate\Support\Str;
use Prettus\Repository\Eloquent\BaseRepository;

class TagRepository extends BaseRepository implements TagRepositoryInterface
{
    public function model()
    {
        return Tag::class;
    }
    public function findOrCreateTag(string $title): Tag
    {
        return $this->model->firstOrCreate([
            'title' => Str::lower($title)
        ]);
    }

}