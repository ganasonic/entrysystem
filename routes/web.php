<?php

use App\Http\Controllers\Auth0Controller;
use Illuminate\Support\Facades\Route;

Route::get('/signin', [Auth0Controller::class, 'showLoginPrompt'])->name('login');
Route::get('/signout', [Auth0Controller::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [Auth0Controller::class, 'showDashboard'])->name('dashboard');
    Route::get('/private', [Auth0Controller::class, 'private']);
    Route::get('/scope', [Auth0Controller::class, 'scope'])->can('read:messages');
    Route::get('/colors', [Auth0Controller::class, 'colors']);
});

Route::get('/', [Auth0Controller::class, 'showDashboard'])->name('home');
