<?php

use Illuminate\Support\Facades\Route;
use Nerbiz\ReorderMigrations\Controllers\ReorderMigrationsController;
use Nerbiz\ReorderMigrations\Middleware\InDevelopmentMiddleware;

Route::group([
    'middleware' => [InDevelopmentMiddleware::class, 'web', 'auth'],
], function () {
    Route::get('reorder-migrations', [ReorderMigrationsController::class, 'reorder'])
        ->name('reorderMigrations.reorder');

    Route::post('reorder-migrations', [ReorderMigrationsController::class, 'processReorder'])
        ->name('reorderMigrations.processReorder');

    Route::get('reorder-migrations/confirm', [ReorderMigrationsController::class, 'confirm'])
        ->name('reorderMigrations.confirm');

    Route::post('reorder-migrations/confirm', [ReorderMigrationsController::class, 'processConfirm'])
        ->name('reorderMigrations.processConfirm');
});
