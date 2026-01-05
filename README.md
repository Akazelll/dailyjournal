# Pengembangan Fitur AI pada Web Daily Journal — Generate Images (SDXL)

> Fitur ini menambahkan kemampuan **generate gambar ilustrasi** dari **Judul Artikel** menggunakan AI (Stable Diffusion XL via Hugging Face), lengkap dengan **penerjemahan judul (EN)** dan **prompt engineering**.

---

## Daftar Isi
- [Ringkasan](#ringkasan)
- [Tujuan](#tujuan)
- [Arsitektur Singkat](#arsitektur-singkat)
- [Teknologi / Library](#teknologi--library)
- [Struktur Modul & File](#struktur-modul--file)
- [Prompt Engineering](#prompt-engineering)
- [User Guide (Panduan Penggunaan)](#user-guide-panduan-penggunaan)
- [Setup & Konfigurasi](#setup--konfigurasi)
- [Error Handling & Catatan Keamanan](#error-handling--catatan-keamanan)
- [Checklist Presentasi](#checklist-presentasi)
- [Catatan](#catatan)

---

## Ringkasan
Pada menu **Article**, admin biasanya membutuhkan gambar ilustrasi/thumbnail yang relevan. Namun sering terkendala:
- tidak punya stok gambar,
- tidak sempat mencari gambar manual,
- gambar yang ditemukan tidak konsisten gaya/tema.

Solusi: tombol **Generate Images** yang akan menghasilkan ilustrasi otomatis dari **judul artikel** dan menyimpan hasilnya ke folder `img/`.

---

## Tujuan
1. Mempermudah admin membuat artikel tanpa harus mencari asset gambar manual.
2. Mempercepat workflow penulisan artikel/jurnal.
3. Menunjukkan integrasi AI yang nyata: **API call**, **prompt engineering**, **handling output**, dan **penyimpanan file**.

---

## Arsitektur Singkat
Fitur ini memakai alur request–response berbasis **AJAX**:

**Flow data:**
1. Admin mengisi **Judul Artikel**
2. Klik **Generate Images**
3. Browser kirim **AJAX** ke `ajax_ai.php`
4. `ajax_ai.php` memanggil `ai_image.php`
5. `ai_image.php` melakukan:
   - Translate judul → **Google Translate API** (EN)
   - Susun **Enhanced Prompt**
   - Generate gambar → **Hugging Face Inference API** (model `stabilityai/stable-diffusion-xl-base-1.0`)
6. API mengembalikan hasil gambar (base64/binary)
7. Server menyimpan file gambar ke folder `img/`
8. Server mengembalikan URL gambar → browser menampilkan preview
9. Admin klik **Simpan**

**Diagram ringkas:**
```text
[Admin Input Judul]
        |
        v
   (AJAX Request)
        |
        v
     ajax_ai.php
        |
        v
     ai_image.php
   /             \
  v               v
Translate API   HuggingFace Inference API (SDXL)
  |               |
  v               v
English Title   Base64/Image Result
        \        /
         v      v
      Save to /img/
         |
         v
  Return URL + Preview
```

---

## Teknologi / Library
### Backend
- **PHP** (endpoint + logic pipeline AI)
- **cURL** (HTTP request ke API eksternal)

### Frontend
- **Bootstrap** (UI form + tombol + komponen preview + loading)
- **AJAX (JavaScript)** (request tanpa reload halaman)

### AI / Model
- **Hugging Face Inference API**
- Model: `stabilityai/stable-diffusion-xl-base-1.0` (SDXL)

---

## Struktur Modul & File
### `ajax_ai.php` — Endpoint/Controller
Tugas utama:
- menerima request dari frontend (judul artikel)
- validasi input
- memanggil `ai_image.php`
- mengembalikan response JSON ke frontend

Contoh format response yang disarankan:
```json
{
  "status": "success",
  "image_url": "img/ai_1700000000.png",
  "prompt_used": "..."
}
```

### `ai_image.php` — AI Pipeline
Tugas utama:
- `translateToEnglish($title)` → judul ke bahasa Inggris
- `buildEnhancedPrompt($englishTitle)` → susun prompt berkualitas tinggi
- request ke HF Inference API
- simpan output ke `img/`
- return URL/path gambar

---

## Prompt Engineering
### Kenapa Enhanced Prompt?
Sistem **tidak mengirim judul mentah** ke model. Judul terlebih dahulu:
1. diterjemahkan ke bahasa Inggris (umumnya lebih optimal untuk model SDXL),
2. diperkaya dengan parameter kualitas (render, lighting, kamera, detail).

Hasilnya:
- output lebih konsisten,
- lebih realistis,
- lebih “layak thumbnail artikel”.

### Pola Enhanced Prompt (Contoh)
- **Subject**: hasil translate judul
- **Quality Enhancer**: “Ultra-realistic Unreal Engine 5 cinematic render...”
- **Detail Render**: PBR, micro-details, ray tracing, GI, AO
- **Camera**: 35mm lens, f/2.8, DOF
- **Constraint**: “no cartoon/anime, no artifacts, clean”

Contoh gaya prompt (ringkas):
```text
Ultra-realistic Unreal Engine 5 cinematic render of {englishTitle}.
PBR materials, ray-traced reflections, volumetric lighting, shallow depth of field,
8k, sharp focus, professional color grading, clean, no artifacts, no cartoon, no anime.
```

---

## User Guide (Panduan Penggunaan)
> Tambahkan screenshot di bagian yang ditandai `TODO: Screenshot`.

### 1) Login sebagai Admin
1. Buka halaman login
2. Masukkan username & password admin
3. Klik **Login**

✅ TODO: Screenshot 1 — Halaman Login

### 2) Buka Menu Article
1. Dari dashboard, pilih menu **Article**
2. Klik tombol **Tambah Article**

✅ TODO: Screenshot 2 — Menu Article & tombol Tambah

### 3) Isi Judul Artikel
1. Isi field **Judul Artikel**
2. (Opsional) isi isi artikel/konten

✅ TODO: Screenshot 3 — Form Tambah Article (judul terisi)

### 4) Generate Images
1. Klik tombol **Generate Images**
2. Sistem menampilkan **loading** karena memanggil API:
   - Translate API
   - Hugging Face Inference API
3. Tunggu sampai preview gambar muncul

✅ TODO: Screenshot 4 — Loading state  
✅ TODO: Screenshot 5 — Preview hasil generate

### 5) Simpan
1. Jika gambar sudah sesuai, klik **Simpan**
2. Sistem menyimpan gambar ke folder `img/` dan mengaitkan ke artikel

✅ TODO: Screenshot 6 — Artikel tersimpan + thumbnail tampil

---

## Setup & Konfigurasi
### Prasyarat
- PHP (disarankan 7.4+ / 8.x)
- Akses internet (karena butuh API eksternal)
- (Opsional) Web server: Apache/Nginx/Laragon/XAMPP

### Konfigurasi API Key (Disarankan via ENV / Config)
**Jangan hardcode API key di repo publik.**  
Simpan pada `.env` atau file config yang tidak ikut di-commit.

Contoh variabel:
- `GOOGLE_TRANSLATE_API_KEY=...`
- `HUGGINGFACE_API_TOKEN=...`

> Jika belum pakai `.env`, minimal simpan di `config.php` lalu exclude dari git.

---

## Error Handling & Catatan Keamanan
### Validasi Input
- Judul tidak boleh kosong
- batas panjang judul (contoh: 5–120 char) untuk mencegah prompt terlalu panjang

### Timeout & Retry
- Set `CURLOPT_TIMEOUT` agar request tidak menggantung
- (Opsional) retry 1x jika error transient

### Fallback
- Jika translate gagal → gunakan judul asli
- Jika generate gagal → tampilkan pesan error yang jelas ke user

### Keamanan
- API key **wajib** disimpan aman (ENV/config, bukan hardcode)
- Batasi frekuensi generate (rate limit sederhana) untuk menghindari abuse
- Pastikan nama file output aman (hindari path traversal)

---

## Checklist Presentasi
### Konsep
- Masalah: admin butuh gambar untuk artikel, tapi tidak punya asset/skill desain
- Solusi: generate gambar otomatis dari judul, langsung bisa jadi thumbnail

### Demo
- Pastikan koneksi stabil
- Siapkan 2–3 judul cadangan untuk demo:
  - “Refleksi Kedisiplinan di Semester Baru”
  - “Malam Hujan dan Overthinking”
  - “Belajar Konsisten: 30 Hari Menulis”

### Penjelasan Kode (yang sering ditanya dosen)
- `translateToEnglish()`:
  - alasan: SDXL cenderung lebih akurat dengan prompt berbahasa Inggris
- `buildEnhancedPrompt()`:
  - alasan: nilai tambah prompt engineering (bukan judul mentah)
- saving output:
  - base64/binary → file di `img/` → return URL → preview

---

## Catatan
Jika ingin meningkatkan kualitas penilaian, kamu bisa menambahkan:
- log request (tanpa menyimpan token) untuk debugging,
- pilihan gaya (realistic / watercolor / minimalist),
- tombol “regenerate” untuk variasi gambar.

---
