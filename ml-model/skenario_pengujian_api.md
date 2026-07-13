# Rencana & Skenario Pengujian API Sistem Pakar Hybrid Stunting

Dokumen ini berisi rencana, metodologi, dan skenario pengujian komprehensif untuk **API Sistem Pakar Hybrid Stunting** (FastAPI) yang menggabungkan prediksi berbasis **Machine Learning (Random Forest)** dan **Sistem Pakar (Certainty Factor)**.

---

## 1. Metodologi Pengujian

Pengujian API ini dirancang khusus untuk mengakomodasi karakteristik sistem hybrid (ML + Rule-based) dengan metodologi berikut:

1. **Black-Box & Functional Testing**: Menguji kecocokan input JSON payload terhadap respons output JSON yang diharapkan tanpa harus menguji kode internal secara terisolasi.
2. **Boundary Value Analysis (BVA)**: Menguji nilai batas fisik anak seperti usia (bulan), berat badan (kg), dan tinggi badan (cm) untuk memastikan validasi skema berjalan dengan benar.
3. **Decision Table / Rule-Based Testing**: Menguji jalur keputusan (logika inferensi) dari gabungan probabilitas ML dan Certainty Factor (CF) dari gejala klinis yang diinput.
4. **Error & Exception Handling Testing**: Memastikan sistem merespons input yang salah, tipe data yang tidak valid, atau nilai kosong dengan kode HTTP status dan pesan error yang tepat.

---

## 2. Struktur Endpoint API yang Diuji

### Endpoint 1: Health Check

- **Metode**: `GET`
- **Path**: `/`
- **Fungsi**: Memastikan server FastAPI aktif dan model ML berhasil dimuat.

### Endpoint 2: Prediksi Hybrid Stunting

- **Metode**: `POST`
- **Path**: `/predict`
- **Fungsi**: Menerima data fisik anak dan nilai keyakinan gejala klinis untuk memberikan analisis risiko dan rekomendasi intervensi.

---

## 3. Skenario Pengujian (Test Scenarios)

### Kategori A: Pengujian Fungsional Dasar (Smoke & Health Check)

| ID Tes      | Judul Skenario   | Input / Aksi    | Expected Output                         | Status HTTP |
| :---------- | :--------------- | :-------------- | :-------------------------------------- | :---------- |
| **TC-F-01** | Health Check API | Panggil `GET /` | `{"status": "Server Hybrid AI Aktif!"}` | `200 OK`    |

---

### Kategori B: Pengujian Validasi Input & Boundary Value (BVA)

Skenario ini menguji aturan Pydantic pada input skema data fisik anak (`gender`, `age_months`, `weight`, `height`).

| ID Tes      | Judul Skenario                                | JSON Payload                                                                                                          | Expected Output                                                                 | Status HTTP                |
| :---------- | :-------------------------------------------- | :-------------------------------------------------------------------------------------------------------------------- | :------------------------------------------------------------------------------ | :------------------------- |
| **TC-V-01** | Input Valid Terendah (Edge Case Usia Minimal) | Usia 0.1 bulan, data fisik positif minimal. <br>`json{"gender": 0, "age_months": 0.1, "weight": 2.5, "height": 45.0}` | BMI dihitung, prediksi ML keluar, dan status sukses.                            | `200 OK`                   |
| **TC-V-02** | Input Usia Tidak Valid (Nilai $\le$ 0)        | Usia 0 bulan. <br>`json{"gender": 0, "age_months": 0, "weight": 3.0, "height": 50.0}`                                 | Error validasi: `Input should be greater than 0` untuk `age_months`.            | `422 Unprocessable Entity` |
| **TC-V-03** | Input Usia Negatif                            | Usia -5 bulan. <br>`json{"gender": 1, "age_months": -5.0, "weight": 4.5, "height": 55.0}`                             | Error validasi: `Input should be greater than 0` untuk `age_months`.            | `422 Unprocessable Entity` |
| **TC-V-04** | Input Berat Badan Tidak Valid ($\le$ 0)       | Berat badan 0 kg. <br>`json{"gender": 0, "age_months": 12.0, "weight": 0, "height": 75.0}`                            | Error validasi: `Input should be greater than 0` untuk `weight`.                | `422 Unprocessable Entity` |
| **TC-V-05** | Input Tinggi Badan Tidak Valid ($\le$ 0)      | Tinggi badan -10 cm. <br>`json{"gender": 0, "age_months": 12.0, "weight": 8.5, "height": -10.0}`                      | Error validasi: `Input should be greater than 0` untuk `height`.                | `422 Unprocessable Entity` |
| **TC-V-06** | Data Gender Tidak Valid (Bukan 0 atau 1)      | Gender bernilai 2. <br>_(Catatan: Seharusnya divalidasi oleh sistem)_                                                 | Pesan error validasi gender jika diatur strictly, atau sistem melempar respons. | `422 / 500`                |
| **TC-V-07** | Parameter Wajib Hilang                        | Mengirim JSON kosong `{}`.                                                                                            | Pesan error validasi field yang hilang (`field required`).                      | `422 Unprocessable Entity` |

Hasil Pengujian

1. **TC-V-01**

- request body

  ```json
  {
    "gender": 0,
    "age_months": 0.1,
    "weight": 2.5,
    "height": 45.0,
    "gejala_cf": {}
  }
  ```

- response body
  ```json
  {
    "status_kode": 200,
    "pesan": "Analisis sistem pakar hybrid berhasil",
    "kalkulasi_fisik": {
      "bmi": 12.35,
      "status_skrining_ml": "Stunting",
      "probabilitas_ml_murni": 76.71
    },
    "kesimpulan_sistem_pakar": {
      "tingkat_risiko_total_persen": 76.71,
      "gejala_terdeteksi": [],
      "rekomendasi_intervensi": [
        "Terapkan 'Feeding Rules' (Jadwal makan ketat) dari IDAI untuk mengatasi GTM/Anoreksia.",
        "Berikan MPASI padat energi yang kaya akan protein hewani (daging, hati ayam, telur).",
        "Konsultasikan ke kader Posyandu untuk pemberian suplemen Zinc dan Vitamin A."
      ]
    }
  }
  ```

2. **TC-V-02**

- request body

  ```json
  {
    "gender": 0,
    "age_months": 0,
    "weight": 3.0,
    "height": 50.0,
    "gejala_cf": {}
  }
  ```

- response body
  ```json
  {
    "detail": [
      {
        "type": "greater_than",
        "loc": ["body", "age_months"],
        "msg": "Input should be greater than 0",
        "input": 0,
        "ctx": {
          "gt": 0
        }
      }
    ]
  }
  ```

3. **TC-V-03**

- request body

  ```json
  {
    "gender": 1,
    "age_months": -5.0,
    "weight": 4.5,
    "height": 55.0,
    "gejala_cf": {}
  }
  ```

- response body
  ```json
  {
    "detail": [
      {
        "type": "greater_than",
        "loc": ["body", "age_months"],
        "msg": "Input should be greater than 0",
        "input": -5,
        "ctx": {
          "gt": 0
        }
      }
    ]
  }
  ```

4. **TC-V-04**

- request body

  ```json
  {
    "gender": 0,
    "age_months": 12.0,
    "weight": 0,
    "height": 75.0,
    "gejala_cf": {}
  }
  ```

- response body
  ```json
  {
    "detail": [
      {
        "type": "greater_than",
        "loc": ["body", "weight"],
        "msg": "Input should be greater than 0",
        "input": 0,
        "ctx": {
          "gt": 0
        }
      }
    ]
  }
  ```

5. **TC-V-05**

- request body

  ```json
  {
    "gender": 0,
    "age_months": 12.0,
    "weight": 8.5,
    "height": -10.0,
    "gejala_cf": {}
  }
  ```

- response body

  ```json
  {
    "detail": [
      {
        "type": "greater_than",
        "loc": ["body", "height"],
        "msg": "Input should be greater than 0",
        "input": -10,
        "ctx": {
          "gt": 0
        }
      }
    ]
  }
  ```

6. **TC-V-06**

- request body

  ```json
  {
    "gender": 2,
    "age_months": 12.0,
    "weight": 8.5,
    "height": 10.0,
    "gejala_cf": {}
  }
  ```

- response body

  ```json
  {
    "status_kode": 200,
    "pesan": "Analisis sistem pakar hybrid berhasil",
    "kalkulasi_fisik": {
      "bmi": 850,
      "status_skrining_ml": "Stunting",
      "probabilitas_ml_murni": 96
    },
    "kesimpulan_sistem_pakar": {
      "tingkat_risiko_total_persen": 96,
      "gejala_terdeteksi": [],
      "rekomendasi_intervensi": [
        "⚠️ SEGERA RUJUK ke Fasilitas Kesehatan / Puskesmas untuk penanganan gizi buruk akut klinis.",
        "Berikan PMT (Pemberian Makanan Tambahan) Pemulihan di bawah pengawasan medis."
      ]
    }
  }
  ```

7. **TC-V-07**

- request body

  ```json
  {}
  ```

- response body

  ```json
  {
    "detail": [
      {
        "type": "missing",
        "loc": ["body", "gender"],
        "msg": "Field required",
        "input": {}
      },
      {
        "type": "missing",
        "loc": ["body", "age_months"],
        "msg": "Field required",
        "input": {}
      },
      {
        "type": "missing",
        "loc": ["body", "weight"],
        "msg": "Field required",
        "input": {}
      },
      {
        "type": "missing",
        "loc": ["body", "height"],
        "msg": "Field required",
        "input": {}
      }
    ]
  }
  ```

---

### Kategori C: Pengujian Logika Hybrid & Sistem Pakar (Decision Table)

Kategori ini memverifikasi integrasi hasil kalkulasi Machine Learning dengan aturan Certainty Factor dari gejala klinis.

#### Aturan Gejala yang Tersedia (`rules.py`):

- `R03` (Linear Faltering, CF Pakar: 0.80)
- `R04` (Weight Faltering, CF Pakar: 0.70)
- `R05` (Wasted, CF Pakar: 0.75)
- `R06` (Edema Bilateral, CF Pakar: 0.90) — **Medis Akut**
- `R07` (Infeksi Berulang, CF Pakar: 0.60)
- `R08` (BBLR/Prematur, CF Pakar: 0.50)
- `R09` (Red Flags, CF Pakar: 0.70) — **Medis Akut**

---

#### 1. Skenario ML Bersih (Tanpa Gejala Tambahan)

Menguji respons ketika pengguna hanya mengirimkan data fisik tanpa gejala penyerta.

| ID Tes      | Judul Skenario                         | JSON Payload                                                                             | Expected Output Logic                                                                                                                   | Status HTTP |
| :---------- | :------------------------------------- | :--------------------------------------------------------------------------------------- | :-------------------------------------------------------------------------------------------------------------------------------------- | :---------- |
| **TC-H-01** | Balita Sehat (Normal)                  | `json{"gender": 0, "age_months": 24.0, "weight": 12.5, "height": 88.0, "gejala_cf": {}}` | - Status ML: `Normal`<br>- Risiko Total: Berdasarkan probabilitas ML saja<br>- Rekomendasi: _"Pertahankan pola makan gizi seimbang..."_ | `200 OK`    |
| **TC-H-02** | Balita Terindikasi Stunting (Hanya ML) | `json{"gender": 1, "age_months": 24.0, "weight": 7.8, "height": 72.0, "gejala_cf": {}}`  | - Status ML: `Stunting`<br>- Rekomendasi: Mengandung _"Feeding Rules"_, _"MPASI padat energi"_, _"suplemen Zinc"_                       | `200 OK`    |

Hasil Pengujian

1. **TC-H-01**

- request body

  ```json
  {
    "gender": 0,
    "age_months": 24.0,
    "weight": 12.5,
    "height": 88.0,
    "gejala_cf": {}
  }
  ```

- response body
  ```json
  {
    "status_kode": 200,
    "pesan": "Analisis sistem pakar hybrid berhasil",
    "kalkulasi_fisik": {
      "bmi": 16.14,
      "status_skrining_ml": "Normal",
      "probabilitas_ml_murni": 38
    },
    "kesimpulan_sistem_pakar": {
      "tingkat_risiko_total_persen": 38,
      "gejala_terdeteksi": [],
      "rekomendasi_intervensi": [
        "Pertahankan pola makan gizi seimbang dan rutin melakukan kunjungan bulanan ke Posyandu."
      ]
    }
  }
  ```

2. **TC-H-02**

- request body

  ```json
  {
    "gender": 1,
    "age_months": 24.0,
    "weight": 7.8,
    "height": 72.0,
    "gejala_cf": {}
  }
  ```

- response body
  ```json
  {
    "status_kode": 200,
    "pesan": "Analisis sistem pakar hybrid berhasil",
    "kalkulasi_fisik": {
      "bmi": 15.05,
      "status_skrining_ml": "Stunting",
      "probabilitas_ml_murni": 100
    },
    "kesimpulan_sistem_pakar": {
      "tingkat_risiko_total_persen": 100,
      "gejala_terdeteksi": [],
      "rekomendasi_intervensi": [
        "⚠️ SEGERA RUJUK ke Fasilitas Kesehatan / Puskesmas untuk penanganan gizi buruk akut klinis.",
        "Berikan PMT (Pemberian Makanan Tambahan) Pemulihan di bawah pengawasan medis."
      ]
    }
  }
  ```

---

#### 2. Skenario Akumulasi Certainty Factor (CF Kombinasi)

Menguji kebenaran perhitungan matematis penggabungan CF dengan rumus: $CF_{gabungan} = CF_{old} + CF_{new} \times (1 - CF_{old})$.

| ID Tes      | Judul Skenario                                             | JSON Payload                                                                                                  | Expected Output Logic                                                                                                                             | Status HTTP |
| :---------- | :--------------------------------------------------------- | :------------------------------------------------------------------------------------------------------------ | :------------------------------------------------------------------------------------------------------------------------------------------------ | :---------- |
| **TC-H-03** | Satu Gejala Aktif dengan Keyakinan Penuh (CF User = 1.0)   | `json{"gender": 0, "age_months": 12.0, "weight": 9.0, "height": 75.0, "gejala_cf": {"R07": 1.0}}`             | - Gejala terdeteksi: `["R07"]`<br>- CF Gejala = $0.60 \times 1.0 = 0.60$<br>- Risiko Total $\ge$ 60%<br>- Rekomendasi: Intervensi PHBS / Sanitasi | `200 OK`    |
| **TC-H-04** | Satu Gejala Aktif dengan Keyakinan Parsial (CF User = 0.5) | `json{"gender": 0, "age_months": 12.0, "weight": 9.0, "height": 75.0, "gejala_cf": {"R07": 0.5}}`             | - Gejala terdeteksi: `["R07"]`<br>- CF Gejala = $0.60 \times 0.5 = 0.30$<br>- Risiko meningkat sebesar 30% dari dasar ML                          | `200 OK`    |
| **TC-H-05** | Kombinasi Banyak Gejala (Multi-CF)                         | `json{"gender": 0, "age_months": 18.0, "weight": 8.0, "height": 76.0, "gejala_cf": {"R04": 0.8, "R07": 0.5}}` | - Gejala terdeteksi: `["R04", "R07"]`<br>- Penghitungan CF kombinasi berjalan tanpa error dan menghasilkan nilai di kisaran $[0.0, 100.0]$        | `200 OK`    |

Hasil Pengujian

3. **TC-H-03**

- request body

  ```json
  {
    "gender": 0,
    "age_months": 12.0,
    "weight": 9.0,
    "height": 75.0,
    "gejala_cf": { "R07": 1.0 }
  }
  ```

- response body
  ```json
  {
    "status_kode": 200,
    "pesan": "Analisis sistem pakar hybrid berhasil",
    "kalkulasi_fisik": {
      "bmi": 16,
      "status_skrining_ml": "Normal",
      "probabilitas_ml_murni": 0
    },
    "kesimpulan_sistem_pakar": {
      "tingkat_risiko_total_persen": 60,
      "gejala_terdeteksi": ["R07"],
      "rekomendasi_intervensi": [
        "Edukasi orang tua mengenai PHBS (Perilaku Hidup Bersih dan Sehat) untuk memutus rantai infeksi.",
        "Periksa kelayakan kualitas air bersih dan sanitasi di lingkungan tempat tinggal."
      ]
    }
  }
  ```

4. **TC-H-04**

- request body

  ```json
  {
    "gender": 0,
    "age_months": 12.0,
    "weight": 9.0,
    "height": 75.0,
    "gejala_cf": { "R07": 0.5 }
  }
  ```

- response body
  ```json
  {
    "status_kode": 200,
    "pesan": "Analisis sistem pakar hybrid berhasil",
    "kalkulasi_fisik": {
      "bmi": 16,
      "status_skrining_ml": "Normal",
      "probabilitas_ml_murni": 0
    },
    "kesimpulan_sistem_pakar": {
      "tingkat_risiko_total_persen": 30,
      "gejala_terdeteksi": ["R07"],
      "rekomendasi_intervensi": [
        "Edukasi orang tua mengenai PHBS (Perilaku Hidup Bersih dan Sehat) untuk memutus rantai infeksi.",
        "Periksa kelayakan kualitas air bersih dan sanitasi di lingkungan tempat tinggal."
      ]
    }
  }
  ```

5. **TC-H-05**

- request body

  ```json
  {
    "gender": 0,
    "age_months": 18.0,
    "weight": 8.0,
    "height": 76.0,
    "gejala_cf": { "R04": 0.8, "R07": 0.5 }
  }
  ```

- response body
  ```json
  {
    "status_kode": 200,
    "pesan": "Analisis sistem pakar hybrid berhasil",
    "kalkulasi_fisik": {
      "bmi": 13.85,
      "status_skrining_ml": "Stunting",
      "probabilitas_ml_murni": 68
    },
    "kesimpulan_sistem_pakar": {
      "tingkat_risiko_total_persen": 90.14,
      "gejala_terdeteksi": ["R04", "R07"],
      "rekomendasi_intervensi": [
        "⚠️ SEGERA RUJUK ke Fasilitas Kesehatan / Puskesmas untuk penanganan gizi buruk akut klinis.",
        "Berikan PMT (Pemberian Makanan Tambahan) Pemulihan di bawah pengawasan medis."
      ]
    }
  }
  ```

---

#### 3. Skenario Rujukan Medis Akut (Jalur Kritis)

Menguji kasus-kasus bahaya yang memerlukan rujukan darurat ke Puskesmas/Rumah Sakit.

| ID Tes      | Judul Skenario                                          | JSON Payload                                                                                                              | Expected Output Logic                                                                                                                       | Status HTTP |
| :---------- | :------------------------------------------------------ | :------------------------------------------------------------------------------------------------------------------------ | :------------------------------------------------------------------------------------------------------------------------------------------ | :---------- |
| **TC-H-06** | Adanya Gejala Edema Bilateral (`R06`)                   | `json{"gender": 1, "age_months": 15.0, "weight": 8.0, "height": 74.0, "gejala_cf": {"R06": 1.0}}`                         | - Gejala terdeteksi: `["R06"]`<br>- Rekomendasi mengandung: _"⚠️ SEGERA RUJUK ke Fasilitas Kesehatan / Puskesmas..."_ dan _"PMT Pemulihan"_ | `200 OK`    |
| **TC-H-07** | Adanya Gejala Red-Flags Sistemik (`R09`)                | `json{"gender": 0, "age_months": 20.0, "weight": 9.0, "height": 78.0, "gejala_cf": {"R09": 0.9}}`                         | - Gejala terdeteksi: `["R09"]`<br>- Rekomendasi mengandung: _"⚠️ SEGERA RUJUK..."_                                                          | `200 OK`    |
| **TC-H-08** | Akumulasi Tingkat Risiko Total Sangat Tinggi ($> 85\%$) | `json{"gender": 0, "age_months": 24.0, "weight": 7.0, "height": 70.0, "gejala_cf": {"R03": 0.9, "R04": 0.9, "R05": 0.9}}` | - Nilai `tingkat_risiko_total_persen` $> 85.0$<br>- Rekomendasi otomatis diarahkan ke Rujukan Darurat                                       | `200 OK`    |

Hasil Pengujian

6. **TC-H-06**

- request body

  ```json
  {
    "gender": 1,
    "age_months": 15.0,
    "weight": 8.0,
    "height": 74.0,
    "gejala_cf": { "R06": 1.0 }
  }
  ```

- response body
  ```json
  {
    "status_kode": 200,
    "pesan": "Analisis sistem pakar hybrid berhasil",
    "kalkulasi_fisik": {
      "bmi": 14.61,
      "status_skrining_ml": "Normal",
      "probabilitas_ml_murni": 38.83
    },
    "kesimpulan_sistem_pakar": {
      "tingkat_risiko_total_persen": 93.88,
      "gejala_terdeteksi": ["R06"],
      "rekomendasi_intervensi": [
        "⚠️ SEGERA RUJUK ke Fasilitas Kesehatan / Puskesmas untuk penanganan gizi buruk akut klinis.",
        "Berikan PMT (Pemberian Makanan Tambahan) Pemulihan di bawah pengawasan medis."
      ]
    }
  }
  ```

7. **TC-H-07**

- request body

  ```json
  {
    "gender": 0,
    "age_months": 20.0,
    "weight": 9.0,
    "height": 78.0,
    "gejala_cf": { "R09": 0.9 }
  }
  ```

- response body
  ```json
  {
    "status_kode": 200,
    "pesan": "Analisis sistem pakar hybrid berhasil",
    "kalkulasi_fisik": {
      "bmi": 14.79,
      "status_skrining_ml": "Stunting",
      "probabilitas_ml_murni": 98
    },
    "kesimpulan_sistem_pakar": {
      "tingkat_risiko_total_persen": 99.26,
      "gejala_terdeteksi": ["R09"],
      "rekomendasi_intervensi": [
        "⚠️ SEGERA RUJUK ke Fasilitas Kesehatan / Puskesmas untuk penanganan gizi buruk akut klinis.",
        "Berikan PMT (Pemberian Makanan Tambahan) Pemulihan di bawah pengawasan medis."
      ]
    }
  }
  ```

8. **TC-H-08**

- request body

  ```json
  {
    "gender": 0,
    "age_months": 24.0,
    "weight": 7.0,
    "height": 70.0,
    "gejala_cf": { "R03": 0.9, "R04": 0.9, "R05": 0.9 }
  }
  ```

- response body
  ```json
  {
    "status_kode": 200,
    "pesan": "Analisis sistem pakar hybrid berhasil",
    "kalkulasi_fisik": {
      "bmi": 14.29,
      "status_skrining_ml": "Stunting",
      "probabilitas_ml_murni": 100
    },
    "kesimpulan_sistem_pakar": {
      "tingkat_risiko_total_persen": 100,
      "gejala_terdeteksi": ["R03", "R04", "R05"],
      "rekomendasi_intervensi": [
        "⚠️ SEGERA RUJUK ke Fasilitas Kesehatan / Puskesmas untuk penanganan gizi buruk akut klinis.",
        "Berikan PMT (Pemberian Makanan Tambahan) Pemulihan di bawah pengawasan medis."
      ]
    }
  }
  ```

---

#### 4. Skenario Penyesuaian Rekomendasi Spesifik

Menguji apakah sistem pakar memberikan rekomendasi yang sesuai dengan riwayat spesifik balita.

| ID Tes      | Judul Skenario                                | JSON Payload                                                                                     | Expected Output Logic                                                                                                                           | Status HTTP |
| :---------- | :-------------------------------------------- | :----------------------------------------------------------------------------------------------- | :---------------------------------------------------------------------------------------------------------------------------------------------- | :---------- |
| **TC-H-09** | Balita dengan Riwayat BBLR / Prematur (`R08`) | `json{"gender": 0, "age_months": 6.0, "weight": 6.5, "height": 62.0, "gejala_cf": {"R08": 0.8}}` | Rekomendasi harus mencakup: _"Lakukan pemantauan tumbuh kembang secara lebih intensif (minimal 2 minggu sekali) karena riwayat BBLR/Prematur."_ | `200 OK`    |

Hasil pengujian

9. **TC-H-09**

- request body

  ```json
  {
    "gender": 0,
    "age_months": 6.0,
    "weight": 6.5,
    "height": 62.0,
    "gejala_cf": { "R08": 0.8 }
  }
  ```

- response body
  ```json
  {
    "status_kode": 200,
    "pesan": "Analisis sistem pakar hybrid berhasil",
    "kalkulasi_fisik": {
      "bmi": 16.91,
      "status_skrining_ml": "Stunting",
      "probabilitas_ml_murni": 100
    },
    "kesimpulan_sistem_pakar": {
      "tingkat_risiko_total_persen": 100,
      "gejala_terdeteksi": ["R08"],
      "rekomendasi_intervensi": [
        "⚠️ SEGERA RUJUK ke Fasilitas Kesehatan / Puskesmas untuk penanganan gizi buruk akut klinis.",
        "Berikan PMT (Pemberian Makanan Tambahan) Pemulihan di bawah pengawasan medis."
      ]
    }
  }
  ```

---

## 4. Contoh Payload Uji (Untuk Postman atau cURL)

### A. Uji Kasus Normal (TC-H-01)

**Request:**

```bash
curl -X POST "http://127.0.0.1:8001/predict" \
     -H "Content-Type: application/json" \
     -d '{"gender": 0, "age_months": 24.0, "weight": 12.5, "height": 88.0, "gejala_cf": {}}'
```

**Response (Sukses):**

```json
{
  "status_kode": 200,
  "pesan": "Analisis sistem pakar hybrid berhasil",
  "kalkulasi_fisik": {
    "bmi": 16.13,
    "status_skrining_ml": "Normal",
    "probabilitas_ml_murni": 12.45
  },
  "kesimpulan_sistem_pakar": {
    "tingkat_risiko_total_persen": 12.45,
    "gejala_terdeteksi": [],
    "rekomendasi_intervensi": [
      "Pertahankan pola makan gizi seimbang dan rutin melakukan kunjungan bulanan ke Posyandu."
    ]
  }
}
```

Hail Pengujian

```bash
$ curl -X POST "http://127.0.0.1:8001/predict" \
     -H "Content-Type: application/json" \
     -d '{"gender": 0, "age_months": 24.0, "weight": 12.5, "height": 88.0, "gejala_cf": {}}'
{"status_kode":200,"pesan":"Analisis sistem pakar hybrid berhasil","kalkulasi_fisik":{"bmi":16.14,"status_skrining_ml":"Normal","probabilitas_ml_murni":38.0},"kesimpulan_sistem_pakar":{"tingkat_risiko_total_persen":38.0,"gejala_terdeteksi":[],"rekomendasi_intervensi":["Pertahankan pola makan gizi seimbang dan rutin melakukan kunjungan bulanan ke Posyandu."]}}
```

### B. Uji Rujukan Akut (TC-H-06)

**Request:**

```bash
curl -X POST "http://127.0.0.1:8001/predict" \
     -H "Content-Type: application/json" \
     -d '{"gender": 1, "age_months": 15.0, "weight": 8.0, "height": 74.0, "gejala_cf": {"R06": 1.0}}'
```

**Response (Sukses dengan Rujukan):**

```json
{
  "status_kode": 200,
  "pesan": "Analisis sistem pakar hybrid berhasil",
  "kalkulasi_fisik": {
    "bmi": 14.61,
    "status_skrining_ml": "Normal",
    "probabilitas_ml_murni": 15.3
  },
  "kesimpulan_sistem_pakar": {
    "tingkat_risiko_total_persen": 91.53,
    "gejala_terdeteksi": ["R06"],
    "rekomendasi_intervensi": [
      "⚠️ SEGERA RUJUK ke Fasilitas Kesehatan / Puskesmas untuk penanganan gizi buruk akut klinis.",
      "Berikan PMT (Pemberian Makanan Tambahan) Pemulihan di bawah pengawasan medis."
    ]
  }
}
```

Hasil pengujian

```bash
$ curl -X POST "http://127.0.0.1:8001/predict" \
     -H "Content-Type: application/json" \
     -d '{"gender": 1, "age_months": 15.0, "weight": 8.0, "height": 74.0, "gejala_cf": {"R06": 1.0}}'
{"status_kode":200,"pesan":"Analisis sistem pakar hybrid berhasil","kalkulasi_fisik":{"bmi":14.61,"status_skrining_ml":"Normal","probabilitas_ml_murni":38.83},"kesimpulan_sistem_pakar":{"tingkat_risiko_total_persen":93.88,"gejala_terdeteksi":["R06"],"rekomendasi_intervensi":["⚠️ SEGERA RUJUK ke Fasilitas Kesehatan / Puskesmas untuk penanganan gizi buruk akut klinis.","Berikan PMT (Pemberian Makanan Tambahan) Pemulihan di bawah pengawasan medis."]}}
```

### C. Uji Error Validasi (TC-V-02)

**Request:**

```bash
curl -X POST "http://127.0.0.1:8001/predict" \
     -H "Content-Type: application/json" \
     -d '{"gender": 0, "age_months": 0, "weight": 3.0, "height": 50.0}'
```

**Response (Error):**

```json
{
  "detail": [
    {
      "type": "greater_than",
      "loc": ["body", "age_months"],
      "msg": "Input should be greater than 0",
      "input": 0.0,
      "ctx": {
        "gt": 0.0
      }
    }
  ]
}
```

Hasil pengujian

```bash
$ curl -X POST "http://127.0.0.1:8001/predict" \
     -H "Content-Type: application/json" \
     -d '{"gender": 0, "age_months": 0, "weight": 3.0, "height": 50.0}'
{"detail":[{"type":"greater_than","loc":["body","age_months"],"msg":"Input should be greater than 0","input":0,"ctx":{"gt":0.0}}]}
```

---

## 5. Cara Eksekusi Pengujian

Untuk menjalankan skenario pengujian di atas, tim penguji dapat menggunakan:

1. **Postman**: Buat Collection baru dan masukkan payload JSON sesuai tabel pengujian.
2. **Swagger UI**: Buka browser ke `http://127.0.0.1:8001/docs` lalu gunakan antarmuka interaktif yang disediakan FastAPI untuk mencoba skenario di atas.
3. **Automated Testing (Pytest)**: Buat file tes Python (misal `test_main.py`) menggunakan `TestClient` bawaan dari FastAPI untuk memvalidasi secara otomatis setiap kali ada perubahan kode.
