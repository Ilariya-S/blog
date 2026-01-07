<?php

namespace App\Services\Users\Providers;

use App\Providers\BaseServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;

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
        $this->loadApiRoutes(base_path(__DIR__ . '/../Routes/routes.php'));
        ResetPassword::createUrlUsing(function (object $user, string $token) {
            return "http://127.0.0.1:8000/api/users/reset-password?token={$token}&email={$user->email}";
        });
    }
}
