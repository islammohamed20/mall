<?php

use App\Http\Controllers\Admin\ContactMessageController as AdminContactMessageController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EmailSettingController as AdminEmailSettingController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\FacebookPostController as AdminFacebookPostController;
use App\Http\Controllers\Admin\FacilityController as AdminFacilityController;
use App\Http\Controllers\Admin\FloorController as AdminFloorController;
use App\Http\Controllers\Admin\OfferController as AdminOfferController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\PaymentMethodController as AdminPaymentMethodController;
use App\Http\Controllers\Admin\ShopCategoryController as AdminShopCategoryController;
use App\Http\Controllers\Admin\ShopController as AdminShopController;
use App\Http\Controllers\Admin\SliderController as AdminSliderController;
use App\Http\Controllers\Admin\ProductAttributeController as AdminProductAttributeController;
use App\Http\Controllers\Admin\SiteSettingController as AdminSiteSettingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\Admin\UnitController as AdminUnitController;
use App\Http\Controllers\VisitGeoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/lang/{locale}', function (string $locale) {
    $supported = config('app.supported_locales', ['ar', 'en']);
    if (in_array($locale, $supported, true)) {
        session(['locale' => $locale]);
    }

    return back();
})->name('lang.switch');

Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Visitor geolocation (browser-based, permission required)
Route::post('/_visit/geo', [VisitGeoController::class, 'store'])->name('visit.geo');

Route::get('/shops', [ShopController::class, 'index'])->name('shops.index');
Route::get('/shops/{shop:slug}', [ShopController::class, 'show'])->name('shops.show');
Route::get('/shops/{shop:slug}/products/{product:slug}', [ShopController::class, 'product'])->name('shops.products.show');

// Cart routes (session-based for guests)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product:slug}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{product:slug}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{product:slug}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');

// Checkout routes
Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');

// Favorites routes (session-based for guests)
Route::get('/favorites', [FavoritesController::class, 'index'])->name('favorites.index');
Route::post('/favorites/toggle/{product:slug}', [FavoritesController::class, 'toggle'])->name('favorites.toggle');
Route::delete('/favorites/remove/{product:slug}', [FavoritesController::class, 'remove'])->name('favorites.remove');
Route::delete('/favorites/clear', [FavoritesController::class, 'clear'])->name('favorites.clear');
Route::get('/favorites/count', [FavoritesController::class, 'count'])->name('favorites.count');
Route::get('/favorites/check/{product:slug}', [FavoritesController::class, 'check'])->name('favorites.check');

Route::get('/units', [UnitController::class, 'index'])->name('units.index');
Route::get('/units/{unit:slug}', [UnitController::class, 'show'])->name('units.show');

Route::get('/offers', [OfferController::class, 'index'])->name('offers.index');
Route::get('/offers/{offer:slug}', [OfferController::class, 'show'])->name('offers.show');

Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event:slug}', [EventController::class, 'show'])->name('events.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->middleware('throttle:login')->name('login.store');
    Route::get('/register', [AuthController::class, 'createRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'storeRegister'])->name('register.store');

    // OTP Verification
    Route::get('/verify-otp', [AuthController::class, 'showVerifyOtp'])->name('otp.verify');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('otp.verify.submit');
    Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('otp.resend');

    // Forgot Password
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetOtp'])->name('password.email');
    Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'destroy'])->middleware('auth')->name('logout');

Route::get('/account', [AccountController::class, 'show'])->middleware('auth')->name('account.show');

Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('shop-categories', AdminShopCategoryController::class)->except(['show']);
    Route::resource('product-attributes', AdminProductAttributeController::class)->except(['show']);
    Route::resource('shops', AdminShopController::class)->except(['show']);
    Route::resource('offers', AdminOfferController::class)->except(['show']);
    Route::resource('events', AdminEventController::class)->except(['show']);
    Route::resource('sliders', AdminSliderController::class)->except(['show']);
    Route::resource('floors', AdminFloorController::class)->except(['show']);
    Route::resource('facilities', AdminFacilityController::class)->except(['show']);
    Route::resource('payment-methods', AdminPaymentMethodController::class)->except(['show']);
    Route::resource('pages', AdminPageController::class)->except(['show']);
    Route::resource('units', AdminUnitController::class)->except(['show']);

    Route::prefix('shops/{shop}/products')->name('shops.products.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ShopProductController::class, 'index'])->name('index');
        Route::get('/export', [\App\Http\Controllers\Admin\ShopProductController::class, 'export'])->name('export');
        Route::get('/import', [\App\Http\Controllers\Admin\ShopProductController::class, 'showImportForm'])->name('import.form');
        Route::post('/import', [\App\Http\Controllers\Admin\ShopProductController::class, 'import'])->name('import');
        Route::get('/create', [\App\Http\Controllers\Admin\ShopProductController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\ShopProductController::class, 'store'])->name('store');
        Route::get('/{product}/edit', [\App\Http\Controllers\Admin\ShopProductController::class, 'edit'])->name('edit');
        Route::put('/{product}', [\App\Http\Controllers\Admin\ShopProductController::class, 'update'])->name('update');
        Route::delete('/{product}', [\App\Http\Controllers\Admin\ShopProductController::class, 'destroy'])->name('destroy');
    });

    Route::get('facebook-posts', [AdminFacebookPostController::class, 'index'])->name('facebook-posts.index');
    Route::patch('facebook-posts/{facebookPost}/approve', [AdminFacebookPostController::class, 'approve'])->name('facebook-posts.approve');
    Route::patch('facebook-posts/{facebookPost}/reject', [AdminFacebookPostController::class, 'reject'])->name('facebook-posts.reject');

    // Outgoing Facebook Posts (Publish to Facebook)
    Route::prefix('facebook-posts/outgoing')->name('facebook-posts.outgoing.')->group(function () {
        Route::get('/', [AdminFacebookPostController::class, 'outgoingIndex'])->name('index');
        Route::get('/create', [AdminFacebookPostController::class, 'outgoingCreate'])->name('create');
        Route::post('/', [AdminFacebookPostController::class, 'outgoingStore'])->name('store');
        Route::get('/{post}', [AdminFacebookPostController::class, 'outgoingShow'])->name('show');
        Route::get('/{post}/edit', [AdminFacebookPostController::class, 'outgoingEdit'])->name('edit');
        Route::put('/{post}', [AdminFacebookPostController::class, 'outgoingUpdate'])->name('update');
        Route::delete('/{post}', [AdminFacebookPostController::class, 'outgoingDestroy'])->name('destroy');
        Route::post('/{post}/publish', [AdminFacebookPostController::class, 'outgoingPublish'])->name('publish');
        Route::post('/{post}/retry', [AdminFacebookPostController::class, 'outgoingRetry'])->name('retry');
        Route::delete('/{post}/facebook', [AdminFacebookPostController::class, 'deleteFromFacebook'])->name('delete-from-facebook');
    });
    Route::get('facebook/verify/{shop}', [AdminFacebookPostController::class, 'verifyConnection'])->name('facebook.verify');

    Route::get('settings', [AdminSiteSettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [AdminSiteSettingController::class, 'update'])->name('settings.update');

    Route::get('email', [AdminEmailSettingController::class, 'edit'])->name('email.edit');
    Route::put('email', [AdminEmailSettingController::class, 'update'])->name('email.update');
    Route::post('email/test', [AdminEmailSettingController::class, 'sendTest'])->name('email.test');

    Route::get('messages', [AdminContactMessageController::class, 'index'])->name('messages.index');
    Route::get('messages/{contactMessage}', [AdminContactMessageController::class, 'show'])->name('messages.show');
    Route::patch('messages/{contactMessage}/status', [AdminContactMessageController::class, 'updateStatus'])->name('messages.status');

    // Reports Routes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [AdminReportController::class, 'dashboard'])->name('dashboard');
        Route::get('/sales', [AdminReportController::class, 'sales'])->name('sales');
        Route::get('/orders', [AdminReportController::class, 'orders'])->name('orders');
        Route::get('/shops', [AdminReportController::class, 'shops'])->name('shops');
        Route::get('/products', [AdminReportController::class, 'products'])->name('products');
        Route::get('/customers', [AdminReportController::class, 'customers'])->name('customers');
        Route::get('/offers-events', [AdminReportController::class, 'offersEvents'])->name('offers-events');
        Route::get('/messages', [AdminReportController::class, 'messages'])->name('messages');
        Route::get('/visits', [AdminReportController::class, 'visits'])->name('visits');
    });

    // Orders Management Routes
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
});

// Direct Shop URL (must be at the end to avoid conflicts)
// Example: 192.168.1.26/style-store
Route::get('/{shop:slug}', [ShopController::class, 'show'])->name('shop.direct')
    ->where('shop', '^(?!admin|login|logout|register|password|account|shops|cart|checkout|orders|favorites|offers|events|facilities|about|contact|lang|search|api|units|verify-otp|resend-otp|forgot-password|reset-password).*$');
