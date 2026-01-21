<?php

namespace App\Services\Posts\Contacts;

interface TagRepositoryInterface
{
    public function findOrCreateTag(string $title);

}
