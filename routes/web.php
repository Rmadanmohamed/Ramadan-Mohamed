<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index']);
Route::resource('posts', PostController::class);
Auth::routes(['reset' => false]);
Route::get('/home', [HomeController::class, 'index'])->name('home');
