<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Manga extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'cover_image',
        'type',
        'status',
        'is_18plus',
        'author',
        'author_id',
        'translation_team',
        'release_year',
        'view_count',
        'follower_count',
        'rating',
        'rating_count',
        'is_featured',
        'is_hot',
    ];

    protected $casts = [
        'is_18plus' => 'boolean',
        'is_featured' => 'boolean',
        'is_hot' => 'boolean',
        'view_count' => 'integer',
        'follower_count' => 'integer',
        'rating' => 'decimal:2',
        'rating_count' => 'integer',
    ];

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class)->orderBy('chapter_number');
    }

    public function latestChapter()
    {
        return $this->hasOne(Chapter::class)->latestOfMany('chapter_number');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function readingHistories(): HasMany
    {
        return $this->hasMany(ReadingHistory::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
