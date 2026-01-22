<?php

use App\Http\Controllers\IndexController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\KaosController;
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

Route::get('/', [IndexController::class, 'gets'])->name('index');
Route::get('/kaos/{id}', [KaosController::class, 'detail'])->name('kaos-detail');
Route::post('/', [IndexController::class, 'store']);
Route::get('/search', [KaosController::class , 'search'])->name('search');
Route::get('/variants/by-warna/{variant}', function (\App\Models\KaosVariant $variant) {
	return \App\Models\KaosVariant::with('ukuran')
		->where('warna_id', $variant->warna_id)->where('kaos_id', $variant->kaos_id)
		->get()
		->map(fn($v) => [
			'id' => $v->id,
			'ukuran' => $v->ukuran->ukuran,
		'kaos_id'  => $v->kaos_id,
	]);
});
Route::delete('/images/{image}', [ImageController::class, 'destroy'])
	->name('images.destroy');

require __DIR__ . '/auth.php';


Route::fallback(function () {
	return response()->view('errors.404', [], 404);
});
