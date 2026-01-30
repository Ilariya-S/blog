<?php

namespace App\Services\Posts\Providers;

use App\Providers\BaseServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;

class PostServiceProvider extends BaseServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadApiRoutes(__DIR__ . '/../Routes/routes.php');
    }
}
