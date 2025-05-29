# PPMRJSKA - Sistem Informasi Pondok Pesantren Mahasiswa

[![Laravel Version](https://img.shields.io/badge/Laravel-v11.x-FF2D20.svg)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-%5E8.2-777BB4.svg)](https://www.php.net/)
[![Filament Version](https://img.shields.io/badge/Filament-v3.x-F59E0B.svg)](https://filamentphp.com)
[![License](https://img.shields.io/packagist/l/laravel/framework.svg)](https://packagist.org/packages/laravel/framework)

## Tentang Proyek

**PPMRJSKA** adalah Sistem Informasi Manajemen komprehensif yang dirancang untuk Pondok Pesantren Mahasiswa Roudhotul Jannah Surakarta. Aplikasi ini dibangun menggunakan Laravel 11 dan Filament v3, menyediakan antarmuka yang kuat dan ramah pengguna untuk mengelola berbagai aspek operasional pesantren.

Sistem ini mencakup manajemen data santri, pendaftaran santri baru (PPSB), kegiatan akademik seperti kurikulum, jadwal, presensi, dan munaqosah (ujian), administrasi keuangan, manajemen asrama, serta pengelolaan konten website seperti blog dan media.

## Fitur Utama

Situs bisa diakses melalui ppmrjska.com

## Fitur Utama

Sistem ini dibagi menjadi beberapa panel Filament untuk peran pengguna yang berbeda:
* **Admin Panel:** Pengelolaan sistem secara keseluruhan.
* **PPSB Panel:** Fokus pada proses penerimaan santri baru.
* **Santri Panel:** Akses untuk santri terkait informasi akademik dan lainnya.

Fitur-fitur utama meliputi:

* **Manajemen Pengguna & Peran:** Kontrol akses terperinci menggunakan Spatie Laravel Permission & Filament Shield.
* **Manajemen Santri:**
    * Biodata lengkap santri.
    * Manajemen calon santri dan proses pendaftaran.
    * Impor dan ekspor data santri.
* **Akademik & Kurikulum:**
    * Pengelolaan kurikulum dan materi pembelajaran (Hafalan, Himpunan, Juz, Surat, Tambahan).
    * Penjadwalan dan penilaian Munaqosah (ujian).
    * Jurnal kelas dan manajemen presensi (termasuk presensi via QR Code).
    * Pelacakan ketercapaian dan target materi.
* **Administrasi Keuangan:**
    * Pengelolaan jenis administrasi dan tagihan.
    * Pencatatan pembayaran administrasi.
    * Dukungan untuk format mata uang Rupiah.
* **Manajemen Asrama:**
    * Pengelolaan data asrama dan kamar.
    * Plotting kamar untuk santri.
* **Manajemen Konten Website:**
    * Blog dengan kategori dan tag.
    * Album foto dan galeri media.
    * Pengelolaan carousel untuk homepage.
    * Manajemen agenda kegiatan.
* **Data Wilayah:** Pengelolaan data Provinsi, Kota, Kecamatan, hingga Kelurahan.
* **Pengaturan Sistem:**
    * Konfigurasi informasi pondok, website, kurikulum, dan email.
    * Integrasi pengaturan dengan Spatie Laravel Settings.
* **Laporan & Ekspor:** Kemampuan untuk menghasilkan laporan dan mengekspor data ke Excel.
* **Fitur Tambahan:**
    * Kalender kegiatan terintegrasi (Filament FullCalendar).
    * Log aktivitas pengguna (Spatie Laravel Activitylog).
    * Notifikasi (Filament Notifications).
    * Editor Teks Kaya (TipTap Editor).
    * Peta Picker untuk pemilihan lokasi.

## Teknologi yang Digunakan

* **PHP 8.2+**
* **Laravel 11**
* **Filament 3.x Admin Panel**
* **Livewire 3.x**
* **Alpine.js**
* **Tailwind CSS**
* **MySQL** (atau database relasional lainnya yang didukung Laravel)
* **Berbagai Paket Laravel & Filament dari Spatie, Awcodes, dan lainnya** (lihat `composer.json` untuk daftar lengkap).

## Instalasi

1.  **Clone repository:**
    ```bash
    git clone [https://github.com/annasabdurrahman354/ppmrjska.git](https://github.com/annasabdurrahman354/ppmrjska.git)
    cd ppmrjska
    ```

2.  **Install dependensi PHP:**
    ```bash
    composer install
    ```

3.  **Install dependensi Node.js & build aset frontend:**
    ```bash
    npm install
    npm run dev
    ```
    (Untuk production, gunakan `npm run build`)

4.  **Buat file environment:**
    Salin `.env.example` menjadi `.env`:
    ```bash
    cp .env.example .env
    ```
    Kemudian, konfigurasi variabel environment Anda di file `.env`, terutama:
    * `APP_NAME`
    * `APP_URL`
    * `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
    * `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, dll.

5.  **Generate application key:**
    ```bash
    php artisan key:generate
    ```

6.  **Jalankan migrasi database:**
    ```bash
    php artisan migrate
    ```

7.  **Jalankan database seeder (opsional, tapi direkomendasikan untuk data awal):**
    ```bash
    php artisan db:seed
    ```
    (Ini akan menjalankan `DatabaseSeeder.php` yang mungkin memanggil seeder lain seperti `AllTableSeeder`, `RolesTableSeeder`, `UsersTableSeeder`.)

8.  **Buat symbolic link untuk storage:**
    ```bash
    php artisan storage:link
    ```

9.  **Konfigurasi Web Server:**
    Arahkan document root web server Anda (misalnya Apache atau Nginx) ke direktori `public` proyek.

10. **Antrian (Opsional):**
    Jika proyek menggunakan antrian (jobs), pastikan worker antrian berjalan:
    ```bash
    php artisan queue:work
    ```

## Struktur Direktori Utama (Contoh)
ppmrjska/
├── app/
│   ├── Console/
│   ├── Enums/                # Enumerations untuk berbagai status dan tipe
│   ├── Exceptions/
│   ├── Exports/              # Kelas untuk ekspor data (mis. Excel)
│   ├── Filament/             # Konfigurasi dan Resource untuk Panel Admin, PPSB, Santri
│   ├── Forms/                # Komponen form kustom untuk Filament
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   ├── Imports/              # Kelas untuk impor data
│   ├── Livewire/             # Komponen Livewire untuk frontend publik
│   ├── Mail/
│   ├── Models/               # Model Eloquent
│   ├── Policies/             # Kebijakan otorisasi
│   ├── Providers/
│   ├── Settings/             # Kelas pengaturan (Spatie Laravel Settings)
│   └── helpers.php
├── bootstrap/
├── config/                   # File konfigurasi aplikasi
├── database/
│   ├── factories/
│   ├── migrations/
│   ├── seeders/
│   └── settings/             # Migrasi untuk Spatie Laravel Settings
├── lang/                     # File-file bahasa (termasuk id dan en)
├── public/                   # Aset publik dan entry point aplikasi
├── resources/
│   ├── css/
│   ├── js/
│   ├── views/                # Blade templates (termasuk untuk Filament, Livewire, guest)
├── routes/                   # Definisi rute (web, api, console, channels)
├── storage/
├── tests/
├── vendor/
├── composer.json
├── package.json
└── README.md
