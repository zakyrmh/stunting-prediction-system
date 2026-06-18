<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabel 'users' mencakup tiga role dengan kebutuhan berbeda:
     *
     *   - bidan      : tenaga kesehatan, wajib terdaftar di satu posyandu
     *   - kader      : relawan posyandu, wajib terdaftar di satu posyandu
     *   - orang_tua  : orang tua/wali balita, posyandu_id opsional (bisa didapat dari data anak)
     *
     * Catatan 2FA:
     *   Kolom two_factor_* hanya aktif jika aplikasi menggunakan Laravel Jetstream.
     *   Jika tidak menggunakan Jetstream, hapus ketiga kolom tersebut untuk menghindari
     *   kolom yang tidak terpakai (dead weight).
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // ── Identitas ──────────────────────────────────────────────────────
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone', 20)->nullable()->unique(); // Nomor HP/WA, penting untuk notifikasi
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // ── Peran & Asosiasi ───────────────────────────────────────────────
            $table->enum('role', ['bidan', 'kader', 'orang_tua'])->default('orang_tua');

            // posyandu_id: wajib untuk bidan & kader, opsional untuk orang_tua
            // FK ke tabel posyandus ditambahkan di: create_posyandus_table.php
            // (karena tabel posyandus belum ada saat migrasi ini dijalankan)
            $table->foreignId('posyandu_id')->nullable();

            // ── Status Akun ────────────────────────────────────────────────────
            // Menonaktifkan akun tanpa menghapus data historis bidan/kader
            $table->boolean('is_active')->default(true);

            // ── Two Factor Authentication (Jetstream) ──────────────────────────
            // Hapus tiga kolom di bawah ini jika TIDAK menggunakan Laravel Jetstream.
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();

            // ── Laravel Defaults ───────────────────────────────────────────────
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
