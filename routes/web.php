<?php

use App\Http\Controllers\IndexController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\KaosController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['web'])->group(function () {
	Route::get('/', [IndexController::class, 'gets'])->name('index');
	Route::get('/kaos/{id}', [KaosController::class, 'detail'])->name('kaos-detail');
	Route::post('/', [IndexController::class, 'store']);
	Route::get('/search', [KaosController::class, 'search'])->name('search');
	Route::get('/variants/by-warna/{variant}', function (\App\Models\KaosVariant $variant) {
		return \App\Models\KaosVariant::with('ukuran')
			->where('warna_id', $variant->warna_id)->where('kaos_id', $variant->kaos_id)
			->get()
			->map(fn($v) => [
				'id' => $v->id,
				'ukuran' => $v->ukuran->ukuran,
				'harga_jual' => $v->harga_jual,
				'kaos_id'  => $v->kaos_id,
				'image_path' => $v->image_path
			]);
	});
	Route::get('/otp/verify', [OtpController::class, 'show'])->name('otp.verify');
	Route::post('/otp/verify', [OtpController::class, 'verify'])->name('otp.verify.post');
	Route::post('/otp/resend', [OtpController::class, 'resend'])->name('otp.resend');
});

Route::middleware(['web', 'auth'])->group(function () {
	Route::get('cart', [KeranjangController::class, 'create'])->name('cart');
	Route::post('cart-check', [KeranjangController::class, 'check'])->name('cart.check');
	Route::post('beli-langsung-check/{id_varian}', [KeranjangController::class, 'belilangsung'])->name('beli.langsung.check');
	Route::put('tolak-transaksi/{id}', [TransaksiController::class, 'tolak'])->name('transaksi.tolak');
	Route::get('checkout', [TransaksiController::class, 'create'])->name('checkout');
	Route::post('pesan', [TransaksiController::class, 'pesan'])->name('payment.confirm');
	Route::post('bayar/{id}', [TransaksiController::class, 'bayar'])->name('payment.bayar');
	Route::put('transaksi-selesai/{id}', [TransaksiController::class, 'selesai'])->name('payment.selesai');
});

Route::delete('/images/{image}', [ImageController::class, 'destroy'])
	->name('images.destroy');

require __DIR__ . '/auth.php';


Route::fallback(function () {
	return response()->view('errors.404', [], 404);
});
