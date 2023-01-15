<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (!Auth::check()) {
        return view('auth.login');
    } else {
        return redirect('home');
    }
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/erm/{tabel}', [App\Http\Controllers\HomeController::class, 'index_new']);
Route::get('/query/{query}', [App\Http\Controllers\HomeController::class, 'query']);
Route::get('/notification', [App\Http\Controllers\HomeController::class, 'notification']);

Route::resource('book', App\Http\Controllers\BookController::class)->except(['show', 'create', 'edit']);
Route::get('/book/api', [App\Http\Controllers\BookController::class, 'api']);

Route::resource('publisher', App\Http\Controllers\PublisherController::class)->except(['show', 'create', 'edit']);
Route::get('/publisher/api', [App\Http\Controllers\PublisherController::class, 'api']);

Route::resource('author', App\Http\Controllers\AuthorController::class)->except(['show', 'create', 'edit']);
Route::get('/author/api', [App\Http\Controllers\AuthorController::class, 'api']);

Route::resource('catalog', App\Http\Controllers\CatalogController::class)->except('show');
Route::get('/catalog/api', [App\Http\Controllers\CatalogController::class, 'api']);

Route::resource('member', App\Http\Controllers\MemberController::class)->except(['show', 'create', 'edit']);
Route::get('/member/api', [App\Http\Controllers\MemberController::class, 'api']);

Route::resource('lend', App\Http\Controllers\LendController::class)->except('show');
Route::get('/lend/api', [App\Http\Controllers\LendController::class, 'api']);
Route::get('/lend/{lend}/edit/api', [App\Http\Controllers\LendController::class, 'editApi']);


