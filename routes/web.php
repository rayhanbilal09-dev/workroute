<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;

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

    // ── Kelola Akun (Admin only) ──────────────────────────────────────
    Route::middleware('role:admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // ── Individual Chat ───────────────────────────────────────────────
    Route::get('/chat/individual', [ChatController::class, 'individual'])->name('chat.individual');
    Route::post('/chat/individual/{receiver}', [ChatController::class, 'sendIndividual'])->name('chat.individual.send');

    // Chat edit & delete (individual & group messages)
    Route::put('/chat/{chat}', [ChatController::class, 'update'])->name('chat.update');
    Route::delete('/chat/{chat}', [ChatController::class, 'destroy'])->name('chat.destroy');

    // ── Group Chats ───────────────────────────────────────────────────
    Route::get('/chat/groups', [ChatController::class, 'groups'])->name('chat.group');
    Route::get('/chat/groups/create', [ChatController::class, 'createGroup'])->name('chat.group.create')->middleware('role:admin');
    Route::post('/chat/groups', [ChatController::class, 'storeGroup'])->name('chat.group.store')->middleware('role:admin');
    Route::get('/chat/groups/{group}', [ChatController::class, 'showGroup'])->name('chat.group.show');
    Route::post('/chat/groups/{group}', [ChatController::class, 'sendGroup'])->name('chat.group.send');
    Route::get('/chat/groups/{group}/edit', [ChatController::class, 'editGroup'])->name('chat.group.edit')->middleware('role:admin');
    Route::put('/chat/groups/{group}', [ChatController::class, 'updateGroup'])->name('chat.group.update')->middleware('role:admin');
    Route::delete('/chat/groups/{group}', [ChatController::class, 'destroyGroup'])->name('chat.group.destroy')->middleware('role:admin');
});
