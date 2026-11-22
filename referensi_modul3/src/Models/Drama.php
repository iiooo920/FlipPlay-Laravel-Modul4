<?php

namespace FlipPlay\Models;

class Drama extends Media
{
    public function getType(): string
    {
        return 'Drama';
    }
}
