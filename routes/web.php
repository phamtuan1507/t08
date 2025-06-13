<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminCartController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProductController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\DiscountCodeController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/products', [UserProductController::class, 'index'])->name('products.list');
Route::get('/products/{product}', [UserProductController::class, 'show'])->name('products.show');
Route::get('/cart/add/{product}', [UserProductController::class, 'addToCart'])->name('cart.add');
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/api/dashboard/revenue', [AdminController::class, 'getRevenueData'])->name('api.dashboard.revenue');
    Route::resource('products', ProductController::class)->names([
        'index' => 'admin.products.index',
        'create' => 'admin.products.create',
        'store' => 'admin.products.store',
        'show' => 'admin.products.show',
        'edit' => 'admin.products.edit',
        'update' => 'admin.products.update',
        'destroy' => 'admin.products.destroy',
    ]);
    // Route::resource('categories', CategoryController::class);
    Route::resource('categories', CategoryController::class)->names([
        'index' => 'admin.categories.index',
        'create' => 'admin.categories.create',
        'store' => 'admin.categories.store',
        'show' => 'admin.categories.show',
        'edit' => 'admin.categories.edit',
        'update' => 'admin.categories.update',
        'destroy' => 'admin.categories.destroy',
    ]);

    Route::resource('banners', BannerController::class)->names([
        'index' => 'admin.banners.index',
        'create' => 'admin.banners.create',
        'store' => 'admin.banners.store',
        'show' => 'admin.banners.show',
        'edit' => 'admin.banners.edit',
        'update' => 'admin.banners.update',
        'destroy' => 'admin.banners.destroy',
    ]);
    Route::get('/carts', [AdminCartController::class, 'index'])->name('admin.carts.index');
    Route::get('/carts/{cart}', [AdminCartController::class, 'show'])->name('admin.carts.show');
    Route::delete('/carts/{cart}', [AdminCartController::class, 'destroy'])->name('admin.carts.destroy');
    Route::delete('/carts/items/{cartItem}', [AdminCartController::class, 'destroyItem'])->name('admin.carts.items.destroy');
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::put('/orders/{order}', [AdminOrderController::class, 'update'])->name('admin.orders.update');
    Route::delete('/orders/{order}', [AdminOrderController::class, 'destroy'])->name('admin.orders.destroy');
    Route::get('/contact-detail', [AdminController::class, 'detail_contact'])->name('admin.contact.index');
    Route::post('/dashboard/{contact}/send-response', [AdminController::class, 'sendResponse'])->name('admin.send.response');
    Route::get('/reviews', [ReviewController::class, 'adminIndex'])->name('admin.reviews.index'); // Thêm mới
    Route::post('/reviews/{id}/approve', [ReviewController::class, 'approve'])->name('admin.reviews.approve'); // Thêm mới
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('admin.reviews.destroy'); // Thêm mới

    // Route::resource('discount-codes', DiscountCodeController::class);

    Route::resource('discount-codes', DiscountCodeController::class)->names([
        'index' => 'admin.discount-codes.index',
        'create' => 'admin.discount-codes.create',
        'store' => 'admin.discount-codes.store',
        'show' => 'admin.discount-codes.show',
        'edit' => 'admin.discount-codes.edit',
        'update' => 'admin.discount-codes.update',
        'destroy' => 'admin.discount-codes.destroy',
    ]);
    Route::get('/statistic', [AdminController::class, 'showChart'])->name('admin.statistic.index');

    Route::get('/menus', [MenuController::class, 'index'])->name('admin.menus.index');
    Route::get('/menus/create', [MenuController::class, 'create'])->name('admin.menus.create');
    Route::post('/menus', [MenuController::class, 'store'])->name('admin.menus.store');
    Route::get('/menus/{id}/edit', [MenuController::class, 'edit'])->name('admin.menus.edit');
    Route::put('/menus/{id}', [MenuController::class, 'update'])->name('admin.menus.update');
    Route::delete('/menus/{id}', [MenuController::class, 'destroy'])->name('admin.menus.destroy');


});

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard', function () {
        return view('user.dashboard');
    })->name('dashboard');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/update/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    // Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::post('/checkout/apply-discount', [CheckoutController::class, 'applyDiscount'])->name('checkout.apply.discount');
    Route::get('/profile', [UserController::class, 'profile'])->name('profile.index');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('user.profile.update');
    Route::get('/search', [UserProductController::class, 'search'])->name('products.search');
});

Route::get('/orders', [UserController::class, 'orders'])->name('user.orders.index')->middleware(['auth', 'role:user']);

// New static pages
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'storeContact'])->name('contact.store');
Route::get('/news', [PageController::class, 'news'])->name('news');
Route::post('/products/{product}/review', [ReviewController::class, 'store'])->name('products.review'); // Thêm mới


Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

