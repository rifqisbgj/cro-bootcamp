<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\CheckoutController as UserCheckout;
use App\Http\Controllers\UserController;
use App\Http\Controllers\User\DashboardController as UserDashboard;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\CheckoutController as AdminCheckout;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Socialite Google
Route::get('sign-in-google', [UserController::class, 'google'])->name('user.login.google');
Route::get('auth/google/callback', [UserController::class, 'handleProviderCallback'])->name('user.google.callback');

Route::get('payment/success', [UserCheckout::class, 'midtransCallback']);
Route::post('payment/success', [UserCheckout::class, 'midtransCallback']);

// Auth
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Checkout Start
    Route::get('checkout/success', [UserCheckout::class, 'success'])->name('checkout.success')->middleware('ensureUserRole:user');
    Route::get('checkout/{camp:slug}', [UserCheckout::class, 'create'])->name('checkout.create')->middleware('ensureUserRole:user');
    Route::post('checkout/{camp}', [UserCheckout::class, 'store'])->name('checkout.store')->middleware('ensureUserRole:user');
    // End Checkout

    // global dashboard
    Route::get('dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    // user dashboard
    Route::prefix('user/dashboard')->namespace('User')->name('user.')->middleware('ensureUserRole:user')->group(function () {
        Route::get('/', [UserDashboard::class, 'index'])->name('dashboard');
        Route::get('/checkout/invoice/{checkout}', [UserCheckout::class, 'invoice'])->name('checkout.invoice');
    });

    // admin dashboard
    Route::prefix('admin/dashboard')->namespace('Admin')->name('admin.')->middleware('ensureUserRole:admin')->group(function () {
        Route::get('/', [AdminDashboard::class, 'index'])->name('dashboard');

        // admin set paid
        Route::get('checkout/{checkout}', [AdminCheckout::class, 'update'])->name('checkout.update');
    });
});

require __DIR__ . '/auth.php';
