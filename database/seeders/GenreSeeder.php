<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genres = [
            ['name' => 'Action', 'slug' => 'action'],
            ['name' => 'Adventure', 'slug' => 'adventure'],
            ['name' => 'Comedy', 'slug' => 'comedy'],
            ['name' => 'Drama', 'slug' => 'drama'],
            ['name' => 'Fantasy', 'slug' => 'fantasy'],
            ['name' => 'Horror', 'slug' => 'horror'],
            ['name' => 'Mystery', 'slug' => 'mystery'],
            ['name' => 'Romance', 'slug' => 'romance'],
            ['name' => 'Sci-Fi', 'slug' => 'sci-fi'],
            ['name' => 'Slice of Life', 'slug' => 'slice-of-life'],
            ['name' => 'Sports', 'slug' => 'sports'],
            ['name' => 'Supernatural', 'slug' => 'supernatural'],
            ['name' => 'Thriller', 'slug' => 'thriller'],
            ['name' => 'Isekai', 'slug' => 'isekai'],
            ['name' => 'Martial Arts', 'slug' => 'martial-arts'],
            ['name' => 'School Life', 'slug' => 'school-life'],
            ['name' => 'Historical', 'slug' => 'historical'],
            ['name' => 'Psychological', 'slug' => 'psychological'],
            ['name' => 'Harem', 'slug' => 'harem'],
            ['name' => 'Mature', 'slug' => 'mature'],
        ];

        \App\Models\Genre::insert($genres);
    }
}
