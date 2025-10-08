<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            'Overpowered MC',
            'Leveling System',
            'Dungeon Crawling',
            'Weak to Strong',
            'Reincarnation',
            'Time Travel',
            'Revenge',
            'Magic Academy',
            'Game Elements',
            'Beast Taming',
            'Cultivation',
            'Modern Fantasy',
            'Regression',
            'System',
            'Hunter',
        ];

        foreach ($tags as $tag) {
            Tag::create([
                'name' => $tag,
                'slug' => Str::slug($tag),
            ]);
        }
    }
}
