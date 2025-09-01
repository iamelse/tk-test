# Tugas Laravel - Sistem Manajemen Rumah Sakit dan Pasien

## Deskripsi
Aplikasi ini dibuat menggunakan **Laravel**, **jQuery**, dan **Bootstrap**.  
Fitur utama aplikasi ini adalah autentikasi berbasis username, serta CRUD (Create, Read, Update, Delete) untuk **Data Rumah Sakit** dan **Data Pasien**. CRUD Data Pasien memiliki fitur filter berdasarkan Rumah Sakit menggunakan Ajax, dan tombol hapus juga menggunakan Ajax.

---

## Requirement
- PHP >= 8.2
- Composer
- Laravel 11
- MySQL
- Node.js & npm

---

## Fitur
1. **Login**  
   - Autentikasi menggunakan **username** (bukan email)
   - Halaman dashboard setelah login

2. **CRUD Data Rumah Sakit**
   - Struktur tabel:
     | Kolom | Tipe Data |
     |-------|-----------|
     | id    | integer (auto increment) |
     | nama  | string |
     | alamat| text |
     | email | string |
     | telepon | string |
   
3. **CRUD Data Pasien**
   - Struktur tabel:
     | Kolom         | Tipe Data |
     |---------------|-----------|
     | id            | integer (auto increment) |
     | nama_pasien   | string |
     | alamat        | text |
     | no_telpon     | string |
     | rumah_sakit_id| integer (foreign key) |
   - Relasi:
     - Data Pasien **belongsTo** Rumah Sakit
     - Rumah Sakit **hasMany** Pasien
   - Fitur filter dropdown Rumah Sakit menggunakan Ajax
   - Tombol hapus menggunakan Ajax

---

## Instalasi

1. Clone repository:
   ```bash
   git clone <repository_url>
   cd <project_folder>
2. Install dependencies:
   ```bash
   composer install
   npm install
   npm run dev
3. Buat file .env dari template:
   ```bash
   cp .env.example .env
4. Sesuaikan konfigurasi database di .env:
   ```bash
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database
   DB_USERNAME=root
   DB_PASSWORD=
5. Jalankan migration dan seeder:
   ```bash
   php artisan migrate --seed
6. Jalankan server:
   ```bash
   php artisan serve

Akses aplikasi di http://127.0.0.1:8000/auth/login