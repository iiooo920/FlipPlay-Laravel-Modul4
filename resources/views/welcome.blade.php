<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>FlipPlay · Laravel REST API</title>
  <style>
    :root { color-scheme: dark; }
    body { margin: 0; font: 16px/1.6 system-ui,sans-serif; background: #101829; color: #e9edf7; }
    main { max-width: 780px; margin: 8vh auto; padding: 30px; }
    .tag { color: #84d9ff; text-transform: uppercase; font-size: 12px; letter-spacing: .2em; }
    h1 { font-size: clamp(2rem,6vw,3.3rem); margin: .1em 0; }
    p { color: #b8c4d6; }
    .panel { background: #1a273d; padding: 22px; margin: 26px 0; border: 1px solid #34435b; border-radius: 16px; }
    code { color: #b6e4ff; overflow-wrap: anywhere; }
    li { margin: 12px 0; }
    .hint { font-size: 13px; }
  </style>
</head>
<body>
<main>
  <span class="tag">Demo Modul 4 · Laravel 12</span>
  <h1>FlipPlay REST API</h1>
  <p>Backend untuk mengelola Anime, Manga, Drama, serta koleksi media pengguna. Data disimpan dalam database Laravel menggunakan Eloquent ORM.</p>
  <section class="panel">
    <strong>Endpoint utama</strong>
    <ul>
      <li><code>GET /api/media</code> — daftar, cari judul, filter jenis dan Aura</li>
      <li><code>GET /api/media/1</code> — detail dan relasi pengguna</li>
      <li><code>POST /api/media</code> — tambah media</li>
      <li><code>PATCH /api/media/1</code> — ubah media</li>
      <li><code>DELETE /api/media/1</code> — hapus media</li>
      <li><code>GET /api/users/1/collection</code> — koleksi pengguna</li>
      <li><code>POST /api/users/1/collection</code> — tambah ke koleksi</li>
      <li><code>DELETE /api/users/1/collection/1</code> — lepas dari koleksi</li>
    </ul>
  </section>
  <p class="hint">Ini hanya halaman informasi. Demo API dilakukan lewat Postman dan menerima/mengirim JSON. Lihat README.md dan POSTMAN_FLIPPLAY.json.</p>
</main>
</body>
</html>
