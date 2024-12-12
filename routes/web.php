<?php

use App\Http\Controllers\DashboardCustomerController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeAdminController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransaksiController; 
use App\Http\Controllers\TransaksiCustomerController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.home');
        } elseif (Auth::user()->role === 'customer') {
            return redirect()->route('customer.dashboard');
        }
    }

    return app(ProductController::class)->showWelcomePage();
})->name('home');

// Dashboard route - hanya untuk customer
Route::get('/customer/dashboard', function () {
    if (Auth::check() && Auth::user()->role === 'customer') {
        return view('customer.dashboard');
    }
    return redirect('/login');
})->middleware(['auth', 'checkuser:customer'])->name('customer.dashboard');

// Admin routes - hanya untuk admin
Route::middleware(['auth', 'checkuser:admin'])->group(function () {
    Route::get('/admin/home', [HomeAdminController::class, 'index'])->name('admin.home');
    Route::get('/admin/product', [ProductController::class, 'index'])->name('admin.product');
    Route::get('/admin/create_product', [ProductController::class, 'create'])->name('admin.create_product');
    Route::post('/admin/product', [ProductController::class, 'store'])->name('product.store');
    Route::get('/admin/products/{id}/edit', [ProductController::class, 'edit'])->name('admin.edit_product');
    Route::post('/admin/products/{id}', [ProductController::class, 'update'])->name('admin.update_product');
    Route::delete('/admin/products/{id}', [ProductController::class, 'destroy'])->name('admin.delete_product');
    Route::get('/admin/transaksi', [TransaksiController::class, 'index'])->name('admin.transaksi');
    Route::patch('/admin/transaksi/{id}/update-status', [TransaksiController::class, 'updateStatus'])->name('admin.update_status');
    Route::get('/admin/transaksi/detail/{id}', [TransaksiController::class, 'getOrderDetail'])->name('admin.transaksi.detail');
});

Route::middleware(['auth', 'checkuser:customer'])->group(function () {
    Route::get('/customer/dashboard', [DashboardCustomerController::class, 'index'])
        ->name('customer.dashboard');

    Route::get('/customer/keranjang', [KeranjangController::class, 'index'])
        ->name('customer.keranjang');
    Route::post('/cart/add/{id}', [KeranjangController::class, 'add'])
        ->name('cart.add');
    Route::get('/cart', [KeranjangController::class, 'index'])
        ->name('cart.index');
    Route::put('/customer/keranjang/{key}', [KeranjangController::class, 'update'])
        ->name('customer.update');
    Route::delete('/customer/keranjang/{key}', [KeranjangController::class, 'destroy'])
        ->name('customer.destroy');
    Route::post('/customer/buy', [KeranjangController::class, 'buy'])->name('customer.buy');
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('customer.transaksi');
    Route::get('/customer/transaksi', [TransaksiCustomerController::class, 'index'])
        ->name('customer.transaksi');
    Route::get('/customer/transaksi/{id}', [TransaksiCustomerController::class, 'show'])
        ->name('customer.transaksi.detail');
    Route::get('/customer/transaksi', [TransaksiCustomerController::class, 'index'])
        ->name('customer.transaksi');
    Route::get('/customer/transaksi', [TransaksiCustomerController::class, 'index'])
        ->name('customer.transaksi');
    Route::get('/customer/transaksi/{id}', [TransaksiCustomerController::class, 'show'])
        ->name('customer.transaksi.detail');
    Route::get('/customer/transaksi/detail/{id}', [TransaksiCustomerController::class, 'getOrderDetail'])->name('customer.transaksi.detail');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
