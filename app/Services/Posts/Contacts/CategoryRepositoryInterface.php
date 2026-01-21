<?php

namespace App\Services\Posts\Contacts;

interface CategoryRepositoryInterface
{
    public function findOrCreateCategories(string $title);

}
