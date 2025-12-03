<?php

namespace App\Services\Users\Providers;

use App\Providers\BaseServiceProvider;



class UserServiceProvider extends BaseServiceProvider
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
        $this->loadApiRoutes(base_path('app\Service\Users\Routes\routes.php'));
    }
}
