<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\UserController;
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

Route::group(['namespace' => 'App\Http\Controllers\Api'], function () {

    Route::post('/register', [AuthController::class, 'register'])->name('api.register');

    Route::post('/login', [AuthController::class, 'login'])->name('api.login');

    Route::group(['middleware' => ['auth:api']], function() {

        Route::get('/user', [UserController::class, 'get'])->name('api.user');

        Route::post('/blog/create', [BlogController::class, 'create'])->name('api.blog.create');

    });
});
