<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChatController;

// Guest Dashboard (landing page)
Route::get('/', [DashboardController::class, 'guest'])->name('guest.dashboard');

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'destroyAvatar'])->name('profile.avatar.destroy');
    
    // Tasks & History
    Route::get('/tasks/history', [IssueController::class, 'history'])->name('tasks.history');
    Route::resource('tasks', IssueController::class)->parameters([
        'tasks' => 'issue'
    ]);
    
    // Chat
    Route::get('/chat/individual', [ChatController::class, 'individual'])->name('chat.individual');
    Route::post('/chat/individual/{receiver}', [ChatController::class, 'sendIndividual'])->name('chat.individual.send');
    
    // Chat edit & delete
    Route::put('/chat/{chat}', [ChatController::class, 'update'])->name('chat.update');
    Route::delete('/chat/{chat}', [ChatController::class, 'destroy'])->name('chat.destroy');
    
    // Group Chat ( restricted to admin, worker )
    Route::middleware('role:admin,worker')->group(function () {
        Route::get('/chat/group', [ChatController::class, 'group'])->name('chat.group');
        Route::post('/chat/group', [ChatController::class, 'sendGroup'])->name('chat.group.send');
    });
});
