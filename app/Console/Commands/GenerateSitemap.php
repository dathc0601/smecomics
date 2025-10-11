<?php

namespace App\Console\Commands;

use App\Services\SitemapGenerator;
use Illuminate\Console\Command;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate
                            {--force : Force regenerate even if sitemaps exist}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate XML sitemaps for the website';

    /**
     * Execute the console command.
     */
    public function handle(SitemapGenerator $generator): int
    {
        $this->info('🗺️  Starting sitemap generation...');
        $this->newLine();

        // Check if sitemaps already exist
        if (!$this->option('force') && $generator->exists()) {
            if (!$this->confirm('Sitemaps already exist. Do you want to regenerate them?', true)) {
                $this->warn('Sitemap generation cancelled.');
                return self::SUCCESS;
            }
        }

        // Generate all sitemaps
        $startTime = microtime(true);

        try {
            $stats = $generator->generateAll();

            $this->newLine();
            $this->info('✅ Sitemaps generated successfully!');
            $this->newLine();

            // Display statistics
            $this->table(
                ['Sitemap', 'URLs', 'File'],
                [
                    ['Static Pages', $stats['pages'], 'sitemap-pages.xml'],
                    ['Manga', $stats['manga'], 'sitemap-manga.xml'],
                    ['Chapters', $stats['chapters'], 'sitemap-chapters.xml'],
                    ['Genres', $stats['genres'], 'sitemap-genres.xml'],
                    ['Index', '4 sitemaps', 'sitemap.xml'],
                ]
            );

            $totalUrls = array_sum($stats);
            $duration = round(microtime(true) - $startTime, 2);

            $this->newLine();
            $this->info("📊 Total URLs: {$totalUrls}");
            $this->info("⏱️  Duration: {$duration}s");
            $this->newLine();

            // Show sitemap locations
            $this->comment('Sitemap files generated in: ' . public_path());
            $this->comment('Main sitemap: ' . config('app.url') . '/sitemap.xml');
            $this->newLine();

            // Remind about robots.txt
            $this->warn('💡 Don\'t forget to add the sitemap to your robots.txt file:');
            $this->line('   Sitemap: ' . config('app.url') . '/sitemap.xml');

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('❌ Error generating sitemaps: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
