<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabel 'children' menyimpan data balita yang terdaftar di posyandu.
     * - user_id     : mengacu ke orang tua (role: orang_tua), nullable jika belum punya akun
     * - posyandu_id : posyandu tempat anak terdaftar, nullable jika belum ditentukan
     */
    public function up(): void
    {
        Schema::create('children', function (Blueprint $table) {
            $table->id();

            // Orang tua pemilik data anak (nullable: anak bisa diinput bidan tanpa akun ortu)
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Posyandu tempat anak aktif terdaftar
            $table->foreignId('posyandu_id')
                ->nullable()
                ->constrained('posyandus')
                ->nullOnDelete();

            $table->string('nik', 16)->unique()->nullable(); // NIK anak (opsional)
            $table->string('name');
            $table->date('birth_date');
            $table->string('birth_place');
            $table->enum('gender', ['male', 'female']);
            $table->text('address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('children');
    }
};
