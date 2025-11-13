<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AgentApprovalController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Session;
// -----------------------------
// PUBLIC ROUTES (no login required)
// -----------------------------
Route::get('/', [AuthController::class, 'showSignupForm'])->name('signup'); 
Route::get('/signup', [AuthController::class, 'showSignupForm']);
Route::post('/signup', [AuthController::class, 'register'])->name('signup.submit');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

 Route::get('/index', [AuthController::class, 'index'])
    ->name('home')
    ->middleware('auth.session');



Route::get('/session-check', function () {
    if(session()->has('user')) {
        return session('user'); // will display user array
    }
    return 'No user in session';
});

 Route::get('/services', [AuthController::class, 'services'])
    ->name('services')
    ->middleware('auth.session');


Route::get('/verify/{token}', [AuthController::class, 'verifyEmail'])->name('verify.email');






Route::middleware('auth.session')->group(function () {
   

   

    Route::get('/agents', function () {
        return view('agents');
    })->name('agents');

    Route::get('/send', function () {
        return view('send');
    })->name('send');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth.session', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/agents', [AgentApprovalController::class, 'index'])->name('agents.index');
        Route::post('/agents/{agent}/approve', [AgentApprovalController::class, 'approve'])->name('agents.approve');
        Route::post('/agents/{agent}/revoke', [AgentApprovalController::class, 'revoke'])->name('agents.revoke');
    });

