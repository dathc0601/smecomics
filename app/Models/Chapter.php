<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Chapter extends Model
{
    protected $fillable = [
        'manga_id',
        'chapter_number',
        'title',
        'view_count',
        'published_at',
    ];

    protected $casts = [
        'chapter_number' => 'integer',
        'view_count' => 'integer',
        'published_at' => 'datetime',
    ];

    public function manga(): BelongsTo
    {
        return $this->belongsTo(Manga::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ChapterImage::class)->orderBy('order');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function readingHistories(): HasMany
    {
        return $this->hasMany(ReadingHistory::class);
    }
}
