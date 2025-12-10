<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $author = Author::first();
        $admin = User::where('is_admin', true)->first();

        $posts = [
            [
                'title' => 'Welcome to SME Comics Blog',
                'slug' => 'welcome-to-sme-comics-blog',
                'excerpt' => 'Introducing our new blog section where we share news, reviews, and more about the world of manga and manhwa.',
                'content' => '<p>Welcome to the official SME Comics blog! We\'re excited to launch this new section of our website where we\'ll be sharing:</p>
                <ul>
                    <li>Latest manga and manhwa news</li>
                    <li>In-depth reviews of popular series</li>
                    <li>Reading guides and recommendations</li>
                    <li>Industry insights and analysis</li>
                </ul>
                <h2>What to Expect</h2>
                <p>Our team of passionate manga enthusiasts will be bringing you regular content to enhance your reading experience. Whether you\'re looking for your next binge-worthy series or want to stay updated on the latest releases, we\'ve got you covered.</p>
                <p>Stay tuned for more exciting content coming your way!</p>',
                'status' => 'published',
                'published_at' => now()->subDays(7),
                'is_featured' => true,
                'author_id' => $author?->id,
                'created_by' => $admin?->id,
                'categories' => [1], // News
                'tags' => [],
            ],
            [
                'title' => 'Top 10 Manhwa You Should Be Reading Right Now',
                'slug' => 'top-10-manhwa-you-should-be-reading',
                'excerpt' => 'Our picks for the best manhwa that deserve your attention this season.',
                'content' => '<p>Manhwa has been taking the world by storm, and there\'s never been a better time to dive into Korean comics. Here are our top 10 recommendations:</p>
                <h2>1. Solo Leveling</h2>
                <p>The iconic series that sparked the "system" genre phenomenon. Follow Sung Jin-Woo as he transforms from the weakest hunter to something unprecedented.</p>
                <h2>2. Tower of God</h2>
                <p>An epic adventure that takes place in a mysterious tower where climbers seek to fulfill their wishes.</p>
                <h2>3. The Beginning After The End</h2>
                <p>A powerful king reincarnated in a world of magic must navigate his new life while keeping his past a secret.</p>
                <p>...and many more! Check back soon for the full list with detailed reviews.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(3),
                'is_featured' => true,
                'author_id' => $author?->id,
                'created_by' => $admin?->id,
                'categories' => [2, 3], // Reviews, Guides
                'tags' => [],
            ],
            [
                'title' => 'How to Get the Most Out of Your Reading Experience',
                'slug' => 'how-to-get-most-out-of-reading-experience',
                'excerpt' => 'Tips and tricks to enhance your manga and manhwa reading sessions.',
                'content' => '<p>Reading manga and manhwa is more than just flipping through pages. Here are some tips to maximize your enjoyment:</p>
                <h2>1. Find Your Perfect Reading Environment</h2>
                <p>Whether you prefer complete silence or background music, creating the right atmosphere can significantly enhance your reading experience.</p>
                <h2>2. Pace Yourself</h2>
                <p>While binge-reading can be tempting, taking breaks allows you to fully appreciate the artwork and story development.</p>
                <h2>3. Join the Community</h2>
                <p>Discussing your favorite series with other fans adds a whole new dimension to your experience. Leave comments, join discussions, and share your thoughts!</p>
                <blockquote>The best reading experience is one where you\'re fully immersed in the world of the story.</blockquote>',
                'status' => 'published',
                'published_at' => now()->subDays(1),
                'is_featured' => false,
                'author_id' => $author?->id,
                'created_by' => $admin?->id,
                'categories' => [5], // Tutorials
                'tags' => [],
            ],
            [
                'title' => 'Upcoming Features Preview - Draft',
                'slug' => 'upcoming-features-preview-draft',
                'excerpt' => 'A sneak peek at exciting features coming soon to SME Comics.',
                'content' => '<p>We\'re working on some exciting new features...</p>
                <p>This is a draft post that will be published when the features are ready to announce.</p>',
                'status' => 'draft',
                'published_at' => null,
                'is_featured' => false,
                'author_id' => $author?->id,
                'created_by' => $admin?->id,
                'categories' => [1], // News
                'tags' => [],
            ],
        ];

        $allTags = Tag::take(5)->pluck('id')->toArray();

        foreach ($posts as $postData) {
            $categoryIds = $postData['categories'];
            unset($postData['categories'], $postData['tags']);

            $post = BlogPost::create($postData);

            // Attach categories
            $post->categories()->attach($categoryIds);

            // Attach random tags to published posts
            if ($post->status === 'published' && !empty($allTags)) {
                $randomTags = array_slice($allTags, 0, rand(1, min(3, count($allTags))));
                $post->tags()->attach($randomTags);
            }
        }
    }
}
