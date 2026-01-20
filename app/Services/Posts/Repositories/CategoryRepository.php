<?php

namespace App\Services\Posts\Repositories;

use App\Services\Posts\Contacts\CategoryRepositoryInterface;
use App\Services\Posts\Models\Category;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Str;


class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function model()
    {
        return Category::class;
    }
    public function findOrCreateCategories(string $title): Category
    {
        return $this->model->firstOrCreate([
            'title' => Str::lower($title)
        ]);
    }

}