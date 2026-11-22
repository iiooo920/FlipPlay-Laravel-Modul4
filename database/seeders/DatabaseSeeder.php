<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Data asli berasal dari index.php DEMO MODUL 3 (simulasi lokal).
        $items = [
            ['Naruto', 'Anime', ['Action', 'Adventure'], 8.5, 2002, 'Kisah ninja muda yang mengejar cita-citanya.'],
            ['Frieren: Beyond Journey\'s End', 'Anime', ['Fantasy', 'Adventure', 'Drama'], 9.2, 2023, 'Petualangan Frieren setelah perjalanan bersama rekan-rekannya.'],
            ['One Piece', 'Manga', ['Action', 'Adventure', 'Fantasy'], 9.1, 1997, 'Petualangan sekelompok bajak laut mencari harta karun.'],
            ['Death Note', 'Manga', ['Mystery', 'Psychological', 'Thriller'], 8.9, 2003, 'Kisah misteri sebuah buku dengan kekuatan tidak biasa.'],
            ['Alice in Borderland', 'Drama', ['Drama', 'Thriller', 'Mystery'], 8.0, 2020, 'Sekelompok orang harus menyelesaikan permainan penuh teka-teki.'],
            ['The Glory', 'Drama', ['Drama', 'Thriller'], 8.1, 2022, 'Drama tentang perjuangan mendapatkan keadilan.'],
        ];

        $mediaByTitle = [];
        foreach ($items as [$title, $type, $genres, $rating, $year, $synopsis]) {
            // firstOrCreate mencegah data ganda jika seeder dijalankan ulang.
            $mediaByTitle[$title] = Media::firstOrCreate(
                ['title' => $title],
                compact('type', 'genres', 'rating', 'year', 'synopsis')
            );
        }

        $user = User::firstOrCreate(
            ['email' => 'flipplay@example.test'],
            ['name' => 'Pengguna FlipPlay', 'password' => 'demo-password-local-only']
        );

        // Warisan koleksi Modul 3: Naruto dan One Piece.
        $user->media()->syncWithoutDetaching([
            $mediaByTitle['Naruto']->id,
            $mediaByTitle['One Piece']->id,
        ]);
    }
}
