<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransaksiController;
use App\Livewire\ProfileForm;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(
    function () {
        Route::get('register', [RegisteredUserController::class, 'create'])
            ->name('register');

        Route::post('register', [RegisteredUserController::class, 'store']);

        Route::get('login', [AuthenticatedSessionController::class, 'create'])
            ->name('login');

        Route::post('login', [AuthenticatedSessionController::class, 'store']);

        Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
            ->name('password.request');

        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
            ->name('password.email');

        Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
            ->name('password.reset');

        Route::post('reset-password', [NewPasswordController::class, 'store'])
            ->name('password.store');
    }
);
Route::middleware('auth')->group(function () {
   
});
Route::middleware(['auth'])->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');
  
    // Route::get('cart', [KeranjangController::class, 'create'])->name('cart');
    // Route::post('cart-check', [KeranjangController::class, 'check'])->name('cart.check');

    Route::delete('cart-varian/{id}', [KeranjangController::class, 'delete'])->name('cart-delete');

    Route::get('/orders', [TransaksiController::class, 'transaksi'])->name('transaksi.render');
    Route::get('/orders/{id}', [TransaksiController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/confirm', [TransaksiController::class, 'confirm'])->name('orders.confirm');


    Route::delete('/images/{image}', [ImageController::class, 'destroy'])
        ->name('images.destroy');
    Route::get('/cetak/pendapatan/{ids}', [PDFController::class, 'cetakpendapatan'])->name('pendapatan.cetak.pdf');
    Route::get('/cetak/transaksi/{id}', [PDFController::class, 'cetaktransaksi'])->name('transaksi.cetak.pdf');

    Route::get('profile', [ProfileController::class, 'create'])->name('profile');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');


    Route::put('transaksi/selesai/{id}', [TransaksiController::class, 'selesai'])->name('transaksi.selesai');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
