# Web Daily Journal dengan Fitur AI Image Generator

Proyek ini adalah pengembangan dari aplikasi Web Daily Journal yang mengintegrasikan kecerdasan buatan (Artificial Intelligence) untuk memenuhi tugas **Ujian Akhir Semester (UAS)**.

Fitur utama yang ditambahkan adalah **Auto Generate Gambar untuk Artikel** (Opsi 5), yang memungkinkan admin membuat ilustrasi artikel secara otomatis menggunakan Generative AI.

## 👥 Anggota Kelompok
| Nama                           | NIM            |
| ------------------------------ | -------------- |
| **Adam Raga** | A11.2024.15598 |
| **Affan Shahzada** | A11.2024.15784 |
| **Aiska Zahra Nailani** | A11.2024.16014 |
| **Nur Alif Maulana Syafrudin** | A11.2024.15936 |

## 🤖 Fitur AI: Auto Generate Image
Sesuai dengan deskripsi Opsi 5 pada dokumen panduan, fitur ini berfungsi untuk menghasilkan gambar ilustrasi otomatis berdasarkan **Judul Artikel**.

### Keunggulan Fitur:
* **Integrasi Text-to-Image:** Menggunakan model **Stable Diffusion XL Base 1.0** via Hugging Face Inference API.
* **Prompt Engineering Otomatis:** Sistem secara otomatis menerjemahkan judul artikel ke bahasa Inggris dan memperkaya prompt dengan parameter kualitas tinggi (e.g., *Ultra-realistic, Cinematic lighting, 8k render*) agar hasil gambar lebih estetis.
* **Efisiensi:** Membantu penulis yang kesulitan mencari gambar ilustrasi yang relevan.

## 🛠️ Teknologi yang Digunakan
* **Backend:** PHP Native (7.4 / 8.x)
* **Frontend:** Bootstrap 5, jQuery (AJAX)
* **Database:** MySQL
* **AI Service:** Hugging Face Inference API (Model: `stabilityai/stable-diffusion-xl-base-1.0`)
* **Translation:** Google Translate API (Unofficial/Free endpoint) untuk preprocessing prompt.

## ⚙️ Cara Instalasi & Konfigurasi

1.  **Clone Repository**
    ```bash
    git clone [https://github.com/Akazelll/dailyjourna](https://github.com/Akazelll/dailyjourna)
    ```

2.  **Setup Database**
    * Buat database baru di phpMyAdmin (misal: `dailyjournal` atau `latihan_pbw`).
    * Import file database yang disertakan (jika ada file `.sql`, sertakan di sini).
    * Sesuaikan konfigurasi di `koneksi.php`:
        ```php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "nama_database_kamu";
        ```

3.  **Konfigurasi Environment Variable (.env)**
    * Duplikat file `.env.example` (jika ada) atau buat file baru bernama `.env` di root folder.
    * Dapatkan **Access Token** (Write permission) dari [Hugging Face Settings](https://huggingface.co/settings/tokens).
    * Isi file `.env`:
        ```env
        HF_TOKEN=hf_xxxxxxxxxxxxxxxxxxxxxxxxxxxx
        ```

4.  **Izin Folder**
    Pastikan folder `img/` memiliki izin tulis (*write permission*) agar script PHP dapat menyimpan gambar hasil generate AI.

## 📖 Panduan Penggunaan (User Guide)

1.  **Login Admin:** Masuk ke halaman `login.php` menggunakan akun admin.
2.  **Buka Halaman Artikel:** Navigasi ke menu **Article**.
3.  **Tambah Artikel:** Klik tombol **"Tambah Article"**.
4.  **Isi Judul:** Masukkan judul artikel yang ingin ditulis (Minimal 3 karakter). Judul ini akan menjadi dasar (prompt) bagi AI untuk menggambar.
5.  **Generate Gambar:**
    * Klik tombol **"Generate Images"** (ikon bintang/magic).
    * Tunggu proses *loading* (biasanya 10-20 detik tergantung server Hugging Face).
    * *Preview* gambar akan muncul otomatis setelah selesai.
6.  **Simpan:** Lengkapi isi artikel dan klik **Simpan**. Gambar otomatis tersimpan di server dan database.

## 🧠 Dokumentasi Teknis (Arsitektur & Alur)

Proses generasi gambar berjalan dengan alur sebagai berikut:

1.  **Client-Side (article.php):**
    * User menginput judul.
    * AJAX request dikirim ke `ajax_ai.php` membawa parameter `judul`.

2.  **Server-Side Logic (ajax_ai.php & ai_image.php):**
    * **Translation:** Judul bahasa Indonesia diterjemahkan ke Inggris menggunakan fungsi `translateToEnglish()` agar lebih dipahami oleh model AI.
    * **Prompt Enhancement:** Prompt judul digabungkan dengan *magic words* (modifier) seperti *"Ultra-realistic Unreal Engine 5 cinematic render..."* untuk memastikan kualitas output tinggi.
    * **API Call:** Script mengirim POST request ke Hugging Face API dengan *header* Authorization Bearer token.
    * **Response Handling:** API mengembalikan binary image, yang kemudian di-encode ke Base64 dan dikirim kembali ke browser untuk preview.

3.  **Saving:**
    * Saat tombol simpan ditekan, Base64 image di-decode kembali menjadi file `.jpg/.jpeg` dan disimpan ke folder `img/` dengan nama unik.

## ⚠️ Disclaimer & Etika AI
Fitur ini menggunakan model Generative AI pihak ketiga.
* Gambar yang dihasilkan bersifat ilustratif dan mungkin tidak 100% akurat secara fakta.
* Harap gunakan fitur ini dengan bijak dan tidak menggunakannya untuk membuat konten yang melanggar hukum, berbau SARA, atau pornografi.
* Hak cipta gambar mengikuti ketentuan lisensi dari model Stability AI.

---
**Dibuat untuk memenuhi tugas UAS Mata Kuliah Pemrograman Berbasis Web.**
