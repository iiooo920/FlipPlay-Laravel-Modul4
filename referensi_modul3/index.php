<?php

// DEMO MODUL 3 - FlipPlay PHP OOP
// Jalankan dari terminal: php index.php
// Simulasi backend, tidak menghubungi API dan tidak memerlukan database.

require_once __DIR__ . '/src/Models/Media.php';
require_once __DIR__ . '/src/Models/Anime.php';
require_once __DIR__ . '/src/Models/Manga.php';
require_once __DIR__ . '/src/Models/Drama.php';
require_once __DIR__ . '/src/Services/Library.php';
require_once __DIR__ . '/src/Services/UserCollection.php';

use FlipPlay\Models\Anime;
use FlipPlay\Models\Manga;
use FlipPlay\Models\Drama;
use FlipPlay\Models\Media;
use FlipPlay\Services\Library;
use FlipPlay\Services\UserCollection;

/** Cetak daftar object Media dalam terminal. */
function printMediaList(array $items): void
{
    if (count($items) === 0) {
        echo "(Tidak ada media yang cocok)\n";
        return;
    }

    foreach ($items as $media) {
        if ($media instanceof Media) {
            echo '- ' . $media->getTitle()
                . ' | ' . $media->getType()
                . ' | ' . implode(', ', $media->getGenres()) . "\n";
        }
    }
}

$library = new Library();
$user = new UserCollection('Pengguna FlipPlay');

echo "============================================\n";
echo "        FLIPPLAY - DEMO MODUL 3\n";
echo "     Simulasi Backend dengan PHP OOP\n";
echo "============================================\n";
echo "Catatan: seluruh media adalah data contoh lokal.\n\n";

// 1. Menambahkan data simulasi Anime, Manga, Drama.
echo "1. MENAMBAHKAN MEDIA KE LIBRARY\n";
$contohMedia = [
    new Anime('Naruto', ['Action', 'Adventure'], 8.5, 2002, 'Kisah ninja muda yang mengejar cita-citanya.'),
    new Anime('Frieren: Beyond Journey\'s End', ['Fantasy', 'Adventure', 'Drama'], 9.2, 2023, 'Petualangan Frieren setelah perjalanan bersama rekan-rekannya.'),
    new Manga('One Piece', ['Action', 'Adventure', 'Fantasy'], 9.1, 1997, 'Petualangan sekelompok bajak laut mencari harta karun.'),
    new Manga('Death Note', ['Mystery', 'Psychological', 'Thriller'], 8.9, 2003, 'Kisah misteri sebuah buku dengan kekuatan tidak biasa.'),
    new Drama('Alice in Borderland', ['Drama', 'Thriller', 'Mystery'], 8.0, 2020, 'Sekelompok orang harus menyelesaikan permainan penuh teka-teki.'),
    new Drama('The Glory', ['Drama', 'Thriller'], 8.1, 2022, 'Drama tentang perjuangan mendapatkan keadilan.'),
];

foreach ($contohMedia as $media) {
    $library->addMedia($media);
    echo '[OK] ' . $media->getType() . ': ' . $media->getTitle() . " ditambahkan\n";
}

// 2. Menampilkan seluruh daftar media.
echo "\n2. DAFTAR SEMUA MEDIA\n";
printMediaList($library->getAllMedia());

// 3. Search by title, seperti search pada Modul 2.
echo "\n3. PENCARIAN MEDIA BERDASARKAN JUDUL\n";
$keyword = 'naruto';
echo "Kata kunci: {$keyword}\n";
printMediaList($library->searchByTitle($keyword));

// 4. Filter kategori dan Aura sama seperti konsep The Vibe Grid.
echo "\n4. FILTER JENIS MEDIA DAN AURA\n";
echo "Jenis: Anime | Aura: High Energy\n";
printMediaList($library->filterMedia('', 'Anime', 'High Energy'));
echo "Jenis: Semua | Aura: Mind Bender\n";
printMediaList($library->filterMedia('', '', 'Mind Bender'));

// 5. Detail lengkap sebuah media (polymorphism: getType() berbeda tiap subclass).
echo "\n5. DETAIL MEDIA\n";
$detailMedia = $library->findByTitle('Frieren: Beyond Journey\'s End');
if ($detailMedia !== null) {
    echo $detailMedia->getDetails() . "\n";
} else {
    echo "Media tidak ditemukan.\n";
}

// 6. Koleksi pribadi milik pengguna.
echo "\n6. KOLEKSI MEDIA PENGGUNA\n";
$user->addMedia($library->findByTitle('Naruto'));
$user->addMedia($library->findByTitle('One Piece'));
echo 'Pengguna: ' . $user->getUserName() . "\n";
printMediaList($user->getItems());

echo "\n============================================\n";
echo "       DEMO MODUL 3 SELESAI\n";
echo "============================================\n";
