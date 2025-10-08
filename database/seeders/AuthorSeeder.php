<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AuthorSeeder extends Seeder
{
    public function run(): void
    {
        $authors = [
            ['name' => 'Chugong', 'bio' => 'Author of Solo Leveling'],
            ['name' => 'TurtleMe', 'bio' => 'Author of The Beginning After The End'],
            ['name' => 'Sing Shong', 'bio' => 'Author of Omniscient Reader\'s Viewpoint'],
            ['name' => 'SIU', 'bio' => 'Author of Tower of God'],
            ['name' => 'Yongje Park', 'bio' => 'Author of The God of High School'],
            ['name' => 'Redice Studio', 'bio' => 'Illustration studio'],
            ['name' => 'Fuyuki23', 'bio' => 'Illustration artist'],
        ];

        foreach ($authors as $author) {
            Author::create([
                'name' => $author['name'],
                'slug' => Str::slug($author['name']),
                'bio' => $author['bio'],
            ]);
        }
    }
}
