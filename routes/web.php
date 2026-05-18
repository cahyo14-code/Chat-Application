<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;

// Redirect ke register
Route::get('/', function () {
    return redirect()->route('register');
});

// Auth Routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Chat Routes (hanya bisa diakses kalau sudah login)
Route::middleware('auth')->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/private/{user}', [ChatController::class, 'openPrivate'])->name('chat.private');
    Route::post('/chat/group/create', [ChatController::class, 'createGroup'])->name('chat.group.create');
    Route::get('/chat/{conversation}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{conversation}/send', [ChatController::class, 'sendMessage'])->name('chat.send');
});