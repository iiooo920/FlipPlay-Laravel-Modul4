<?php

namespace FlipPlay\Models;

class Manga extends Media
{
    public function getType(): string
    {
        return 'Manga';
    }
}
