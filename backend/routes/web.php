<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\TaskWebController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [TaskWebController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Task CRUD Web Routes
    Route::post('/tasks', [TaskWebController::class, 'store'])->name('tasks.store');
    Route::put('/tasks/{task}', [TaskWebController::class, 'update'])->name('tasks.update');
    Route::patch('/tasks/{task}/toggle', [TaskWebController::class, 'toggle'])->name('tasks.toggle');
    Route::delete('/tasks/{task}', [TaskWebController::class, 'destroy'])->name('tasks.destroy');
});

require __DIR__.'/auth.php';
