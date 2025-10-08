<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'bio',
        'website',
    ];

    public function mangas(): HasMany
    {
        return $this->hasMany(Manga::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
