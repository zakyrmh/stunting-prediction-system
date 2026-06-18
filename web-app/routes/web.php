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
    Route::post('/balita/override/{child}', [ChildrenController::class, 'overrideStatus'])->name('balita.override-status');
    Volt::route('/balita/tambah', 'balita.form')->name('balita.form');
    Volt::route('/balita/{balita}', 'balita.show')->name('balita.show');

    // Prediksi
    Route::get('/prediksi', [PredictionController::class, 'index'])->name('prediksi.index');
    Volt::route('/prediksi/tambah', 'prediction.form')->name('prediksi.form');
    Volt::route('/prediksi/{prediction}', 'prediction.show')->name('prediksi.show');

    // Bidan only
    Route::middleware(['bidan'])->group(function () {
        Route::get('/posyandu', [PosyanduController::class, 'index'])->name('posyandu.index');
        Volt::route('/posyandu/tambah', 'posyandu.form')->name('posyandu.form');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::patch('/users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

});

require __DIR__.'/settings.php';
