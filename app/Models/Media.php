<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * FlipPlay Media: data Anime, Manga, dan Drama disimpan pada satu tabel.
 * Ini menggantikan array dan subclass sementara pada demo PHP OOP Modul 3.
 */
class Media extends Model
{
    protected $table = 'media';

    protected $fillable = [
        'title', 'type', 'genres', 'rating', 'year', 'synopsis',
    ];

    protected function casts(): array
    {
        return [
            'genres' => 'array',
            'rating' => 'decimal:1',
            'year' => 'integer',
        ];
    }

    /** Satu media bisa dikoleksi banyak pengguna. */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'media_user')
            ->withTimestamps();
    }
}
