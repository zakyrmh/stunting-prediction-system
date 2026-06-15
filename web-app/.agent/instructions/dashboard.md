## 🩺 1. Dashboard Bidan / Tenaga Kesehatan (Super User)

Fokus utama Bidan adalah **monitoring makro, verifikasi klinis, dan evaluasi hasil AI**. Bidan membutuhkan metrik yang komprehensif untuk mengambil keputusan medis.

### **Komponen & Fitur yang Harus Ada:**

* **Widget Statistik Global (Kesehatan Wilayah):**
    * Total Balita Terdaftar di Puskesmas/Posyandu.
    * Jumlah & Persentase Balita Berstatus *Stunting* (Lampu Merah).
    * Jumlah Balita Berstatus *Growth Faltering* / Gagal Tumbuh (Lampu Kuning).

* **Grafik Analisis & Komparasi Model (Output ML):**
    * Tampilkan **Bar Chart** komparasi performa algoritma dari riset notebook-mu: **Random Forest (97.31%)**, **K-Nearest Neighbors (96.24%)**, dan **Naïve Bayes (42.55%)**.
    * Sediakan *space* teks analisis singkat yang menjelaskan mengapa Random Forest menjadi model utama penentu di sistem ini karena kestabilannya saat *10-Fold Cross-Validation* (Akurasi 97.42% ±0.30%).

* **Tabel Antrean Verifikasi Sistem Pakar (*Pending Approvals*):**
    * Daftar balita yang baru saja diinput oleh Kader dan memiliki tingkat risiko *Certainty Factor* tinggi dari FastAPI.
    * Tombol aksi cepat: **"Tinjau Rekomendasi Gizi"** agar Bidan bisa memvalidasi atau memberikan catatan tambahan sebelum statusnya sah dikirim ke akun orang tua.
    * **Shortcut Menu:** Manajemen Akun Kader, Cetak Laporan Bulanan Puskesmas, dan Pengaturan Tabel Aturan (*Knowledge Base*).

---

## 📝 2. Dashboard Kader Posyandu (Data Entry / Operator)

Fokus utama Kader adalah **kecepatan operasional lapangan (efisiensi *data entry*)**. Mereka tidak membutuhkan statistik algoritma ML, melainkan petunjuk teknis siapa anak yang harus diperiksa hari ini.

### **Komponen & Fitur yang Harus Ada:**
* **Widget Operasional Posyandu Hari Ini:**
    * Jumlah Balita Hadir/Ditimbang Hari Ini.
    * Jumlah Target Balita yang Belum Kunjung Datang Bulan Ini.
    * Notifikasi Cepat: *"Ada 3 balita di Posyandu Anda mengalami Gagal Tumbuh (2T) bulan ini. Segera picu kuesioner gejala luar!"*

* **Aksi Cepat Operasional (Quick Actions Buttons):**
    * Tombol besar: **"➕ Tambah Balita Baru"**
    * Tombol besar: **"⚖️ Input Catatan Bulanan (Timbang/Ukur)"**

* **Tabel Aktivitas Kunjungan Terakhir:**
    * Menampilkan 5-10 baris data balita yang baru saja selesai diukur berat dan tinggi badannya pada hari tersebut untuk memastikan tidak ada salah ketik angka (*human error*).

---

## 👁️ 3. Dashboard Orang Tua / Ibu Balita (Viewer / Read-Only)

Dashboard ini harus dirancang **sangat humanis, menenangkan, mudah dipahami orang awam**, dan murni bersifat *read-only* (tidak bisa memanipulasi data apa pun). Data yang ditampilkan **dikunci secara ketat** hanya untuk anak dari ibu tersebut.

### **Komponen & Fitur yang Harus Ada:**
* **Ringkasan Status Anak Terakhir:**
    * Kartu Profil Anak: Nama, Umur (Bulan), Berat Badan Terakhir, dan Tinggi Badan Terakhir.
    * Badge Status Pertumbuhan Visual: *Normal* (Hijau), *Waspada Gagal Tumbuh* (Kuning), atau *Perlu Intervensi Gizi* (Merah).

* **Grafik Pemantauan Runtun Waktu (*Time-Series Tracking*):**
    * **Line Chart (Grafik Garis):** Memetakan tren kenaikan berat badan dan tinggi badan anak dari bulan ke bulan dibandingkan dengan garis batas ideal standar Kemenkes/WHO. Ibu bisa melihat apakah grafiknya bergerak naik atau mendatar.

* **Kotak Hasil Diagnosis & Rekomendasi Makanan (Output FastAPI):**
    * Jika anak terindikasi risiko oleh Certainty Factor, tampilkan box khusus: *"Hasil pemeriksaan Posyandu terakhir menunjukkan Ananda membutuhkan perhatian pada sektor zat gizi mikro."*
    * **Poin-Poin Solusi Nyata:** Tampilkan list resep makanan PMT lokal yang dikirim dari FastAPI (misal: pemberian telur/hati ayam harian, serta penerapan *feeding rules* dari IDAI).
