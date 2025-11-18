<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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
    
    // Bank Account Verification Routes (Email-only)
    Route::get('/bank-accounts/{bankAccount}/verify', [BankAccountController::class, 'showVerificationForm'])->name('bank-accounts.verify-form');
    Route::post('/bank-accounts/{bankAccount}/send-verification-email', [BankAccountController::class, 'sendVerificationEmail'])->name('bank-accounts.send-verification-email');

   

    Route::get('/agents', function () {
        return view('agents');
    })->name('agents');

    Route::get('/send', function () {
        return view('send');
    })->name('send');

    // Transfer services search
    Route::get('/transfer-services', [\App\Http\Controllers\TransferServiceController::class, 'index'])
        ->name('transfer-services.index');

    // Beneficiary Management Routes
    Route::get('/beneficiaries', [\App\Http\Controllers\BeneficiaryController::class, 'index'])->name('beneficiaries.index');
    Route::get('/beneficiaries/create', [\App\Http\Controllers\BeneficiaryController::class, 'create'])->name('beneficiaries.create');
    Route::post('/beneficiaries', [\App\Http\Controllers\BeneficiaryController::class, 'store'])->name('beneficiaries.store');
    Route::get('/beneficiaries/{id}', [\App\Http\Controllers\BeneficiaryController::class, 'show'])->name('beneficiaries.show');
    Route::get('/beneficiaries/{id}/edit', [\App\Http\Controllers\BeneficiaryController::class, 'edit'])->name('beneficiaries.edit');
    Route::put('/beneficiaries/{id}', [\App\Http\Controllers\BeneficiaryController::class, 'update'])->name('beneficiaries.update');
    Route::delete('/beneficiaries/{id}', [\App\Http\Controllers\BeneficiaryController::class, 'destroy'])->name('beneficiaries.destroy');

    // Money Transfer Routes
    Route::get('/transfers', [\App\Http\Controllers\TransferController::class, 'index'])->name('transfers.index');
    Route::get('/transfers/create', [\App\Http\Controllers\TransferController::class, 'create'])->name('transfers.create');
    Route::post('/transfers', [\App\Http\Controllers\TransferController::class, 'store'])->name('transfers.store');
    Route::get('/transfers/{id}', [\App\Http\Controllers\TransferController::class, 'show'])->name('transfers.show');
    Route::post('/transfers/calculate-quote', [\App\Http\Controllers\TransferController::class, 'calculateQuote'])->name('transfers.calculate-quote');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Email verification link endpoint (does not require session)
Route::get('/bank-accounts/verify-email/{bankAccount}/{token}', [BankAccountController::class, 'verifyByEmail'])
    ->name('bank-accounts.verify-email');

