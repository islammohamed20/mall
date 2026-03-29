<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ShopController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\EmployeeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "api" middleware group. Now create something great!
|
*/

// Public API routes (no authentication required)
Route::prefix('v1')->group(function () {
    // Authentication
    Route::post('/login', [AuthController::class, 'login'])->name('api.login');
    Route::post('/register', [AuthController::class, 'register'])->name('api.register');
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');
    
    // Shops
    Route::get('/shops', [ShopController::class, 'index'])->name('api.shops.index');
    Route::get('/shops/{id}', [ShopController::class, 'show'])->name('api.shops.show');
    Route::get('/shops/categories', [ShopController::class, 'categories'])->name('api.shops.categories');
    Route::get('/shops/floors', [ShopController::class, 'floors'])->name('api.shops.floors');
    
    // Protected routes (authentication required)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/shops', [ShopController::class, 'store'])->name('api.shops.store');
        Route::put('/shops/{id}', [ShopController::class, 'update'])->name('api.shops.update');
        Route::delete('/shops/{id}', [ShopController::class, 'destroy'])->name('api.shops.destroy');

        // Users
        Route::get('/users', [UserController::class, 'index'])->name('api.users.index');
        Route::post('/users', [UserController::class, 'store'])->name('api.users.store');
        Route::get('/users/{id}', [UserController::class, 'show'])->name('api.users.show');
        Route::put('/users/{id}', [UserController::class, 'update'])->name('api.users.update');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('api.users.destroy');

        // Employees
        Route::apiResource('employees', EmployeeController::class)->names([
            'index' => 'api.employees.index',
            'store' => 'api.employees.store',
            'show' => 'api.employees.show',
            'update' => 'api.employees.update',
            'destroy' => 'api.employees.destroy',
        ]);
    });
});
