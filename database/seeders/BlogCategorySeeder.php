<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use Illuminate\Database\Seeder;

class BlogCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'News',
                'slug' => 'news',
                'description' => 'Latest news and announcements from the manga world',
            ],
            [
                'name' => 'Reviews',
                'slug' => 'reviews',
                'description' => 'In-depth manga and manhwa reviews',
            ],
            [
                'name' => 'Guides',
                'slug' => 'guides',
                'description' => 'Reading guides and recommendations',
            ],
            [
                'name' => 'Industry',
                'slug' => 'industry',
                'description' => 'Manga industry news and analysis',
            ],
            [
                'name' => 'Tutorials',
                'slug' => 'tutorials',
                'description' => 'How-to guides and tutorials',
            ],
        ];

        foreach ($categories as $category) {
            BlogCategory::create($category);
        }
    }
}
