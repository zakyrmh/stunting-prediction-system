from fastapi import FastAPI, HTTPException
from pydantic import BaseModel, Field
import joblib
import numpy as np

# Menjalankan server
# uvicorn main:app --reload

# 1. Inisialisasi Aplikasi FastAPI
app = FastAPI(
    title="API Deteksi Stunting (Microservice AI)",
    description="Melayani prediksi risiko stunting menggunakan Random Forest.",
    version="1.0.0"
)

# 2. Memuat "Otak" AI (Model & Scaler) saat server pertama kali menyala
try:
    model = joblib.load('model_rf_stunting_terbaik.pkl')
    scaler = joblib.load('scaler_stunting.pkl')
    print("Model dan Scaler berhasil dimuat ke dalam memori!")
except Exception as e:
    print(f"Error memuat file .pkl: {e}")

# 3. Membuat "Buku Menu" (Pydantic Schema)
# Ini memaksa Frontend (Laravel/React) untuk mengirim data dengan format dan tipe yang benar
class DataBalita(BaseModel):
    gender: int = Field(..., description="0 untuk Laki-laki, 1 untuk Perempuan")
    age_months: float = Field(..., gt=0, description="Umur dalam bulan (harus lebih dari 0)")
    weight: float = Field(..., gt=0, description="Berat badan dalam kg")
    height: float = Field(..., gt=0, description="Tinggi badan dalam cm")

# 4. Membuat Endpoint (Pintu Masuk Pesanan)
@app.post("/predict")
def prediksi_stunting(data: DataBalita):
    try:
        # A. Feature Engineering (Menghitung BMI secara on-the-fly)
        # Kenapa di sini? Agar Web Frontend tidak perlu repot menghitung BMI.
        tinggi_meter = data.height / 100
        bmi = round(data.weight / (tinggi_meter ** 2), 2)

        # B. Menyusun Array Fitur Sesuai Urutan Pelatihan: 
        # ['Gender', 'Age (Month)', 'Weight', 'Height', 'BMI']
        fitur_mentah = np.array([[data.gender, data.age_months, data.weight, data.height, bmi]])

        # C. Feature Scaling (Mengecilkan angka dengan Scaler bawaan)
        fitur_scaled = scaler.transform(fitur_mentah)

        # D. Eksekusi Prediksi
        prediksi_kelas = model.predict(fitur_scaled)[0]
        # Mengambil probabilitas persentase (predict_proba)
        probabilitas = model.predict_proba(fitur_scaled)[0]

        # E. Menerjemahkan Hasil untuk Dikirim Kembali ke Frontend (JSON)
        status_teks = "Stunting" if prediksi_kelas == 1 else "Normal"
        risiko_persen = round(probabilitas[1] * 100, 2) # Mengambil probabilitas kelas 1 (Stunting)

        return {
            "status_kode": 200,
            "pesan": "Prediksi berhasil",
            "data_input": data.dict(),
            "bmi_kalkulasi": bmi,
            "hasil_prediksi": status_teks,
            "risiko_stunting_persen": risiko_persen
        }

    except Exception as e:
        # Jika ada error, kembalikan pesan error HTTP 500
        raise HTTPException(status_code=500, detail=str(e))

# 5. Endpoint sederhana untuk cek status server (Health Check)
@app.get("/")
def read_root():
    return {"status": "Server AI Aktif!", "pesan": "Gunakan endpoint /predict untuk memprediksi."}