<?php

namespace FlipPlay\Models;

// Inheritance: Anime mewarisi property, constructor, dan method Media.
class Anime extends Media
{
    public function getType(): string
    {
        return 'Anime';
    }
}
