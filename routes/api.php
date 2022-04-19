<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\GalleryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(UserController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
    Route::post('/logout', 'logout')->middleware('auth');
    Route::get('/authors/{id}', 'getMyGalleries')->middleware('auth');
    Route::post('/refresh', 'refreshToken');
});

Route::controller(GalleryController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/my-galleries', 'getMyGalleries');
    Route::post('/create', 'store')->middleware('auth');
    Route::get('/galleries/{id}', 'show');
    //ovo vrvt nece valjati:
    Route::put('/galleries/{id}', 'update');
    Route::delete('/galleries/{id}', 'delete');
});

Route::controller(CommentController::class)->group(function () {
    Route::post('/galleries/{gallery}/comments', 'store');
    Route::post('/galleries/{gallery}/comments/{id}', 'delete');
});
