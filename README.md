# 📝 Web Daily Journal — AI Image Generator (FLUX.1 Schnell)

Aplikasi **Web Daily Journal** berbasis **PHP Native** yang memungkinkan admin membuat artikel + **menghasilkan ilustrasi gambar otomatis** menggunakan **Generative AI**.  
Proyek ini dibuat untuk memenuhi tugas **Proyek Akhir / UAS Mata Kuliah Pemrograman Berbasis Web**.

---

## ✨ Highlight
- ✅ **Auto Generate Image (Opsi 5)** dari konten artikel
- ✅ Model **black-forest-labs/FLUX.1-schnell** via **Hugging Face Inference API**
- ✅ Output **Multi-Format**: **WebP / JPG / PNG / GIF**
- ✅ **Prompt engineering otomatis** (translate → enrich → generate)
- ✅ **AJAX async** (tidak ganggu alur nulis artikel)
- ✅ Simpan gambar AI ke **server lokal** (`img/`)

---

## 👥 Anggota Kelompok 5 (Kelas A11.43UG1)

| Nama                           | NIM              |
|--------------------------------|------------------|
| **Adam Raga**                  | `A11.2024.15598` |
| **Affan Shahzada**             | `A11.2024.15784` |
| **Aiska Zahra Nailani**        | `A11.2024.16014` |
| **Nur Alif Maulana Syafrudin** | `A11.2024.15936` |

---

## 🤖 Fitur Utama: Auto Generate Image (Opsi 5)

Fitur ini memungkinkan admin menghasilkan gambar ilustrasi secara otomatis berdasarkan isi konten artikel. Cocok buat penulis yang ingin visual instan tanpa cari gambar manual.

### Keunggulan
- **Model AI Mutakhir**  
  Menggunakan `black-forest-labs/FLUX.1-schnell` (fotorealistik, latensi rendah).
- **Multi-Format Output**  
  Admin bisa pilih output: WebP/JPG/PNG/GIF.
- **Prompt Engineering Otomatis**  
  Konten diterjemahkan ke English lalu diperkaya parameter visual seperti:  
  `photorealistic`, `8k`, `cinematic lighting`, `sharp focus`, dll.
- **AJAX Async Generation**  
  Generate berjalan asinkron sehingga UI tetap responsif.

---

## 🧱 Arsitektur & Teknologi

### Arsitektur (Monolithic Client–Server)
- **Client-Side:** HTML5, Bootstrap 5, jQuery (AJAX)
- **Server-Side:** PHP Native (logika aplikasi & pemrosesan gambar)
- **External API:**
  - **Hugging Face Inference API** (Text-to-Image)
  - **Google Translate API** (preprocessing prompt Bahasa Indonesia → English)

### Library / Komponen Teknis
- **cURL**: request ke API eksternal
- **PHP GD Library**: decode stream gambar + konversi format
- **Custom Env Loader**: load `.env` tanpa dependensi tambahan

---

## 🔁 Alur Generate Gambar (Flow)

```mermaid
flowchart TD
A[Admin tulis artikel] --> B[Ambil 300 karakter awal / isi konten]
B --> C[Translate ID -> EN]
C --> D[Prompt Enrichment: photorealistic, 8k, cinematic]
D --> E[Request ke HF Inference API (FLUX.1 Schnell)]
E --> F[Terima image binary stream]
F --> G[Konversi format (GD): webp/jpg/png/gif]
G --> H[Simpan lokal ke folder img/]
H --> I[Preview ditampilkan ke admin]
````

---

## 📦 Instalasi & Konfigurasi

### 1) Clone Repository

```bash
git clone https://github.com/Akazelll/dailyjournal
cd dailyjournal
```

### 2) Setup Database

1. Buat database (contoh: `latihan_pbw`)
2. Import file SQL (jika tersedia) / jalankan struktur tabel dari repo
3. Sesuaikan koneksi pada `koneksi.php`

> Pastikan credential DB benar dan database server aktif.

### 3) Konfigurasi Environment (.env)

1. Salin `.env.example` menjadi `.env`
2. Isi token Hugging Face:

```env
HF_TOKEN=your_huggingface_token_here
```

3. (Jika digunakan) set konfigurasi translate:

```env
GOOGLE_TRANSLATE_KEY=your_key_here
```

> **Cara ambil HF Token:**
> Hugging Face → Settings → Access Tokens → Generate token → copy.

### 4) Izin Folder (Wajib)

Pastikan folder `img/` **bisa ditulis** agar gambar AI dapat disimpan.

* Windows (XAMPP): pastikan permission folder tidak read-only
* Linux:

```bash
chmod -R 775 img
```

---

## ▶️ Cara Menjalankan

* Jalankan via XAMPP/Laragon (Apache + MySQL)
* Akses project dari browser (contoh):

  * `http://localhost/dailyjournal/`

---

## 📖 Panduan Penggunaan (User Guide)

### Login Admin

* Akses: `login.php`
* Default akun:

  * **username:** `admin`
  * **password:** `123456`

> Disarankan mengganti default password sebelum demo/presentasi.

### Membuat Artikel + Generate Gambar

1. Login sebagai admin
2. Masuk menu **Article**
3. Klik **Tambah Article**
4. Isi **Judul** dan **Konten**

   * Minimal 10 karakter (disarankan konten lebih deskriptif)
5. Klik **Generate Image**

   * Pilih format output: **WebP / JPG / PNG / GIF**
6. Tunggu proses hingga selesai (± 10–20 detik)
7. Jika preview cocok → klik **Simpan Artikel**

   * Gambar otomatis disimpan di server lokal (`img/`)

---

## 💡 Tips Biar Hasil AI Lebih Akurat

* Fokus pada **300 karakter awal** konten artikel: buat sejelas mungkin (siapa, apa, tempat, suasana, objek)
* Tambahkan detail visual: waktu (pagi/malam), lokasi (pantai/kota/hutan), mood (dramatic/calm)
* **WebP** direkomendasikan karena lebih ringan untuk web

---

## 🧪 Catatan Teknis (Developer Notes)

### Output Format

Sistem mendukung:

* WebP (recommended)
* JPG
* PNG
* GIF

Konversi dilakukan di server menggunakan **PHP GD Library**.

### Error Handling (Ringkas)

Beberapa kondisi yang ditangani:

* Token HF kosong / invalid → tampilkan pesan error
* Response API gagal / timeout → fallback pesan & retry manual
* Format tidak valid → default ke format aman (misal PNG)
* Folder `img/` tidak writable → tampilkan error permission

---

## 🔐 Security Notes

* Jangan commit file `.env` ke GitHub
* Jangan hardcode token API di file PHP
* Default admin credential hanya untuk kebutuhan demo tugas, **wajib diganti** jika dipakai publik

---

## 🗂️ Struktur Folder (Contoh)

> Struktur bisa menyesuaikan isi repo, ini contoh umum:

```
dailyjournal/
├─ img/                  # output gambar AI tersimpan
├─ assets/               # css/js/img static (jika ada)
├─ admin/                # halaman admin (jika dipisah)
├─ koneksi.php           # koneksi database
├─ login.php
├─ article.php           # CRUD artikel + generate image
├─ .env.example
└─ README.md
```

---

## ✅ Requirements

* PHP 7.4+ (recommended PHP 8.x)
* Apache (XAMPP/Laragon)
* MySQL/MariaDB
* PHP Extensions:

  * **cURL**
  * **GD**

---

## 📌 Repo

Link repository:
[https://github.com/Akazelll/dailyjournal](https://github.com/Akazelll/dailyjournal)

---

## 📄 Lisensi

Proyek ini dibuat untuk kebutuhan akademik (UAS).
Jika ingin dipublikasikan atau dipakai lanjut, silakan tambahkan lisensi sesuai kebutuhan (MIT/Apache-2.0/dll).

---

## 🙌 Acknowledgements

* Hugging Face Inference API
* FLUX.1 Schnell — Black Forest Labs
* Bootstrap & jQuery
