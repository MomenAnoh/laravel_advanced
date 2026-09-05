<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderAndProduct\ProductController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('chat');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('deploy',function () {
   return 'ok';
});

Route::get('test-pay',[ProductController::class,'payWithCard']);

Route::get('/login-page', function () {
    return view('auth');
})->name('login.page');

Route::get('/chat', function () {
    return view('chat');
})->name('chat');

require __DIR__.'/auth.php';
