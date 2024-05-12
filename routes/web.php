<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\MainController;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Models\Gen;
use Illuminate\Support\Facades\Route;
use App\Admin\Controllers\DuDashboardController;
use App\Admin\Controllers\GuestController;
use App\Admin\Controllers\OPDDashboardController;
use App\Admin\Controllers\PersonController;
use App\Admin\Controllers\PwdDashboardController;
use App\Http\Middleware\Du_Dashboard;

Route::get('generate-class', [MainController::class, 'generate_class']);
Route::get('generate-variables', [MainController::class, 'generate_variables']);
Route::get('/', [MainController::class, 'index'])->name('home');
Route::get('/about-us', [MainController::class, 'about_us']);
Route::get('/our-team', [MainController::class, 'our_team']);
Route::get('/news-category/{id}', [MainController::class, 'news_category']);
Route::get('/news-category', [MainController::class, 'news_category']);
Route::get('/news', [MainController::class, 'news_category']);
Route::get('/news/{id}', [MainController::class, 'news']);
Route::get('/members', [MainController::class, 'members']);
Route::get('/dinner', [MainController::class, 'dinner']);
Route::get('/ucc', function () {
    return view('chair-person-message');
});
Route::get('/vision-mission', function () {
    return view('vision-mission');
});
Route::get('/constitution', function () {
    return view('constitution');
});
Route::get('/counseling-and-guidance', [MainController::class, 'counseling_centres']);
Route::get('/register', [AccountController::class, 'register'])->name('register');


Route::get('service-providers', [MainController::class, 'service_providers']);
Route::get('service-providers/{id}', [MainController::class, 'service_provider']);
Route::get('disabilities', [MainController::class, 'disabilities']);
Route::get('disabilities/{id}', [MainController::class, 'disability']);
Route::get('innovations', [MainController::class, 'innovations']);
Route::get('innovations/{id}', [MainController::class, 'innovation']);
Route::get('jobs', [MainController::class, 'jobs']);
Route::get('jobs/{id}', [MainController::class, 'job']);
Route::get('events', [MainController::class, 'events']);
Route::get('events/{id}', [MainController::class, 'event']);
Route::get('resources', [MainController::class, 'resources']);
Route::get('resources/{id}', [MainController::class, 'resource']);
Route::get('/du-dashboard', [DuDashboardController::class, 'index'])
    ->middleware('auth:admin')
    ->name('du-dashboard');
Route::get('/opd-dashboard', [OPDDashboardController::class, 'index'])
    ->middleware('auth:admin')
    ->name('opd-dashboard');
Route::get('/guest', [GuestController::class, 'index']);

Route::get('/login', [AccountController::class, 'login'])->name('login')
    ->middleware(RedirectIfAuthenticated::class);

Route::post('/register', [AccountController::class, 'register_post'])
    ->middleware(RedirectIfAuthenticated::class);

Route::post('/login', [AccountController::class, 'login_post'])
    ->middleware(RedirectIfAuthenticated::class);


Route::get('/dashboard', [AccountController::class, 'dashboard'])
    ->middleware(Authenticate::class);
Route::middleware(['auth:admin', 'verifyProfile'])->group(function () {
    Route::get('/dashboard', [AccountController::class, 'dashboard'])->name('dashboard');
    Route::get('/approval', [PwdDashboardController::class, 'checkApproval'])->name('approval');
    Route::get('/pwd-dashboard', [PwdDashboardController::class, 'index'])->name('pwd-dashboard');
});


//Route for DU dashboard
// Route::get('/du-dashboard', [DuDashboard::class, 'index'])->middleware(Du_Dashboard::class);


Route::get('/account-details', [AccountController::class, 'account_details'])
    ->middleware(Authenticate::class);

Route::post('/account-details', [AccountController::class, 'account_details_post'])
    ->middleware(Authenticate::class);

Route::get('/logout', [AccountController::class, 'logout']);
Route::get('/gen', function () {
    die(Gen::find($_GET['id'])->do_get());
})->name("gen");
