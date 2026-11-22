<?php

namespace FlipPlay\Services;

use FlipPlay\Models\Media;

/** Koleksi pribadi user pada simulasi FlipPlay. */
class UserCollection
{
    private string $userName;
    /** @var Media[] */
    private array $items = [];

    public function __construct(string $userName)
    {
        $this->userName = $userName;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    // Mengembalikan false jika object yang sama sudah ada di koleksi.
    public function addMedia(Media $media): bool
    {
        foreach ($this->items as $item) {
            if ($item === $media) {
                return false;
            }
        }
        $this->items[] = $media;
        return true;
    }

    /** @return Media[] */
    public function getItems(): array
    {
        return $this->items;
    }
}
