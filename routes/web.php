<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Livewire\TodoList;
use App\Livewire\Calendar;
use App\Http\Controllers\CalendarController;

Route::get('/');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/tasks', function () {
    return view('todo-list');
});

Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');

require __DIR__.'/auth.php';
