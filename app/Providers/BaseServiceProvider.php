<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

abstract class BaseServiceProvider extends ServiceProvider
{
    protected function loadApiRoutes(string $path, array $middleware = []): void
    {
        Route::group(['prefix' => 'api', 'middleware' => $middleware], function () use ($path) {
            $this->loadRoutesFrom($path);
        });
    }
}
