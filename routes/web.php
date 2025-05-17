<?php

use App\Http\Controllers\Auth0Controller;
use App\Http\Controllers\EntryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use Auth0\Laravel\Facade\Auth0;

Route::get('/signin', [Auth0Controller::class, 'showLoginPrompt'])->name('login');
Route::get('/signout', [Auth0Controller::class, 'logout'])->name('logout');
Route::get('/signup', [Auth0Controller::class, 'signup'])->name('signup');
Route::get('/password/change', [Auth0Controller::class, 'passwordchange'])->name('passwordchange');
Route::get('/profile/change', [Auth0Controller::class, 'profilechange'])->name('profilechange');


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [Auth0Controller::class, 'showDashboard'])->name('dashboard');
    Route::get('/private', [Auth0Controller::class, 'private']);
    Route::get('/scope', [Auth0Controller::class, 'scope'])->can('read:messages');
    Route::get('/colors', [Auth0Controller::class, 'colors']);
    Route::get('/tournaments/select', [Auth0Controller::class, 'showTournamentSelect'])->name('tournaments.select');
    Route::get('/tournaments/{id}', [Auth0Controller::class, 'getTournamentDetails'])->where('id', '[0-9]+');
});

//会員登録
Route::get('/user/register', [Auth0Controller::class, 'showRegistrationForm'])->name('user.register.form');
Route::post('/user/register', [Auth0Controller::class, 'register'])->name('user.register');
Route::post('/user/update', [Auth0Controller::class, 'update'])->name('profile.update');


Route::get('/', [Auth0Controller::class, 'showDashboard'])->name('home');
Route::get('/tournaments/{tournament}/entry', [EntryController::class, 'showEntryForm'])->name('tournament.entry.form');
//Route::post('/tournaments/search/', [EntryController::class, 'getFilteringData'])->name('tournament.search');

Route::get('/api/search', [EntryController::class, 'search']);

//エントリー確認画面
Route::post('/entry/confirm', [EntryController::class, 'confirm'])->name('entry.confirm');
Route::post('/entry/remove', [EntryController::class, 'remove'])->name('entry.remove');

//STRIP
Route::get('/checkout', [PaymentController::class, 'checkout'])->name('payment.checkout');
Route::post('/checkout', [PaymentController::class, 'checkout'])->name('checkout');

Route::get('/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');

//エントリー完了確認画面
//Route::get('/entry/complete', [EntryController::class, 'complete'])->name('entry.complete');
Route::get('/entry/complete-redirect', [EntryController::class, 'redirectToComplete'])->name('entry.complete.redirect');
Route::post('/entry/complete', [EntryController::class, 'complete'])->name('entry.complete');

//エントリー確認
Route::get('/entry/list', [EntryController::class, 'list'])->name('entry.list');
Route::get('/entry/fetch/{competition_id}', [EntryController::class, 'fetchEntries'])->name('entry.fetch');

