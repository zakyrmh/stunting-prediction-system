<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabel 'posyandu_sessions' menyimpan jadwal & riwayat kegiatan posyandu.
     * Setiap sesi mewakili satu hari kegiatan penimbangan/pemeriksaan di posyandu.
     * Predictions (pemeriksaan anak) dapat dikelompokkan berdasarkan sesi ini.
     *
     * Status sesi:
     *   - scheduled  : terjadwal, belum dilaksanakan
     *   - ongoing    : sedang berlangsung
     *   - completed  : selesai dilaksanakan
     *   - cancelled  : dibatalkan
     */
    public function up(): void
    {
        Schema::create('posyandu_sessions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('posyandu_id')
                ->constrained('posyandus')
                ->cascadeOnDelete();

            $table->date('session_date');                  // Tanggal pelaksanaan
            $table->time('start_time')->nullable();        // Jam mulai (opsional)
            $table->time('end_time')->nullable();          // Jam selesai (opsional)

            $table->enum('status', [
                'scheduled',
                'ongoing',
                'completed',
                'cancelled',
            ])->default('scheduled');

            $table->text('notes')->nullable();             // Catatan kegiatan

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posyandu_sessions');
    }
};
