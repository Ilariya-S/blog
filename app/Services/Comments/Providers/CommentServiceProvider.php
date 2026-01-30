<?php

namespace App\Services\Comments\Providers;

use App\Providers\BaseServiceProvider;

class CommentServiceProvider extends BaseServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadApiRoutes(__DIR__.'/../Routes/routes.php');
    }
}
