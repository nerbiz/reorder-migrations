<?php

use Illuminate\Support\Facades\Route;
use Nerbiz\ReorderMigrations\Controllers\ReorderMigrationsController;
use Nerbiz\ReorderMigrations\Middleware\InDevelopmentMiddleware;

Route::group([
    'middleware' => [InDevelopmentMiddleware::class, 'web', 'auth'],
], function () {
    Route::get('reorder-migrations', [ReorderMigrationsController::class, 'index'])
        ->name('reorderMigrations.index');

    Route::post('reorder-migrations', [ReorderMigrationsController::class, 'apply'])
        ->name('reorderMigrations.apply');
});
