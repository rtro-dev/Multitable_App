<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SettingController;

Auth::routes(['verify' => true]);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');
    Route::post('/profile/email', [ProfileController::class, 'changeEmail'])->name('profile.email');
    Route::post('/profile/username', [ProfileController::class, 'changeUsername'])->name('profile.username');
});

Route::get('/', [SaleController::class, 'index'])->name('sales.index');
Route::resource('categories', CategoryController::class);
Route::resource('images', ImageController::class);
Route::resource('sales', SaleController::class);
Route::get('sales/user/{user}', [SaleController::class, 'showUserSales'])->name('sales.user');
Route::put('sales/shop/{sale}', [SaleController::class, 'shop'])->name('sales.shop');
Route::resource('settings', SettingController::class);
