<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserRoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Default route - Lo Shu screen (accessible without authentication)
Route::get('/', [JobController::class, 'job1'])->name('home');

// Info Routes (About and Help)
Route::get('/about', [InfoController::class, 'about'])->name('about');
Route::get('/help', [InfoController::class, 'help'])->name('help');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // Password Reset Routes
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Email Verification Routes
// Verification notice page - public (users can see this on login page)
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->name('verification.notice');

// Email verification link - works without requiring user to be logged in
Route::get('/email/verify/{id}/{hash}', function (Request $request, string $id, string $hash) {
    $user = \App\Models\User::findOrFail($id);
    
    // Verify the hash matches the user's email
    if (!hash_equals(sha1($user->getEmailForVerification()), $hash)) {
        abort(403, 'Invalid verification link.');
    }
    
    // If already verified, just log them in and redirect
    if ($user->hasVerifiedEmail()) {
        \Illuminate\Support\Facades\Auth::login($user);
        return redirect()->route('job1')->with('status', 'Your email is already verified. You are now logged in.');
    }
    
    // Mark email as verified
    $user->markEmailAsVerified();
    event(new \Illuminate\Auth\Events\Verified($user));
    
    // Log the user in
    \Illuminate\Support\Facades\Auth::login($user);
    
    // Ensure at least one role manager exists after email verification
    // This will assign Role_1 to the first verified user
    \App\Models\User::ensureRoleManagerExists();
    
    // Refresh user relationships to ensure we have the latest roles
    $user->refresh();
    
    // Assign Role_2 (Default User) to users after email verification
    // Skip Role_2 for users who are Role Managers (first user gets Role_1 only)
    if (!$user->isRoleManager()) {
        $role2 = \App\Models\Role::where('number', 2)->first();
        if ($role2 && !$user->hasRole(2)) {
            $user->roles()->syncWithoutDetaching([$role2->id]);
        }
    }
    
    return redirect()->route('job1')->with('status', 'Your email has been verified! You are now logged in.');
})->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

// Resend verification email - requires authentication
Route::middleware(['auth'])->group(function () {
    Route::post('/email/verification-notification', function (\Illuminate\Http\Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    })->middleware(['throttle:6,1'])->name('verification.send');
});

// Lo Shu routes (accessible to all, but save/load require authentication and verification)
Route::get('/job1', [JobController::class, 'job1'])->name('job1');
Route::post('/job1', [JobController::class, 'store'])->name('job1.store')->middleware(['auth', 'verified']);
Route::get('/job1/clients', [JobController::class, 'index'])->name('job1.clients')->middleware(['auth', 'verified']);
Route::delete('/job1/{id}', [JobController::class, 'destroy'])->name('job1.destroy')->middleware(['auth', 'verified']);
Route::post('/job1/vedic-astrology', [JobController::class, 'calculateVedicAstrology'])->name('job1.vedic-astrology');

// Authenticated Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Profile Routes (accessible even when unverified after email change)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [ProfileController::class, 'showPasswordResetForm'])->name('profile.password');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});

// Role Management Routes (Role Manager only)
Route::middleware(['auth', 'verified'])->prefix('roles')->name('roles.')->group(function () {
    Route::get('/', [RoleController::class, 'index'])->name('index');
    Route::post('/', [RoleController::class, 'store'])->name('store');
    Route::put('/{role}', [RoleController::class, 'update'])->name('update');
    Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');
});

// User Role Assignment Routes (Role Manager only)
Route::middleware(['auth', 'verified'])->prefix('user-roles')->name('user-roles.')->group(function () {
    Route::get('/', [UserRoleController::class, 'index'])->name('index');
    Route::post('/bulk-update', [UserRoleController::class, 'bulkUpdate'])->name('bulk-update');
    Route::put('/{user}', [UserRoleController::class, 'update'])->name('update');
    Route::delete('/{user}', [UserRoleController::class, 'destroy'])->name('destroy');
});
