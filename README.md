# FLIPPLAY — DEMO MODUL 4 (Laravel 12 REST API)

**Proyek ini adalah pengembangan langsung `DEMO MODUL 3` milik pengguna**, bukan proyek perpustakaan Codelab. FlipPlay sebelumnya berupa simulasi PHP OOP dengan data array; di sini data disimpan dalam database dan digunakan melalui HTTP JSON API.

## Konsep yang dipertahankan dari Modul 3

- Kategori **Anime, Manga, Drama** (`media.type`).
- Enam contoh asli: Naruto, Frieren, One Piece, Death Note, Alice in Borderland, The Glory.
- Atribut: `title`, `genres`, `rating`, `year`, `synopsis`.
- Pencarian berdasarkan judul, filter kategori dan **Aura** (`High Energy`, `Melancholy`, `Mind Bender`, `Binge Mode`, `Escapism`).
- Koleksi pribadi pengguna; awalnya berisi **Naruto** dan **One Piece**.
- Kode asli tetap tersedia di `referensi_modul3/` untuk membandingkan perubahan (bukan komponen aktif Laravel).

**Arsitektur baru:** `App\Models\Media` dan `App\Models\User` dihubungkan oleh pivot `media_user` (`belongsToMany`). Tiga subclass PHP lama diwakili oleh kolom `type` karena kini data harus menjadi baris tabel, bukan tiga subclass terpisah.

> **Lingkup keamanan:** API ini **untuk demo lokal dan Postman**; belum memiliki autentikasi/otorisasi. Jangan men-deploy ke internet publik tanpa menambahkan login dan kebijakan akses agar pengguna tidak dapat mengubah koleksi milik orang lain.

## Prasyarat

- **PHP 8.2+**, ekstensi Laravel yang dibutuhkan dan **Composer**.
- Database: **MySQL** (sesuai contoh modul) atau SQLite (untuk menjalankan tanpa Laragon/XAMPP jika driver PDO SQLite aktif).
- VS Code dan Postman (dapat menggunakan Postman tanpa login untuk HTTP requests).
- Folder proyek adalah `FLIPPLAY-DEMO-MODUL-4` yang berisi `artisan`.

## Instalasi dari ZIP (Windows PowerShell)

1. Ekstrak ZIP, buka folder **FLIPPLAY-DEMO-MODUL-4** di VS Code.
2. Terminal baru, jalankan perintah dari folder yang memuat `artisan`:

```powershell
php -v
composer install
Copy-Item .env.example .env
php artisan key:generate
```

3. Pilih database. Default `.env.example` menggunakan **SQLite**; file kosong `database/database.sqlite` disertakan dalam ZIP. Jika mengambil kode via GitHub (file `.sqlite` diabaikan oleh git), buat dahulu dengan `New-Item -ItemType File -Path database/database.sqlite` jika belum ada. Jika memakai SQLite dan driver aktif, lanjut ke langkah 4. Untuk **MySQL**, aktifkan MySQL lalu buat database kosong `flipplay_modul4` (melalui phpMyAdmin/HeidiSQL). Isi `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=flipplay_modul4
DB_USERNAME=root
DB_PASSWORD=
```

> Jika MySQL kamu memiliki username/password lain, sesuaikan nilainya. Jangan menyimpan `.env` dalam Git.

4. Menyiapkan database, data contoh dan server:

```powershell
php artisan config:clear
php artisan migrate --seed
php artisan route:list --path=api
php artisan serve
```

5. Buka browser pada `http://127.0.0.1:8000` untuk halaman keterangan; gunakan Postman untuk request API. Header **Accept: application/json** dan untuk POST/PATCH yang berisi body gunakan **Body → raw → JSON**.

6. Untuk pengecekan otomatis, di terminal lain:

```powershell
php artisan test
```

`composer install` otomatis memasang dependensi Laravel ke folder `vendor/`; folder itu **tidak disertakan** dalam ZIP agar ukuran ringan. **Tidak perlu `php artisan install:api`**, karena `routes/api.php` dan pendaftarannya di `bootstrap/app.php` sudah ada.

Jangan jalankan `php artisan migrate:fresh --seed` pada database berisi data penting: perintah itu **menghapus semua tabel/data** dan mengulang dari awal. Seeder biasa bersifat idempoten (`firstOrCreate` dan `syncWithoutDetaching`) sehingga dapat dijalankan ulang untuk data demo tanpa menciptakan duplikat.

## Endpoint API untuk demo

| HTTP | Path | Fungsi | Status normal |
|---|---|---|---|
| GET | `/api/media` | Semua media (pagination) | 200 |
| GET | `/api/media/{media}` | Detail dan users pengoleksi (eager loaded) | 200 |
| POST | `/api/media` | Tambah Anime/Manga/Drama | 201 |
| PATCH/PUT | `/api/media/{media}` | Ubah sebagian/semua field | 200 |
| DELETE | `/api/media/{media}` | Hapus media | 204 |
| GET | `/api/users` | Lihat pengguna demo | 200 |
| GET | `/api/users/{user}` | Detail pengguna dengan koleksi | 200 |
| GET | `/api/users/{user}/collection` | Lihat koleksi | 200 |
| POST | `/api/users/{user}/collection` | Masukkan media ke koleksi (`attach`) | 201 |
| DELETE | `/api/users/{user}/collection/{media}` | Keluarkan dari koleksi (`detach`) | 204 |

Query filter pada GET `/api/media` bisa digabungkan:

- `?search=naruto` — cari judul (sebagian teks, contoh MySQL biasanya tidak sensitif kapital).
- `?type=Anime` — Anime / Manga / Drama.
- `?aura=Mind%20Bender` — genre cocok dengan peta Aura Modul 3.
- `?type=Anime&aura=High%20Energy`.

Response JSON mengandung `data`. GET index juga mengandung `links` dan `meta` dari pagination. Detail Media mengandung relasi `users` dan detail User mengandung relasi `collection` bila dimuat (`whenLoaded`).

### Contoh POST Media

**POST** `http://127.0.0.1:8000/api/media`

```json
{
  "title": "Mob Psycho 100",
  "type": "Anime",
  "genres": ["Action", "Comedy"],
  "rating": 8.7,
  "year": 2016,
  "synopsis": "Serial anime tentang remaja dengan kemampuan psikis."
}
```

**PATCH** `http://127.0.0.1:8000/api/media/{id}` (contoh hanya rating):

```json
{"rating": 9.0}
```

### Tambah/hapus koleksi

Setelah `php artisan migrate --seed` pada **database kosong**, pengguna contoh memiliki ID 1, Naruto ID 1, Frieren ID 2. Untuk menambahkan Frieren (yang belum di koleksi awal):

**POST** `http://127.0.0.1:8000/api/users/1/collection`

```json
{"media_id": 2}
```

**GET** `http://127.0.0.1:8000/api/users/1/collection` untuk membuktikan koleksi berubah.

**DELETE** `http://127.0.0.1:8000/api/users/1/collection/2` untuk mengeluarkan Frieren. Tidak perlu body.

ID angka di atas hanya contoh **pada database baru yang bersih**. Jika database sudah berisi data, temukan ID lewat GET `/api/media` dan GET `/api/users`.

## Pengujian gagal (WAJIB untuk rubrik)

- **POST `/api/media` judul kosong** → status **422** + objek `errors.title`.
- **POST `/api/media` judul sudah ada** (`Naruto`) → **422**, pelanggaran `unique`.
- **POST `/api/media` jenis bukan Anime/Manga/Drama** → **422**.
- **POST `/api/users/1/collection` dengan media Naruto ID 1 yang sudah ada di koleksi awal** → **409 Conflict**.
- **GET `/api/media/999999`** → **404 Not Found**.
- **PATCH rating di luar 0–10** → **422**.

Dalam JSON response error gunakan header `Accept: application/json` supaya kegagalan tidak ditampilkan sebagai HTML redirect.

## Peta 8 ketentuan modul ke file

| Ketentuan halaman 29 | Bukti di proyek |
|---|---|
| 1. Repositori GitHub + project Laravel | Project Laravel 12 dan langkah push GitHub di bawah (repo harus dibuat pada akunmu) |
| 2. >= 2 entitas + Eloquent relationship | `app/Models/Media.php`, `app/Models/User.php` (`belongsToMany`) |
| 3. Migration entitas + pivot | `database/migrations/*create_users_table.php`, `*create_media_table.php`, `*create_media_user_table.php` |
| 4. CRUD lengkap entitas Media | `app/Http/Controllers/MediaController.php` |
| 5. Form Request Validation POST/PATCH | `app/Http/Requests/StoreMediaRequest.php`, `UpdateMediaRequest.php`; 422 JSON |
| 6. API Resource + whenLoaded | `app/Http/Resources/MediaResource.php`, `UserResource.php` |
| 7. routes/api.php & route:list | `routes/api.php`; `php artisan route:list --path=api` |
| 8. Custom action di luar CRUD | `UserCollectionController.php` (`attach`/`detach` pivot) |

Lihat `PANDUAN_DEMO.md` dan `POSTMAN_FLIPPLAY.json` untuk skrip pengujian terurut dan contoh respons. 

## Membuat repo GitHub untuk memenuhi ketentuan 1

1. Buat repositori **baru dan kosong** bernama `flipplay-modul-4` pada akun GitHub milikmu.
2. Dari terminal VS Code di folder proyek:

```powershell
git init
git add .
git commit -m "Demo Modul 4: FlipPlay Laravel REST API"
git branch -M main
git remote add origin https://github.com/USERNAME/flipplay-modul-4.git
git push -u origin main
```

Ganti `USERNAME` dengan username GitHub kamu. Git mungkin meminta konfigurasi `user.name` dan `user.email` pada komputer pertama kali. `.env`, `vendor/` dan file SQLite database harus tetap diabaikan oleh `.gitignore`.
