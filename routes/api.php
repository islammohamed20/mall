<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ShopController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\CashBoxController;
use App\Http\Controllers\Api\MallShopController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\ActivityLogController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\SalaryAdvanceController;

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
    
    // Shops (public mall directory)
    Route::get('/shops', [ShopController::class, 'index'])->name('api.shops.index');
    Route::get('/shops/{id}', [ShopController::class, 'show'])->name('api.shops.show');
    Route::get('/shops/categories', [ShopController::class, 'categories'])->name('api.shops.categories');
    Route::get('/shops/floors', [ShopController::class, 'floors'])->name('api.shops.floors');
    
    // Galleries (public)
    Route::get('/galleries', [GalleryController::class, 'index'])->name('api.galleries.index');
    Route::get('/galleries/{id}', [GalleryController::class, 'show'])->name('api.galleries.show');
    
    // Pages (public)
    Route::get('/pages', [PageController::class, 'index'])->name('api.pages.index');
    Route::get('/pages/slug/{slug}', [PageController::class, 'showBySlug'])->name('api.pages.by-slug');
    Route::get('/pages/{id}', [PageController::class, 'show'])->name('api.pages.show');
    
    // Mall Shops (mall manager - rental/tenant management)
    Route::get('/mall-shops', [MallShopController::class, 'index'])->name('api.mall-shops.index');
    Route::get('/mall-shops/{mallShop}', [MallShopController::class, 'show'])->name('api.mall-shops.show');
    
    // Protected routes (authentication required)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/shops', [ShopController::class, 'store'])->name('api.shops.store');
        Route::put('/shops/{id}', [ShopController::class, 'update'])->name('api.shops.update');
        Route::delete('/shops/{id}', [ShopController::class, 'destroy'])->name('api.shops.destroy');

        // Mall Shops CRUD
        Route::post('/mall-shops', [MallShopController::class, 'store'])->name('api.mall-shops.store');
        Route::put('/mall-shops/{mallShop}', [MallShopController::class, 'update'])->name('api.mall-shops.update');
        Route::delete('/mall-shops/{mallShop}', [MallShopController::class, 'destroy'])->name('api.mall-shops.destroy');

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

        // CashBox
        Route::apiResource('cashbox', CashBoxController::class)->names([
            'index' => 'api.box.index',
            'store' => 'api.box.store',
            'show' => 'api.box.show',
            'update' => 'api.box.update',
            'destroy' => 'api.box.destroy',
        ]);

        // Attendance
        Route::apiResource('attendance', AttendanceController::class);

        // Activity Logs
        Route::get('activity-logs', [ActivityLogController::class, 'index']);

        // Expenses
        Route::apiResource('expenses', ExpenseController::class);
        
        // Collections
        Route::apiResource('collections', CollectionController::class);
        
        // Salary Advances
        Route::apiResource('salary-advances', SalaryAdvanceController::class);

        // Galleries (admin)
        Route::post('/galleries', [GalleryController::class, 'store'])->name('api.galleries.store');
        Route::put('/galleries/{id}', [GalleryController::class, 'update'])->name('api.galleries.update');
        Route::delete('/galleries/{id}', [GalleryController::class, 'destroy'])->name('api.galleries.destroy');
        Route::post('/galleries/{id}/items', [GalleryController::class, 'addItems'])->name('api.galleries.items.add');
        Route::put('/galleries/{galleryId}/items/{itemId}', [GalleryController::class, 'updateItem'])->name('api.galleries.items.update');
        Route::delete('/galleries/{galleryId}/items/{itemId}', [GalleryController::class, 'removeItem'])->name('api.galleries.items.remove');

        // Pages (admin)
        Route::post('/pages', [PageController::class, 'store'])->name('api.pages.store');
        Route::put('/pages/{id}', [PageController::class, 'update'])->name('api.pages.update');
        Route::delete('/pages/{id}', [PageController::class, 'destroy'])->name('api.pages.destroy');
    });
});
