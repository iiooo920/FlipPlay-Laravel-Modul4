DEMO MODUL 3 - FLIPPLAY (PHP OOP)
================================

ACUAN
- Modul 3 Pemrograman Web, halaman 28: Demo prototipe backend engine PHP OOP
- Modul 3, halaman 29: Rubrik penilaian demo
- Konsep DEMO MODUL 2: Anime, Manga, Drama; pencarian judul; filter Aura.

CARA MENJALANKAN DI VS CODE / WINDOWS
1. Ekstrak ZIP dan buka folder "DEMO MODUL 3" di VS Code.
2. Klik menu Terminal > New Terminal.
3. Pastikan PHP sudah tersedia dengan perintah:
   php -v
4. Pastikan terminal berada di folder yang berisi index.php.
5. Jalankan:
   php index.php

TIDAK PERLU LIVE SERVER, INTERNET, DATABASE, FRAMEWORK, ATAU INSTAL PACKAGE.
PHP 8.x cukup. Data di index.php adalah data simulasi lokal (bukan data API live).

STRUKTUR PROYEK
DEMO MODUL 3/
  index.php                     Entry point untuk menjalankan seluruh simulasi
  src/Models/Media.php          Parent class: property, constructor, getter, detail
  src/Models/Anime.php          Child class Media
  src/Models/Manga.php          Child class Media
  src/Models/Drama.php          Child class Media
  src/Services/Library.php      Menambah, menampilkan, mencari, filter media
  src/Services/UserCollection.php  Koleksi media pengguna
  tests/cek_demo.php            Tes dasar program tanpa library tambahan
  README.txt                    Panduan ini

DEMO BERDASARKAN STUDI KASUS FLIPPLAY
1. Menambah media ke library
2. Menampilkan daftar media
3. Mencari media berdasarkan judul
4. Memfilter jenis media dan Aura (mengikuti konsep Modul 2)
5. Menampilkan detail media
6. Menampilkan koleksi media pengguna

KETENTUAN WAJIB MODUL 3 HALAMAN 28
1. Minimal 3 class                : 6 class (Media, Anime, Manga, Drama, Library, UserCollection)
2. Constructor __construct        : Media.php; UserCollection.php (child class mewarisi constructor)
3. Property dan method            : Media.php; Library.php; UserCollection.php
4. Access modifier                : private untuk property, public untuk method yang diakses
5. Inheritance/struktur rapi      : Anime/Manga/Drama extends Media; file dipisahkan
6. Type hinting dan return type   : contoh addMedia(Media $media): void;
                                    getTitle(): string; findByTitle(): ?Media
7. Namespace                      : FlipPlay\Models dan FlipPlay\Services
8. Entry point index.php          : file index.php di root proyek

KATA KUNCI UNTUK PRESENTASI
- class      = cetakan untuk membuat object
- object     = data nyata yang dibuat dari class dengan new
- property   = data/atribut di dalam class
- method     = fungsi yang dimiliki class
- constructor= otomatis dipanggil saat object dibuat
- private    = hanya bisa diakses langsung di class itu sendiri
- public     = bisa dipanggil dari luar class
- inheritance= mewarisi atribut dan method dari parent class
- type hint  = menentukan tipe parameter
- return type= menentukan tipe hasil method
- namespace  = mengorganisasi class agar tidak bentrok nama
- index.php  = titik awal eksekusi program

CATATAN
- Rating, sinopsis, genre, dan tahun di index.php merupakan DATA SIMULASI,
  bukan respons API Tenrai/TVmaze. Tidak diklaim sebagai data terbaru.
- Modul 3 secara eksplisit meminta simulasi terminal PHP OOP, bukan website.
- File DEMO MODUL 2 tidak diubah dan tidak diperlukan untuk menjalankan Demo Modul 3.

TES TAMBAHAN (opsional)
php tests/cek_demo.php
