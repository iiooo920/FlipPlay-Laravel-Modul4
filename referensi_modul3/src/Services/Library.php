<?php

namespace FlipPlay\Services;

use FlipPlay\Models\Media;

/** Mengelola daftar media, pencarian, dan filter ala Modul 2. */
class Library
{
    /** @var Media[] */
    private array $mediaList = [];

    // Type hinting: parameter wajib berupa turunan Media.
    // Return type: method tidak mengembalikan nilai.
    public function addMedia(Media $media): void
    {
        $this->mediaList[] = $media;
    }

    /** @return Media[] */
    public function getAllMedia(): array
    {
        return $this->mediaList;
    }

    /** @return Media[] */
    public function searchByTitle(string $keyword): array
    {
        return $this->filterMedia($keyword);
    }

    /**
     * Filter jenis media, judul, dan Aura seperti DEMO MODUL 2.
     * Genre Aura hanyalah pengelompokan untuk contoh, bukan kategori resmi API.
     *
     * @return Media[]
     */
    public function filterMedia(string $keyword = '', string $type = '', string $aura = ''): array
    {
        $auraGenres = [
            'High Energy' => ['Action', 'Adventure'],
            'Melancholy' => ['Drama', 'Romance', 'Slice of Life'],
            'Mind Bender' => ['Mystery', 'Psychological', 'Thriller', 'Sci-Fi', 'Science-Fiction'],
            'Binge Mode' => ['Comedy', 'Adventure', 'Fantasy'],
            'Escapism' => ['Fantasy', 'Adventure', 'Supernatural'],
        ];

        $matches = [];
        foreach ($this->mediaList as $media) {
            $titleMatches = stripos($media->getTitle(), trim($keyword)) !== false;
            $typeMatches = $type === '' || $media->getType() === $type;

            $auraMatches = $aura === '';
            if ($aura !== '' && isset($auraGenres[$aura])) {
                foreach ($media->getGenres() as $genre) {
                    if (in_array($genre, $auraGenres[$aura], true)) {
                        $auraMatches = true;
                        break;
                    }
                }
            }

            if ($titleMatches && $typeMatches && $auraMatches) {
                $matches[] = $media;
            }
        }

        return $matches;
    }

    // ?Media berarti hasilnya object Media atau null jika tidak ditemukan.
    public function findByTitle(string $title): ?Media
    {
        foreach ($this->mediaList as $media) {
            if (strcasecmp($media->getTitle(), trim($title)) === 0) {
                return $media;
            }
        }
        return null;
    }
}
