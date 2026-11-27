<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\RewardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WasteTypeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Customer\PortalController;
use App\Http\Controllers\Officer\DepositController;
use App\Http\Controllers\Officer\RedemptionVerificationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route(Auth::user()->role->dashboardRoute());
    }

    return view('landing');
})->name('landing');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function (): void {
    Route::get('/account', [AccountController::class, 'edit'])->name('account.edit');
    Route::put('/account/profile', [AccountController::class, 'updateProfile'])->name('account.profile.update');
    Route::get('/account/password', [AccountController::class, 'edit'])->name('account.password.edit');
    Route::put('/account/password', [AccountController::class, 'updatePassword'])->name('account.password.update');
});

// Customer (Nasabah) Portal
Route::middleware(['auth', 'role:USER'])->group(function (): void {
    Route::get('/dashboard', [PortalController::class, 'dashboard'])->name('customer.dashboard');
    Route::get('/catalog-waste', [PortalController::class, 'catalogWaste'])->name('customer.catalog-waste');
    Route::get('/rewards', [PortalController::class, 'rewards'])->name('customer.rewards');
    Route::post('/rewards/{reward}/redeem', [PortalController::class, 'redeem'])->name('customer.rewards.redeem');
    Route::post('/redemptions/{redemption}/cancel', [PortalController::class, 'cancelRedemption'])->name('customer.redemptions.cancel');
    Route::get('/history', [PortalController::class, 'history'])->name('customer.history');
});

// Officer (Petugas Posko) & Admin Operations
Route::middleware(['auth', 'role:OFFICER,ADMIN'])->prefix('officer')->name('officer.')->group(function (): void {
    Route::get('/deposit', [DepositController::class, 'index'])->name('deposit.index');
    Route::get('/customers/search', [DepositController::class, 'searchCustomers'])->name('customers.search');
    Route::post('/deposit', [DepositController::class, 'store'])->name('deposit.store');
    Route::get('/deposit/{deposit}/receipt', [DepositController::class, 'receipt'])->name('deposit.receipt');

    Route::get('/redemptions', [RedemptionVerificationController::class, 'index'])->name('redemptions.index');
    Route::post('/redemptions/{redemption}/complete', [RedemptionVerificationController::class, 'complete'])->name('redemptions.complete');
    Route::post('/redemptions/{redemption}/cancel', [RedemptionVerificationController::class, 'cancel'])->name('redemptions.cancel');
});

// Admin Panel
Route::middleware(['auth', 'role:ADMIN'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/waste-types', [WasteTypeController::class, 'index'])->name('waste-types.index');
    Route::post('/waste-types', [WasteTypeController::class, 'store'])->name('waste-types.store');
    Route::put('/waste-types/{wasteType}', [WasteTypeController::class, 'update'])->name('waste-types.update');

    Route::get('/rewards', [RewardController::class, 'index'])->name('rewards.index');
    Route::post('/rewards', [RewardController::class, 'store'])->name('rewards.store');
    Route::put('/rewards/{reward}', [RewardController::class, 'update'])->name('rewards.update');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}/password', [UserController::class, 'resetPassword'])->name('users.reset-password');

    Route::get('/audit-logs', [UserController::class, 'auditLogs'])->name('audit-logs.index');
});
