<?php

use App\Http\Controllers\VideosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/hello', function () {
    return response()->json(array(
        'name' => 'Welcome',
        'username' => 'welcome123'
    ));
});

Route::controller(VideosController::class)->group(function () {
    Route::get('/videos', 'getVideos');
    Route::get('/video/{id}', 'getVideoByID');
    Route::post('/video', 'postVideo');
    Route::post('/video/{id}', 'updateVideo');
    Route::delete('/video/{id}', 'deleteVideo');
});

// Route::get('/videos', [VideosController::class, 'getVideos']);
// Route::get('/video/{id}', [VideosController::class, 'getVideoByID']);
// Route::post('/video', [VideosController::class, 'postVideo']);
