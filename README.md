
# Project Laravel

## Deskripsi Proyek
Ini adalah proyek aplikasi Laravel yang dibangun untuk mengelola permintaan dan persetujuan pembelian alat berat. Proyek ini menggunakan Laravel versi 11, PHP versi 8.3.8, Laragon versi 6.0, dan MySQL versi 8.0. Aplikasi ini mencakup fitur-fitur seperti autentikasi pengguna, kontrol akses berbasis peran, dan pelaporan periode.

## Teknologi yang Digunakan
- **Framework**: Laravel 11
- **Bahasa Pemrograman**: PHP 8.3.8
- **Composer** : 2.7.6 
- **Server Lokal**: Laragon 6.0
- **Database**: MySQL 8.0

## Langkah-langkah untuk Menjalankan Aplikasi

1. **Clone Repository**
   ```
   https://github.com/achmichael/test-intern-backend.git
   ```
2. **Masuk Direktori Proyek Hasil Clone**
   ```
   cd test-intern-backend
   ```
3. **Instal Dependensi**
   ```
   composer install
   ```

4. **Salin File `.env.example` lalu rename menjadi file .env**

5. **Konfigurasi Environment**
   Edit file `.env` dan atur konfigurasi database Anda:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database
   DB_USERNAME=user_database
   DB_PASSWORD=password_database
   ```

6. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

7. **Migrasi Database**
   
- Masuk Ke Direktori Database

   ```
   cd database
   ```
- Masuk Ke Direktori Migrations

   ```
   cd migrations
   ```

- Jalankan file migrate.php

   ```
   php migrate.php
   ```
- Setelah Sukses Melakukan Migrasi, Selanjutnya Buka New Terminal Atau dengan menjalankan perintah  
   ```
   cd ../..   (Untuk Kembali Ke Direktori Awal)
   ```
   Lalu lakukan Seeder database dengan perintah dibawah ini.   
8. **Seed Database**
   ```bash
   php artisan db:seed
   ```

9. **Jalankan Server**
   ```bash
   php artisan serve
   ```

10. **Akses Aplikasi**
   
      Dari Hasil Perintah Diatas, akan memberikan output `Server running on [http://127.0.0.1:8000].` untuk mengakses aplikasi anda bisa melakukan ctrl + klik pada link yang sudah diberikan, atau link tersebut bisa anda paste pada web browser yang anda punya.

## Fitur Utama
- **Autentikasi Pengguna**: Login, register, dan lupa password.
- **Kontrol Akses Berbasis Peran**: Hak akses berbeda untuk karyawan, admin, dan direktur.
- **Pelaporan Berkala**: Laporan mingguan dan bulanan dengan ekspor ke Excel.
- **Formulir Permintaan Pembelian**: Pengguna dapat membuat permintaan Pengajuan pembelian alat berat.

## Peran Dan Akun Dari Setiap Peran
- **Karyawan**

   ```
   username : 'Michael',
   password : 'Password#123',
   role     : 'karyawan'
   ```
- **Admin**

   ```
   username : 'Ator',
   password : 'Password*123',
   role     : 'admin'
   ```
- **Direktur**

   ```
   username : 'Direktur',
   passwod  : 'Ilham#123'
   role     : 'direktur'
   ```
   
