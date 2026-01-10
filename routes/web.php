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

//pdf
// Route::post('/generate-bulk-pdf', [BulkPDFController::class, 'generate'])->name('generate.bulk.pdf');
// Route::get('download/transaction/{id}', [PDFController::class, 'transactionpdf'])->name('transaction.pdf');
/*Tagihan PDF */

/*Transaction history PDF */
// Route::get('download/buktibayar/{id}', [PDFController::class, 'cetakbuktibayar'])->name('buktibayar.settled.pdf');
Route::get('download/transaksi/{id}', [PDFController::class, 'cetak'])->name('transaction.settled.pdf');

Route::get('/', [IndexController::class, 'gets'])->name('index');
Route::get('/kaos/{id}', [KaosController::class, 'detail'])->name('kaos-detail');
Route::post('/', [IndexController::class, 'store']);
Route::get('/search', [KaosController::class , 'search'])->name('search');

Route::delete('/images/{image}', [ImageController::class, 'destroy'])
	->name('images.destroy');


// Route::middleware('auth')->group(function () {
//     // fitur routing

//     Route::get('/editprofile', [EditUserProfile::class, 'render']);
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__ . '/auth.php';
require __DIR__ . '/rating.php';


Route::fallback(function () {
	return response()->view('errors.404', [], 404);
});
