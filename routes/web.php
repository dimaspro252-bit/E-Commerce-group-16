<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Customer Controllers
use App\Http\Controllers\Customer\ProductController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\TransactionController;
use App\Http\Controllers\Customer\ReviewController;
use App\Http\Controllers\Customer\CartController;

// Seller Controllers
use App\Http\Controllers\Seller\DashboardController as SellerDashboardController;
use App\Http\Controllers\Seller\StoreController;
use App\Http\Controllers\Seller\ProductController as SellerProductController;
use App\Http\Controllers\Seller\ProductImageController;
use App\Http\Controllers\Seller\CategoryController as SellerCategoryController;
use App\Http\Controllers\Seller\OrderController;
use App\Http\Controllers\Seller\BalanceController;
use App\Http\Controllers\Seller\WithdrawalController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\StoreController as AdminStoreController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\WithdrawalController as AdminWithdrawalController;

// ========================================
// PUBLIC ROUTES (Guest & Customer)
// ========================================

// Homepage - Product Listing
Route::get('/', [ProductController::class, 'index'])->name('home');

// Product Detail
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

// Category Filter (optional, bisa pakai query parameter di homepage)
// Route::get('/category/{category:slug}', [CategoryController::class, 'show'])->name('category.show');

// ========================================
// CART ROUTES (Before Checkout)
// ========================================
Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{product}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
});

// ========================================
// CUSTOMER ROUTES (Authenticated Users)
// ========================================
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['verified'])->name('dashboard');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // Transaction History
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::post('/transactions/{transaction}/pay', [TransactionController::class, 'pay'])->name('transactions.pay');

    // Product Reviews
    Route::get('/products/{product}/review', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/products/{product}/review', [ReviewController::class, 'store'])->name('reviews.store');
});

// ========================================
// SELLER ROUTES
// ========================================
Route::middleware(['auth'])->prefix('seller')->name('seller.')->group(function () {

    // Store Registration (accessible even if not verified yet)
    Route::get('/register', [StoreController::class, 'create'])->name('register');
    Route::post('/register', [StoreController::class, 'store'])->name('register.store');

    // SELLER DASHBOARD & FEATURES (Only for verified stores)
    Route::middleware(['verified.store'])->group(function () {

        // Dashboard
        Route::get('/dashboard', [SellerDashboardController::class, 'index'])->name('dashboard');

        // Store Management
        Route::get('/store', [StoreController::class, 'edit'])->name('store.edit');
        Route::patch('/store', [StoreController::class, 'update'])->name('store.update');
        Route::delete('/store', [StoreController::class, 'destroy'])->name('store.destroy');

        // Product Management (Resource Routes)
        Route::resource('products', SellerProductController::class);

        // Product Images
        Route::post('/products/{product}/images', [ProductImageController::class, 'store'])->name('products.images.store');
        Route::delete('/products/images/{image}', [ProductImageController::class, 'destroy'])->name('products.images.destroy');
        Route::patch('/products/images/{image}/thumbnail', [ProductImageController::class, 'setThumbnail'])->name('products.images.thumbnail');

        // Category Management (Resource Routes)
        Route::resource('categories', SellerCategoryController::class);

        // Order Management
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{transaction}', [OrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{transaction}', [OrderController::class, 'update'])->name('orders.update');

        // Balance
        Route::get('/balance', [BalanceController::class, 'index'])->name('balance.index');

        // Withdrawal
        Route::get('/withdrawal', [WithdrawalController::class, 'index'])->name('withdrawal.index');
        Route::post('/withdrawal', [WithdrawalController::class, 'store'])->name('withdrawal.store');
        Route::patch('/withdrawal/bank', [WithdrawalController::class, 'updateBank'])->name('withdrawal.bank.update');
    });
});

// ========================================
// ADMIN ROUTES
// ========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Admin Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Store Management & Verification
    Route::get('/stores', [AdminStoreController::class, 'index'])->name('stores.index');
    Route::get('/stores/{store}', [AdminStoreController::class, 'show'])->name('stores.show');
    Route::patch('/stores/{store}/verify', [AdminStoreController::class, 'verify'])->name('stores.verify');
    Route::patch('/stores/{store}/reject', [AdminStoreController::class, 'reject'])->name('stores.reject');
    Route::delete('/stores/{store}', [AdminStoreController::class, 'destroy'])->name('stores.destroy');

    // User Management
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::patch('/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.role.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{user}/ban', [AdminUserController::class, 'ban'])->name('users.ban');

    // Withdrawal Management
    Route::get('/withdrawals', [AdminWithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::get('/withdrawals/{withdrawal}', [AdminWithdrawalController::class, 'show'])->name('withdrawals.show');
    Route::patch('/withdrawals/{withdrawal}/approve', [AdminWithdrawalController::class, 'approve'])->name('withdrawals.approve');
    Route::patch('/withdrawals/{withdrawal}/reject', [AdminWithdrawalController::class, 'reject'])->name('withdrawals.reject');
});

// ========================================
// AUTH ROUTES (dari Breeze)
// ========================================
require __DIR__ . '/auth.php';