<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

//Route::get('/', function () {
//    return Inertia::render('Welcome', [
//        'canLogin' => Route::has('login'),
//        'canRegister' => Route::has('register'),
//        'laravelVersion' => Application::VERSION,
//        'phpVersion' => PHP_VERSION,
//    ]);
//});

//Route::get('/dashboard', function () {
//    return Inertia::render('Dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');
//Route::middleware(['auth', 'role:admin'])

Route::middleware(['auth'])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Order
        Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('admin.orders.edit');
        Route::resource('orders', OrderController::class);

        // Product
        Route::resource('products', ProductController::class);

        // Customers
        Route::get('customers/search', [CustomerController::class, 'search'])->name('customers.search');
        Route::resource('customers', CustomerController::class);

        // Category and Sub category
        Route::resource('categories', CategoryController::class);
        Route::get('categories/{category}/subcategories', [SubcategoryController::class, 'index'])->name('subcategories.index');
        Route::post('categories/{category}/subcategories', [SubcategoryController::class, 'store'])->name('subcategories.store');
        Route::delete('categories/{category}/subcategories/{subcategory}', [SubcategoryController::class, 'destroy'])->name('subcategories.destroy');
        Route::post('subcategories/check-slug', [SubcategoryController::class, 'checkSlug'])->name('subcategories.checkSlug');
    });

Route::middleware('auth')
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

require __DIR__ . '/auth.php';

