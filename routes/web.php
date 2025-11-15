<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AgentApprovalController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\TransferManagementController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ComplianceController;
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

// -----------------------------
// ADMIN ROUTES (requires auth.session + admin role)
// -----------------------------
Route::middleware(['auth.session', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        
        // Agent Management
        Route::get('/agents', [AgentApprovalController::class, 'index'])->name('agents.index');
        Route::post('/agents/{agent}/approve', [AgentApprovalController::class, 'approve'])->name('agents.approve');
        Route::post('/agents/{agent}/revoke', [AgentApprovalController::class, 'revoke'])->name('agents.revoke');
        
        // User Management
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [UserManagementController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
        
        // Transfer Management
        Route::get('/transfers', [TransferManagementController::class, 'index'])->name('transfers.index');
        Route::get('/transfers/{transfer}', [TransferManagementController::class, 'show'])->name('transfers.show');
        Route::post('/transfers/{transfer}/cancel', [TransferManagementController::class, 'cancel'])->name('transfers.cancel');
        Route::post('/transfers/{transfer}/update-status', [TransferManagementController::class, 'updateStatus'])->name('transfers.updateStatus');
        
        // System Settings
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
        Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
        
        // Compliance & Monitoring
        Route::get('/compliance', [ComplianceController::class, 'index'])->name('compliance');
        Route::post('/compliance/flag/{transfer}', [ComplianceController::class, 'flagTransaction'])->name('compliance.flag');
        Route::post('/compliance/resolve/{alertId}', [ComplianceController::class, 'resolveAlert'])->name('compliance.resolve');
        Route::get('/audit-log', [ComplianceController::class, 'auditLog'])->name('audit-log');
    });
