# 🧩 SIKLAS – Backend (Laravel 11)

Layanan backend untuk **SIKLAS (Sistem Informasi Klasifikasi Topik Skripsi)**.  
Dibangun menggunakan **Laravel 11**, backend ini menangani autentikasi, manajemen data, dan komunikasi dengan layanan *Machine Learning* berbasis FastAPI.

---

## 🚀 Tech Stack
- **Framework**: Laravel 11 (PHP 8.2+)  
- **Database**: MySQL / PostgreSQL  
- **Auth**: Laravel Sanctum  
- **API Style**: RESTful API  
- **Deployment**: Apache / Nginx  

---

## ⚙️ Fitur Utama
- Autentikasi pengguna (Mahasiswa, Dosen, Admin)  
- CRUD data skripsi dan hasil klasifikasi  
- Koneksi ke *Machine Learning Service (FastAPI)* untuk prediksi topik  
- Middleware untuk keamanan dan validasi token  
- Struktur modular dengan Service Layer  

---

## 🧩 Instalasi & Menjalankan

```bash
# Clone repository
git clone https://github.com/username/siklas-be.git
cd siklas-be

# Install dependency
composer install

# Salin file environment
cp .env.example .env

# Generate key
php artisan key:generate

# Migrasi database
php artisan migrate

# Jalankan server
php artisan serve
