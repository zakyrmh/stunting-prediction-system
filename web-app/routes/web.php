<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChildrenController;
use App\Http\Controllers\PredictionController;
use App\Http\Controllers\PosyanduController;
use App\Http\Controllers\UserController;

Route::view('/', 'welcome')->name('home');
Route::view('/edukasi', 'edukasi')->name('edukasi');

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Balita
    Route::get('/balita', [ChildrenController::class, 'index'])->name('balita.index');
    Route::get('/balita/tambah', [ChildrenController::class, 'create'])->name('balita.form');
    Route::post('/balita/tambah', [ChildrenController::class, 'store'])->name('balita.store');
    Route::get('/balita/{child}/edit', [ChildrenController::class, 'edit'])->name('balita.edit');
    Route::put('/balita/{child}', [ChildrenController::class, 'update'])->name('balita.update');
    Route::post('/balita/override/{child}', [ChildrenController::class, 'overrideStatus'])->name('balita.override-status');
    Volt::route('/balita/{balita}', 'balita.show')->name('balita.show');

    // Prediksi
    Route::get('/prediksi', [PredictionController::class, 'index'])->name('prediksi.index');
    Volt::route('/prediksi/tambah', 'prediction.form')->name('prediksi.form')->middleware('kader');
    Volt::route('/prediksi/{prediction}', 'prediction.show')->name('prediksi.show');

    // Bidan only
    Route::middleware(['bidan'])->group(function () {
        Route::get('/posyandu', [PosyanduController::class, 'index'])->name('posyandu.index');
        Route::get('/posyandu/tambah', [PosyanduController::class, 'create'])->name('posyandu.form');
        Route::post('/posyandu/tambah', [PosyanduController::class, 'store'])->name('posyandu.store');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::patch('/users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

});

require __DIR__.'/settings.php';
