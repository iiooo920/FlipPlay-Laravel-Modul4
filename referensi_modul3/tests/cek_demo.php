<?php
// Pemeriksaan fitur inti tanpa framework dan tanpa dependency.
require_once __DIR__ . '/../src/Models/Media.php';
require_once __DIR__ . '/../src/Models/Anime.php';
require_once __DIR__ . '/../src/Models/Manga.php';
require_once __DIR__ . '/../src/Models/Drama.php';
require_once __DIR__ . '/../src/Services/Library.php';
require_once __DIR__ . '/../src/Services/UserCollection.php';

use FlipPlay\Models\Anime;
use FlipPlay\Models\Manga;
use FlipPlay\Models\Drama;
use FlipPlay\Services\Library;
use FlipPlay\Services\UserCollection;

function check(bool $condition, string $label): void
{
    if (!$condition) {
        fwrite(STDERR, "[FAIL] {$label}\n");
        exit(1);
    }
    echo "[OK] {$label}\n";
}

$library = new Library();
$anime = new Anime('Naruto', ['Action', 'Adventure'], 8.5, 2002, 'Uji');
$manga = new Manga('One Piece', ['Fantasy', 'Adventure'], 9.1, 1997, 'Uji');
$drama = new Drama('The Glory', ['Drama', 'Thriller'], 8.1, 2022, 'Uji');
$library->addMedia($anime);
$library->addMedia($manga);
$library->addMedia($drama);

check(count($library->getAllMedia()) === 3, 'Menambahkan dan menampilkan 3 jenis media');
check($anime->getType() === 'Anime' && $manga->getType() === 'Manga' && $drama->getType() === 'Drama', 'Inheritance dan override getType');
check(count($library->searchByTitle('NARUTO')) === 1, 'Search judul tanpa membedakan huruf besar/kecil');
check(count($library->searchByTitle('tidak ada')) === 0, 'Search tidak ditemukan');
check(count($library->filterMedia('', 'Anime', 'High Energy')) === 1, 'Filter jenis dan Aura');
check(count($library->filterMedia('', '', 'Mind Bender')) === 1, 'Filter Mind Bender');
check(count($library->filterMedia('', 'Drama', 'High Energy')) === 0, 'Filter tanpa kecocokan');
check($library->findByTitle('naruto') === $anime, 'Detail media ditemukan');
check($library->findByTitle('tidak ada') === null, 'Detail tidak ditemukan mengembalikan null');
check(str_contains($anime->getDetails(), 'Naruto'), 'Detail media dapat ditampilkan');
$user = new UserCollection('Pengguna');
check($user->addMedia($anime), 'Tambah koleksi pengguna');
check(!$user->addMedia($anime), 'Cegah data koleksi duplikat');
check(count($user->getItems()) === 1, 'Daftar koleksi pengguna');
check($user->getUserName() === 'Pengguna', 'Constructor dan getter koleksi');
echo "SEMUA TES LOLOS (14/14).\n";
