<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['as' => 'guest.'], function () {
    Route::get('/', \App\Livewire\Guest\Index::class)->name('index');
    Route::get('/media', \App\Livewire\Guest\Media\Index::class)->name('media.index');
    Route::get('/blog', \App\Livewire\Guest\Blog\Index::class)->name('blog.index');
    Route::get('/blog/{slug}', \App\Livewire\Guest\Blog\View::class)->name('blog.view');
    Route::get('/denah-area-pondok', \App\Livewire\Guest\DenahAreaPondok\Index::class)->name('denah-area-pondok.index');
    Route::get('/perguruan-tinggi-terdekat', \App\Livewire\Guest\PerguruanTinggiTerdekat\Index::class)->name('perguruan-tinggi-terdekat.index');
    Route::get('/kontak-kami', \App\Livewire\Guest\Kontak\Index::class)->name('kontak.index');

});

Route::group(['middleware' => 'guest'], function(){
    Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');
});
