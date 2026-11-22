<?php

namespace FlipPlay\Models;

/** Class induk untuk semua jenis media di FlipPlay. */
class Media
{
    // Encapsulation: data disimpan private dan dibaca melalui getter.
    private string $title;
    private array $genres;
    private float $rating;
    private int $year;
    private string $synopsis;

    // Constructor dipanggil saat object dibuat.
    public function __construct(
        string $title,
        array $genres,
        float $rating,
        int $year,
        string $synopsis
    ) {
        $this->title = $title;
        $this->genres = $genres;
        $this->rating = $rating;
        $this->year = $year;
        $this->synopsis = $synopsis;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getGenres(): array
    {
        return $this->genres;
    }

    public function getRating(): float
    {
        return $this->rating;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getSynopsis(): string
    {
        return $this->synopsis;
    }

    // Ditimpa (override) di class Anime, Manga, dan Drama.
    public function getType(): string
    {
        return 'Media';
    }

    public function getDetails(): string
    {
        return "Judul   : {$this->getTitle()}\n"
            . "Jenis   : {$this->getType()}\n"
            . 'Genre   : ' . implode(', ', $this->getGenres()) . "\n"
            . 'Rating  : ' . number_format($this->getRating(), 1) . " (data simulasi)\n"
            . "Tahun   : {$this->getYear()}\n"
            . "Sinopsis: {$this->getSynopsis()}";
    }
}
