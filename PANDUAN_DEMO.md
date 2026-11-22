# CONTEKAN DEMO — FlipPlay Laravel Modul 4

Sumber: PDF "Modul 4 - Laravel REST API: CRUD, Eloquent ORM & Relationship", **DEMO halaman 29–30**, **rubrik halaman 31**.

## Urutan menampilkan ke dosen

1. **Project & repo GitHub**: tunjukkan repository milikmu (harus dibuat sendiri) dan folder Laravel.
2. **Migration**: buka `create_users_table`, `create_media_table`, `create_media_user_table` dan jelaskan `foreignId()->constrained()` serta `unique(user_id, media_id)`.
3. **Model**: buka `Media.php` dan `User.php`, tunjukkan `belongsToMany(...)` di keduanya.
4. **Controller**: buka `MediaController.php` (5 CRUD: `index/show/store/update/destroy`).
5. **Validasi**: buka `StoreMediaRequest.php` dan `UpdateMediaRequest.php`, tunjukkan judul wajib `required` dan `Rule::unique(...)`.
6. **Resources**: buka `MediaResource.php` dan `UserResource.php`, tunjukkan `whenLoaded('users')` dan `whenLoaded('media')`.
7. **Routes**: `php artisan route:list --path=api`.
8. **Custom action**: buka `UserCollectionController.php`, tunjukkan `attach`, `detach` dan penolakan duplikat (409).

## Perintah terminal (Windows PowerShell)

Pada **setup pertama**:

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
php artisan migrate --seed
```

Pada **hari demo** (setelah setup):

```powershell
php artisan serve
```

Di terminal kedua:

```powershell
php artisan route:list --path=api
php artisan test
```

Server berjalan di `http://127.0.0.1:8000`. Biarkan terminal server aktif saat membuka Postman.

## Request Postman untuk unjuk demo (urut)

Set header **Accept: application/json**. Untuk POST/PATCH: **Body → raw → JSON**.

| Urutan | Request | Body / hasil yang diharapkan |
|---|---|---|
| 1 | GET `/api/media` | 6 media contoh (sebelum menambah baru) |
| 2 | GET `/api/media/1` | Naruto dan `users` dari relationship |
| 3 | GET `/api/media?search=naruto` | 1 hasil Naruto |
| 4 | GET `/api/media?type=Anime&aura=High%20Energy` | Hasil filter Anime + Aura |
| 5 | POST `/api/media` | JSON lengkap media baru; **201** |
| 6 | PATCH `/api/media/{id_baru}` | `{"rating": 9.0}`; **200** |
| 7 | DELETE `/api/media/{id_baru}` | **204** |
| 8 | POST `/api/media` tanpa title | **422**, `errors.title` |
| 9 | GET `/api/users/1/collection` | Awalnya Naruto dan One Piece |
| 10 | POST `/api/users/1/collection` | `{"media_id": 2}` (Frieren); **201** |
| 11 | POST `/api/users/1/collection` lagi | Body sama; **409 Conflict** |
| 12 | GET `/api/users/1/collection` | Frieren muncul di koleksi |
| 13 | DELETE `/api/users/1/collection/2` | **204** |
| 14 | GET `/api/users/1/collection` | Frieren sudah hilang dari koleksi |

**Jangan asal pakai ID 1 atau 2 jika menggunakan database lama.** Ambil ID terbaru lewat GET terlebih dulu.

## Hafalan untuk tanya jawab

- **Migration**: mendefinisikan struktur tabel database lewat PHP.
- **Eloquent ORM**: Model mewakili data tabel, tidak harus menulis SQL manual.
- **belongsToMany**: satu user mengoleksi banyak media, satu media dimiliki banyak user. Pivot `media_user` menghubungkannya.
- **Eager loading (`with` / `load`)**: memuat data relasi di awal agar menghindari N+1 query.
- **Controller**: menerima request dan menjalankan proses bisnis.
- **Form Request**: memvalidasi input; data salah → 422.
- **API Resource**: mengatur format respons JSON.
- **whenLoaded**: mengirim relasi hanya bila sudah di-load.
- **Custom action**: tindakan di luar CRUD, misalnya `attach` dan `detach`.
- **Status**: 200 sukses, 201 dibuat, 204 sukses tanpa isi, 404 tidak ditemukan, 409 bentrok, 422 validasi gagal.

## Catatan penting

`php artisan migrate:fresh --seed` **menghapus seluruh tabel dan data**. Jalankan hanya pada database latihan ketika memang ingin mereset data; jangan jadikan perintah wajib setiap demo.

Aplikasi ini API lokal **tanpa login/token** untuk memfokuskan rubrik Modul 4. Tidak aman dipublikasikan ke internet apa adanya.
