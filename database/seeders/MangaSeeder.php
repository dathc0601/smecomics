<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MangaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mangas = [
            [
                'title' => 'Solo Leveling',
                'slug' => 'solo-leveling',
                'description' => '10 years ago, after "the Gate" that connected the real world with the monster world opened, some of the ordinary, everyday people received the power to hunt monsters within the Gate. They are known as "Hunters". However, not all Hunters are powerful...',
                'type' => 'manhwa',
                'status' => 'completed',
                'is_18plus' => false,
                'author' => 'Chugong',
                'translation_team' => 'SME Comics Team',
                'release_year' => 2018,
                'is_featured' => true,
                'is_hot' => true,
                'genres' => [1, 2, 5, 14],
            ],
            [
                'title' => 'The Beginning After The End',
                'slug' => 'the-beginning-after-the-end',
                'description' => 'King Grey has unrivaled strength, wealth, and prestige in a world governed by martial ability. However, solitude lingers closely behind those with great power. Beneath the glamorous exterior of a powerful king lurks the shell of man...',
                'type' => 'manhwa',
                'status' => 'ongoing',
                'is_18plus' => false,
                'author' => 'TurtleMe',
                'translation_team' => 'SME Comics Team',
                'release_year' => 2018,
                'is_featured' => true,
                'is_hot' => true,
                'genres' => [1, 2, 5, 14],
            ],
            [
                'title' => 'Omniscient Reader\'s Viewpoint',
                'slug' => 'omniscient-readers-viewpoint',
                'description' => 'Dokja was an average office worker whose sole interest was reading his favorite web novel. But when the novel suddenly becomes reality, he is the only person who knows how the world will end...',
                'type' => 'manhwa',
                'status' => 'ongoing',
                'is_18plus' => false,
                'author' => 'Sing Shong',
                'translation_team' => 'SME Comics Team',
                'release_year' => 2020,
                'is_featured' => true,
                'is_hot' => true,
                'genres' => [1, 2, 5, 14],
            ],
            [
                'title' => 'Tower of God',
                'slug' => 'tower-of-god',
                'description' => 'What do you desire? Money and wealth? Honor and pride? Authority and power? Revenge? Or something that transcends them all? Whatever you desire—it\'s here.',
                'type' => 'manhwa',
                'status' => 'ongoing',
                'is_18plus' => false,
                'author' => 'SIU',
                'translation_team' => 'SME Comics Team',
                'release_year' => 2010,
                'is_featured' => true,
                'is_hot' => true,
                'genres' => [1, 2, 4, 5],
            ],
            [
                'title' => 'The God of High School',
                'slug' => 'the-god-of-high-school',
                'description' => 'While an island half-disappearing from the face of the earth, a mysterious organization is sending out invitations for a tournament to every skilled fighter in the world.',
                'type' => 'manhwa',
                'status' => 'ongoing',
                'is_18plus' => false,
                'author' => 'Yongje Park',
                'translation_team' => 'SME Comics Team',
                'release_year' => 2011,
                'is_featured' => false,
                'is_hot' => true,
                'genres' => [1, 3, 15],
            ],
        ];

        foreach ($mangas as $mangaData) {
            $genres = $mangaData['genres'];
            unset($mangaData['genres']);

            $manga = \App\Models\Manga::create($mangaData);
            $manga->genres()->attach($genres);

            // Create sample chapters for each manga
            for ($i = 1; $i <= 50; $i++) {
                $chapter = $manga->chapters()->create([
                    'chapter_number' => $i,
                    'title' => 'Chapter ' . $i,
                    'published_at' => now()->subDays(50 - $i),
                ]);

                // Create 10 placeholder images for each chapter
                for ($j = 1; $j <= 10; $j++) {
                    $chapter->images()->create([
                        'image_path' => 'placeholder-page-' . $j . '.jpg',
                        'order' => $j,
                    ]);
                }
            }
        }
    }
}
