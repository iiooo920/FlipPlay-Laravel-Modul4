# HASIL PEMERIKSAAN — FLIPPLAY DEMO MODUL 4

Pemeriksaan pembuatan ZIP (22 November 2026):

- [x] ZIP Modul 3 pengguna terbaca, 6 media dan fitur pencarian/Aura/koleksi teridentifikasi.
- [x] Pengujian asli Modul 3: **14/14 lulus** (`php tests/cek_demo.php`).
- [x] Proyek Laravel 12 menggunakan `composer.json` dengan `laravel/framework:^12.0` dan `composer.lock` yang mengunci Laravel **v12.65.0**.
- [x] Pengecekan sintaks seluruh **49 file PHP** dengan `php -l`: **lulus**.
- [x] Audit statis 25 hal penting: **25/25 lulus**, mencakup CRUD, migrasi, pivot, relasi, validasi, Resource, routing, fitur Aura, seed data dan Postman.
- [x] File Postman Collection v2.1 valid JSON, berisi 16 request happy path + failure path.
- [x] Kode tes otomatis pada `tests/Feature/MediaApiTest.php`: 10 skenario yang mencakup GET, POST, PATCH, DELETE, duplicate/invalid, relationship, pivot attach/detach, dan seeder.
- [x] Kode contoh asli Modul 3 disimpan di `referensi_modul3/`.

**Batas pengujian:** `composer install`, `php artisan migrate --seed`, `php artisan route:list`, `php artisan test` dan pengujian Postman **belum dijalankan di lingkungan pembuat ZIP**, karena Composer/dependensi `vendor/` dan driver database PDO belum tersedia serta akses paket jaringan tidak tersedia. Dengan demikian tidak ada klaim bahwa seluruh endpoint telah lolos pengujian runtime. Tes feature sudah disiapkan untuk dijalankan pada komputer pengguna setelah `composer install`.

**Ketentuan GitHub:** project disiapkan untuk diunggah, tetapi **repositori GitHub belum dibuat**. Pemilik akun harus membuat dan melakukan push sesuai panduan README.

Cara memeriksa pada komputer dengan PHP, Composer dan driver database:

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan route:list --path=api
php artisan test
php artisan serve
```

Setelah itu import `POSTMAN_FLIPPLAY.json` di Postman, gunakan base_url `http://127.0.0.1:8000`, dan jalankan request berurutan.
