<?php

use App\Http\Controllers\Account\DashboardController as AccountDashboardController;
use App\Http\Controllers\Account\OrderController as AccountOrderController;
use App\Http\Controllers\Account\ProfileController as AccountProfileController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CartController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DeliveryMethodController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SearchController;
use App\Livewire\CartPage;
use App\Livewire\Catalog;
use App\Livewire\CategoryProducts;
use App\Livewire\Checkout;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home']);
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contacts', [PageController::class, 'contacts'])->name('contacts');
Route::get('/product/{slug}', [PageController::class, 'product'])->name('product.show');
Route::get('/search', SearchController::class)->middleware('throttle:60,1')->name('search');

Route::livewire('/catalog', Catalog::class)->name('catalog');
Route::livewire('/category/{slug}', CategoryProducts::class);
Route::livewire('/cart', CartPage::class);
Route::livewire('/checkout', Checkout::class);
Route::get('/order/{orderNumber}', [PageController::class, 'orderSuccess'])->name('order.success');

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register'])->middleware('throttle:5,1');
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->middleware('throttle:5,1');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('email/verify', [VerificationController::class, 'notice'])->name('verification.notice');
    Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
        ->middleware('signed')
        ->name('verification.verify');
    Route::post('email/verification-notification', [VerificationController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::prefix('account')->name('account.')->group(function () {
        Route::get('/', [AccountDashboardController::class, 'index'])->name('dashboard');
        Route::get('orders', [AccountOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [AccountOrderController::class, 'show'])->name('orders.show');
        Route::get('profile', [AccountProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [AccountProfileController::class, 'update'])->name('profile.update');
    });
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->middleware('throttle:5,1');
    });

    Route::middleware(['admin.guard', 'auth:admin', 'admin'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::resource('users', UserController::class)->except(['show']);
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('products', ProductController::class)->except(['show']);
        Route::resource('delivery-methods', DeliveryMethodController::class)->except(['show']);
        Route::resource('banners', BannerController::class)->except(['show']);
        Route::patch('banners/{banner}/move-up', [BannerController::class, 'moveUp'])->name('banners.move-up');
        Route::patch('banners/{banner}/move-down', [BannerController::class, 'moveDown'])->name('banners.move-down');
        Route::get('settings', [SettingsController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::resource('orders', OrderController::class)->only(['index', 'show', 'update']);
        Route::get('carts', [CartController::class, 'index'])->name('carts.index');
        Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::get('export/products', [ExportController::class, 'products'])->name('export.products');
        Route::get('export/orders', [ExportController::class, 'orders'])->name('export.orders');
        Route::get('export/users', [ExportController::class, 'users'])->name('export.users');
    });
});
