<?php

use App\Http\Controllers\AdminController;
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

Route::redirect('/admin', '/admin/login');
Route::get('/admin/login', [AdminController::class, 'index'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.post');

Route::middleware('auth')->prefix('admin')->group(function () {
    // Dashboard routes
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('dashboard/{month}', [AdminController::class, 'dashboard'])->name('admin.dashboard.month');
    
    // Blog routes
    Route::get('blogs', [AdminController::class, 'blogs'])->name('admin.blogs');
    Route::get('blog/{id}', [AdminController::class, 'showBlog'])->name('admin.blog.show');

    // user routes
    Route::get('users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('user/{id}', [AdminController::class, 'showUser'])->name('admin.user.show');

    Route::get('logout', [AdminController::class, 'logout'])->name('admin.logout');
});
