<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\guest\HomeController as GuestHomeController;

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

Route::get('/', function () {
    return view('welcome');
});


Route:: prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/home', [ AdminDashboardController::class , 'home'])->name('home');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/posts/deleted', [AdminPostController::class, 'deletedIndex'] )->name('posts.deleted');
Route::post('/posts/deleted/{post}', [AdminPostController::class, 'restore'] )->name('posts.restore');
Route::delete('/posts/deleted/{post}', [AdminPostController::class, 'obliterate'] )->name('posts.obliterate');
Route::resource('/posts', AdminPostController::class);