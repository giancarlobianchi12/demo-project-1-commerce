<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MercadolibreController;
use Illuminate\Support\Facades\Route;

Route::prefix('/web/v1')->group(function () use ($router) {
    // Mercadolibre webhook
    $router->post('/mercadolibre', [MercadolibreController::class, 'hookHandler'])->name('mercadolibre.hook');

    // All users
    $router->post('/auth/login', [AuthController::class, 'login'])->name('auth.login');
    $router->post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
    $router->get('/auth/me', [AuthController::class, 'me'])->name('auth.me');

    // No admin users
    Route::middleware(['auth:sanctum', 'userType:client'])->group(function () use ($router) {
        Route::prefix('mercadolibre')->group(function () use ($router) {
            $router->post('/auth', [MercadolibreController::class, 'auth'])->name('mercadolibre.auth');
        });
    });

    // Admin users
    $router->group(['prefix' => 'admin', 'as' => 'admin.'], function () use ($router) {
        $router->group(['middleware' => ['auth:sanctum', 'userType:admin']], function () use ($router) {
            $router->apiResource('/users', UserController::class);
        });
    });
});
