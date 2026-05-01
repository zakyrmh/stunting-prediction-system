<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // Balita
    Volt::route('/balita', 'balita.index')->name('balita.index');
    Volt::route('/balita/tambah', 'balita.form')->name('balita.form');
    Volt::route('/balita/{balita}', 'balita.show')->name('balita.show');

    // Prediksi
    Volt::route('/prediksi', 'prediction.index')->name('prediksi.index');
    Volt::route('/prediksi/tambah', 'prediction.form')->name('prediksi.form');
    Volt::route('/prediksi/{prediction}', 'prediction.show')->name('prediksi.show');

    // Admin only
    Route::middleware(['admin'])->group(function () {
        Volt::route('/posyandu', 'posyandu.index')->name('posyandu.index');
        Volt::route('/posyandu/tambah', 'posyandu.form')->name('posyandu.form');

        Volt::route('/users', 'users.index')->name('users.index');
    });

});

require __DIR__.'/settings.php';
