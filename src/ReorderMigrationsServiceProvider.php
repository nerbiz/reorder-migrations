<?php

namespace Nerbiz\ReorderMigrations;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class ReorderMigrationsServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    const PACKAGE_NAME = 'Reorder Migrations';

    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->loadViewsFrom(__DIR__ . '/views', 'reorder-migrations');
    }
}
