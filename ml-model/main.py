# main.py
from fastapi import FastAPI, HTTPException
from pydantic import BaseModel, Field
from typing import Literal
import joblib
import numpy as np
import rules # Mengimpor file rules.py yang kita buat di atas

app = FastAPI(
    title="API Sistem Pakar Hybrid Stunting",
    description="Microservice AI: Menggabungkan Random Forest dan Certainty Factor.",
    version="1.1.0"
)

# Memuat model ML
try:
    model = joblib.load('model_rf_stunting_terbaik.pkl')
    scaler = joblib.load('scaler_stunting.pkl')
    print("Model & Scaler ML berhasil dimuat!")
except Exception as e:
    print(f"Error memuat file .pkl: {e}")

# Buku Menu Input Baru (Mendukung Hybrid System)
class SkenarioKonsultasi(BaseModel):
    # Data Fisik (Untuk Machine Learning)
    gender: Literal[0, 1] = Field(..., description="0: Laki-laki, 1: Perempuan")
    age_months: float = Field(..., gt=0)
    weight: float = Field(..., gt=0)
    height: float = Field(..., gt=0)
    
    # Data Gejala & Riwayat (Untuk Sistem Pakar Certainty Factor)
    # Nilai diinput dalam skala 0.0 (Tidak Ada) sampai 1.0 (Sangat Yakin/Pasti)
    # Laravel akan mengirimkan nilai ini berdasarkan form kuesioner
    gejala_cf: dict = Field(
        default={}, 
        description="Contoh: {'R04': 1.0, 'R07': 0.6}. Isikan Key Rule ID dan Nilai Keyakinan User."
    )

@app.post("/predict")
def prediksi_hybrid_stunting(data: SkenarioKonsultasi):
    try:
        # === TAHAP 1: EKSEKUSI MACHINE LEARNING ===
        tinggi_meter = data.height / 100
        bmi = round(data.weight / (tinggi_meter ** 2), 2)
        
        fitur_mentah = np.array([[data.gender, data.age_months, data.weight, data.height, bmi]])
        fitur_scaled = scaler.transform(fitur_mentah)
        
        prediksi_kelas = model.predict(fitur_scaled)[0]
        probabilitas = model.predict_proba(fitur_scaled)[0]
        
        status_ml = "Stunting" if prediksi_kelas == 1 else "Normal"
        cf_ml_awal = probabilitas[1] # Nilai probabilitas kelas stunting (0.0 - 1.0)

        # === TAHAP 2: EKSEKUSI SISTEM PAKAR (FORWARD CHAINING & CF) ===
        # Hitung akumulasi keyakinan akhir menggabungkan ML + Gejala Luar
        persentase_kepastian_total = rules.hitung_cf_kombinasi(data.gejala_cf, cf_ml_awal)
        
        # Ambil daftar rule yang aktif (diisi oleh user) untuk penentu keputusan rekomendasi
        daftar_rule_aktif = [rule_id for rule_id, nilai in data.gejala_cf.items() if nilai > 0]
        
        # Dapatkan rekomendasi gizi yang spesifik dan valid berdasarkan hasil inferensi
        rekomendasi_final = rules.dapatkan_rekomendasi(status_ml, persentase_kepastian_total, daftar_rule_aktif)

        # === TAHAP 3: KEMBALIKAN HASIL KE LARAVEL ===
        return {
            "status_kode": 200,
            "pesan": "Analisis sistem pakar hybrid berhasil",
            "kalkulasi_fisik": {
                "bmi": bmi,
                "status_skrining_ml": status_ml,
                "probabilitas_ml_murni": round(cf_ml_awal * 100, 2)
            },
            "kesimpulan_sistem_pakar": {
                "tingkat_risiko_total_persen": persentase_kepastian_total,
                "gejala_terdeteksi": daftar_rule_aktif,
                "rekomendasi_intervensi": rekomendasi_final
            }
        }

    except Exception as e:
        raise HTTPException(status_code=500, detail=str(e))

@app.get("/")
def health_check():
    return {"status": "Server Hybrid AI Aktif!"}