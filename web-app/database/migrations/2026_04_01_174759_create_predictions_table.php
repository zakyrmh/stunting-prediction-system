<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabel 'predictions' menyimpan hasil pemeriksaan & prediksi stunting per anak.
     * Setiap baris merupakan satu kali pemeriksaan untuk satu anak oleh bidan/kader.
     *
     * Kolom penting:
     *   - child_id      : anak yang diperiksa (wajib)
     *   - posyandu_id   : posyandu tempat pemeriksaan dilakukan (snapshot historis)
     *   - recorded_by   : user (bidan/kader) yang mencatat hasil pemeriksaan
     *   - session_id    : sesi posyandu terkait (opsional, untuk pengelompokan)
     *   - weight        : berat badan dalam kg (contoh: 12.50)
     *   - height        : tinggi badan dalam cm (contoh: 85.50)
     *   - age_months    : usia anak dalam bulan saat diperiksa (snapshot, bukan dihitung ulang)
     *   - examined_at   : tanggal pemeriksaan dilakukan
     *   - result        : hasil klasifikasi stunting dari model AI
     *   - confidence    : tingkat keyakinan model (0.0000 – 1.0000)
     *   - notes         : catatan tambahan dari bidan/kader
     *
     * Nilai enum 'result' berdasarkan standar WHO:
     *   - normal            : HAZ >= -2 SD (tumbuh kembang normal)
     *   - stunting_risk     : HAZ mendekati -2 SD (berisiko, perlu perhatian)
     *   - stunted           : -3 SD <= HAZ < -2 SD (pendek)
     *   - severely_stunted  : HAZ < -3 SD (sangat pendek)
     */
    public function up(): void
    {
        Schema::create('predictions', function (Blueprint $table) {
            $table->id();

            // Anak yang diperiksa
            $table->foreignId('child_id')
                ->constrained('children')
                ->cascadeOnDelete();

            // Posyandu tempat pemeriksaan (disimpan sebagai snapshot historis)
            $table->foreignId('posyandu_id')
                ->constrained('posyandus')
                ->cascadeOnDelete();

            // Bidan atau kader yang mencatat hasil pemeriksaan
            $table->foreignId('recorded_by')
                ->constrained('users')
                ->cascadeOnDelete();

            // Sesi posyandu (opsional, nullable jika diinput di luar jadwal sesi)
            $table->foreignId('session_id')
                ->nullable()
                ->constrained('posyandu_sessions')
                ->nullOnDelete();

            // Data antropometri
            $table->decimal('weight', 5, 2);       // Berat badan (kg), maks 999.99
            $table->decimal('height', 5, 2);       // Tinggi badan (cm), maks 999.99
            $table->unsignedTinyInteger('age_months'); // Usia saat diperiksa (0–255 bulan)

            $table->date('examined_at');            // Tanggal pemeriksaan

            // Hasil prediksi dari AI
            $table->enum('result', [
                'normal',
                'stunting_risk',
                'stunted',
                'severely_stunted',
            ]);

            $table->decimal('confidence', 5, 4);   // Confidence score (0.0000 – 1.0000)
            $table->text('notes')->nullable();      // Catatan tambahan

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('predictions');
    }
};
