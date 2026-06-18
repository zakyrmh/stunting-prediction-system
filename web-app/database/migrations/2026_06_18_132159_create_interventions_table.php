<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabel 'interventions' menyimpan rekomendasi intervensi & tindak lanjutnya
     * berdasarkan hasil prediksi stunting.
     *
     * Alur penggunaan:
     *   1. Setelah prediksi dibuat (terutama hasil stunting_risk / stunted / severely_stunted),
     *      sistem atau bidan membuat record intervensi dengan rekomendasi dari sistem pakar.
     *   2. Bidan/kader yang bertanggung jawab (handled_by) menindaklanjuti rekomendasi.
     *   3. Status diperbarui sesuai progress, dan follow_up_notes diisi saat kunjungan ulang.
     *
     * Status intervensi:
     *   - pending     : rekomendasi dibuat, belum ditindaklanjuti
     *   - in_progress : sedang dalam proses penanganan
     *   - done        : selesai ditangani
     *   - cancelled   : dibatalkan (misal: anak pindah domisili)
     */
    public function up(): void
    {
        Schema::create('interventions', function (Blueprint $table) {
            $table->id();

            // Prediksi yang memicu intervensi ini
            $table->foreignId('prediction_id')
                ->constrained('predictions')
                ->cascadeOnDelete();

            // Rekomendasi dari sistem pakar (dapat berupa teks panjang)
            $table->text('recommendation');

            $table->enum('status', [
                'pending',
                'in_progress',
                'done',
                'cancelled',
            ])->default('pending');

            // Tanggal target kunjungan/tindak lanjut berikutnya
            $table->date('follow_up_date')->nullable();

            // Catatan hasil tindak lanjut (diisi setelah kunjungan)
            $table->text('follow_up_notes')->nullable();

            // Bidan/kader yang bertanggung jawab menangani intervensi ini
            // nullable: jika bidan dihapus dari sistem, data intervensi tetap terjaga
            $table->foreignId('handled_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interventions');
    }
};
