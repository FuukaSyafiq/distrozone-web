<?php

use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::prefix('/rating')->group(function () {
	// Route::get('/', [RatingController::class, 'all']);
	Route::post('/room/{roomid}', [ReviewController::class, 'store']);	

});