<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use Illuminate\Console\Command;

class PublishScheduledPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blog:publish-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish scheduled blog posts that are due';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $posts = BlogPost::where('status', 'scheduled')
            ->where('published_at', '<=', now())
            ->get();

        if ($posts->isEmpty()) {
            $this->info('No scheduled posts to publish.');
            return Command::SUCCESS;
        }

        foreach ($posts as $post) {
            $post->update(['status' => 'published']);
            $this->info("Published: {$post->title}");
        }

        $this->info("Published {$posts->count()} post(s).");

        return Command::SUCCESS;
    }
}
