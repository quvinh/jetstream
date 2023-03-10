<?php

use App\Http\Livewire\Posts;
use App\Http\Livewire\PostShow;
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
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::group(['middleware' => ['auth:sanctum', 'role:admin']], function() {
    Route::get('/admin/posts', Posts::class)->name('posts.index');
});

Route::get('/posts/{slug}', PostShow::class)->name('posts.show');
