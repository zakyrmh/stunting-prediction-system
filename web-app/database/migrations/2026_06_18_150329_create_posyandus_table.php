<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * File ini melakukan DUA hal secara berurutan:
     *
     *  1. Membuat tabel 'posyandus' — harus ada sebelum tabel lain
     *     karena hampir semua tabel utama merujuk posyandu_id ke sini.
     *
     *  2. Menambahkan FK constraint posyandu_id pada tabel 'users' —
     *     dilakukan di sini (bukan di create_users_table) karena saat
     *     users dibuat, tabel posyandus belum ada (circular dependency).
     *
     * Urutan dependency FK yang bergantung pada tabel ini:
     *   users.posyandu_id          → posyandus.id  (ditambahkan di bawah)
     *   children.posyandu_id       → posyandus.id  (di create_children_table)
     *   posyandu_sessions.posyandu_id → posyandus.id (di create_posyandu_sessions_table)
     *   predictions.posyandu_id    → posyandus.id  (di create_predictions_table)
     */
    public function up(): void
    {
        // ── 1. Buat tabel posyandus ────────────────────────────────────────────
        Schema::create('posyandus', function (Blueprint $table) {
            $table->id();
            $table->string('name');         // Nama posyandu, contoh: "Posyandu Melati I"
            $table->text('address');        // Alamat lengkap
            $table->string('village');      // Desa / Kelurahan
            $table->string('district');     // Kecamatan
            $table->string('city');         // Kabupaten / Kota
            $table->timestamps();
        });

        // ── 2. Tambahkan FK posyandu_id pada tabel users ──────────────────────
        // Kolom posyandu_id sudah ada (dibuat nullable di create_users_table),
        // di sini hanya ditambahkan constraint-nya.
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('posyandu_id')
                ->references('id')
                ->on('posyandus')
                ->nullOnDelete(); // Jika posyandu dihapus, posyandu_id user menjadi NULL
        });
    }

    /**
     * Reverse the migrations.
     *
     * Urutan down() harus KEBALIKAN dari up():
     *  1. Lepas FK di users terlebih dahulu
     *  2. Baru drop tabel posyandus
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['posyandu_id']);
        });

        Schema::dropIfExists('posyandus');
    }
};
