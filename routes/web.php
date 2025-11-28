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
use App\Http\Controllers\Admin\ExchangeRateController;
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

    // Wallet Routes
    Route::get('/wallet', [\App\Http\Controllers\WalletController::class, 'index'])->name('wallet.index');
    Route::get('/wallet/deposit', [\App\Http\Controllers\WalletController::class, 'showDepositForm'])->name('wallet.deposit.form');
    Route::post('/wallet/deposit', [\App\Http\Controllers\WalletController::class, 'deposit'])->name('wallet.deposit');
    Route::get('/wallet/withdraw', [\App\Http\Controllers\WalletController::class, 'showWithdrawForm'])->name('wallet.withdraw.form');
    Route::post('/wallet/withdraw', [\App\Http\Controllers\WalletController::class, 'withdraw'])->name('wallet.withdraw');

    // Money Transfer Routes
    Route::get('/transfers', [\App\Http\Controllers\TransferController::class, 'index'])->name('transfers.index');
    Route::get('/transfers/create', [\App\Http\Controllers\TransferController::class, 'create'])->name('transfers.create');
    Route::post('/transfers', [\App\Http\Controllers\TransferController::class, 'store'])->name('transfers.store');
    Route::get('/transfers/{id}', [\App\Http\Controllers\TransferController::class, 'show'])->name('transfers.show');
    Route::post('/transfers/calculate-quote', [\App\Http\Controllers\TransferController::class, 'calculateQuote'])->name('transfers.calculate-quote');
    Route::post('/transfers/{id}/update-status', [\App\Http\Controllers\TransferController::class, 'updateStatus'])->name('transfers.update-status');

    // Customer Support Routes
    Route::get('/support', [\App\Http\Controllers\SupportController::class, 'index'])->name('support.index');
    Route::post('/support/create-ticket', [\App\Http\Controllers\SupportController::class, 'createTicket'])->name('support.create-ticket');
    Route::get('/support/ticket/{ticketId}', [\App\Http\Controllers\SupportController::class, 'showTicket'])->name('support.ticket');
    Route::post('/support/ticket/{ticketId}/message', [\App\Http\Controllers\SupportController::class, 'addMessage'])->name('support.add-message');
    Route::post('/support/ticket/{ticketId}/close', [\App\Http\Controllers\SupportController::class, 'closeTicket'])->name('support.close-ticket');
    Route::post('/support/chatbot', [\App\Http\Controllers\SupportController::class, 'chatbot'])->name('support.chatbot');

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
        
        // Exchange Rates & Fee Management
        Route::get('/exchange-rates', [ExchangeRateController::class, 'index'])->name('exchange-rates');
        Route::post('/exchange-rates/update-rate', [ExchangeRateController::class, 'updateRate'])->name('exchange-rates.update-rate');
        Route::delete('/exchange-rates/delete-rate/{key}', [ExchangeRateController::class, 'deleteRate'])->name('exchange-rates.delete-rate');
        Route::post('/exchange-rates/update-fee', [ExchangeRateController::class, 'updateFee'])->name('exchange-rates.update-fee');
        Route::delete('/exchange-rates/delete-fee/{currency}', [ExchangeRateController::class, 'deleteFee'])->name('exchange-rates.delete-fee');
        Route::post('/exchange-rates/sync', [ExchangeRateController::class, 'syncRates'])->name('exchange-rates.sync');
        
        // Fraud Detection & Prevention
        Route::get('/fraud-detection', [\App\Http\Controllers\Admin\FraudDetectionController::class, 'index'])->name('fraud.index');
        Route::post('/fraud/review-alert/{alertId}', [\App\Http\Controllers\Admin\FraudDetectionController::class, 'reviewAlert'])->name('fraud.review-alert');
        Route::post('/fraud/block-user/{userId}', [\App\Http\Controllers\Admin\FraudDetectionController::class, 'blockUser'])->name('fraud.block-user');
        Route::post('/fraud/unblock', [\App\Http\Controllers\Admin\FraudDetectionController::class, 'unblockEntity'])->name('fraud.unblock');
        Route::post('/fraud/add-rule', [\App\Http\Controllers\Admin\FraudDetectionController::class, 'addRule'])->name('fraud.add-rule');
        Route::get('/fraud/toggle-rule/{ruleId}', [\App\Http\Controllers\Admin\FraudDetectionController::class, 'toggleRule'])->name('fraud.toggle-rule');
        Route::delete('/fraud/delete-rule/{ruleId}', [\App\Http\Controllers\Admin\FraudDetectionController::class, 'deleteRule'])->name('fraud.delete-rule');
        
        // Reports & Analytics
        Route::get('/reports', [\App\Http\Controllers\Admin\ReportsController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [\App\Http\Controllers\Admin\ReportsController::class, 'export'])->name('reports.export');
    });
// Email verification link endpoint (does not require session)
Route::get('/bank-accounts/verify-email/{bankAccount}/{token}', [BankAccountController::class, 'verifyByEmail'])
    ->name('bank-accounts.verify-email');

