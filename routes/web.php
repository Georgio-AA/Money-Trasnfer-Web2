<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AgentApprovalController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\BankAccountController;
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
   
    // Bank Account Management Routes
    Route::get('/bank-accounts', [BankAccountController::class, 'index'])->name('bank-accounts.index');
    Route::get('/bank-accounts/create', [BankAccountController::class, 'create'])->name('bank-accounts.create');
    Route::post('/bank-accounts', [BankAccountController::class, 'store'])->name('bank-accounts.store');
    Route::get('/bank-accounts/{bankAccount}', [BankAccountController::class, 'show'])->name('bank-accounts.show');
    Route::get('/bank-accounts/{bankAccount}/edit', [BankAccountController::class, 'edit'])->name('bank-accounts.edit');
    Route::put('/bank-accounts/{bankAccount}', [BankAccountController::class, 'update'])->name('bank-accounts.update');
    Route::delete('/bank-accounts/{bankAccount}', [BankAccountController::class, 'destroy'])->name('bank-accounts.destroy');
    
    // Bank Account Verification Routes
    Route::get('/bank-accounts/{bankAccount}/verify', [BankAccountController::class, 'showVerificationForm'])->name('bank-accounts.verify-form');
    Route::post('/bank-accounts/{bankAccount}/verify', [BankAccountController::class, 'verify'])->name('bank-accounts.verify');
    Route::post('/bank-accounts/{bankAccount}/start-micro-verification', [BankAccountController::class, 'startMicroTransferVerification'])->name('bank-accounts.start-micro-verification');

   

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

