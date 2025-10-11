<?php

namespace App\Services;

use App\Models\Manga;
use App\Models\Chapter;
use App\Models\Genre;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;

class SitemapGenerator
{
    protected string $publicPath;
    protected string $baseUrl;

    public function __construct()
    {
        $this->publicPath = public_path();
        $this->baseUrl = config('app.url');
    }

    /**
     * Generate all sitemap files
     */
    public function generateAll(): array
    {
        $stats = [
            'pages' => $this->generatePagesSitemap(),
            'manga' => $this->generateMangaSitemap(),
            'chapters' => $this->generateChaptersSitemap(),
            'genres' => $this->generateGenresSitemap(),
        ];

        $this->generateSitemapIndex();

        return $stats;
    }

    /**
     * Generate main sitemap index
     */
    protected function generateSitemapIndex(): void
    {
        $sitemaps = [
            'sitemap-pages.xml',
            'sitemap-manga.xml',
            'sitemap-chapters.xml',
            'sitemap-genres.xml',
        ];

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($sitemaps as $sitemap) {
            $lastmod = date('Y-m-d');
            $xml .= '  <sitemap>' . PHP_EOL;
            $xml .= '    <loc>' . $this->baseUrl . '/' . $sitemap . '</loc>' . PHP_EOL;
            $xml .= '    <lastmod>' . $lastmod . '</lastmod>' . PHP_EOL;
            $xml .= '  </sitemap>' . PHP_EOL;
        }

        $xml .= '</sitemapindex>';

        File::put($this->publicPath . '/sitemap.xml', $xml);
    }

    /**
     * Generate static pages sitemap
     */
    protected function generatePagesSitemap(): int
    {
        $urls = [
            [
                'loc' => $this->baseUrl,
                'lastmod' => date('Y-m-d'),
                'changefreq' => 'daily',
                'priority' => '1.0',
            ],
            [
                'loc' => $this->baseUrl . '/manga',
                'lastmod' => date('Y-m-d'),
                'changefreq' => 'daily',
                'priority' => '0.9',
            ],
        ];

        $xml = $this->buildUrlset($urls);
        File::put($this->publicPath . '/sitemap-pages.xml', $xml);

        return count($urls);
    }

    /**
     * Generate manga sitemap
     */
    protected function generateMangaSitemap(): int
    {
        $urls = [];

        Manga::select('slug', 'updated_at')
            ->chunk(1000, function ($mangas) use (&$urls) {
                foreach ($mangas as $manga) {
                    $urls[] = [
                        'loc' => route('manga.show', $manga->slug),
                        'lastmod' => $manga->updated_at->format('Y-m-d'),
                        'changefreq' => 'daily',
                        'priority' => '0.8',
                    ];
                }
            });

        $xml = $this->buildUrlset($urls);
        File::put($this->publicPath . '/sitemap-manga.xml', $xml);

        return count($urls);
    }

    /**
     * Generate chapters sitemap
     */
    protected function generateChaptersSitemap(): int
    {
        $urls = [];

        Chapter::with('manga:id,slug')
            ->select('id', 'manga_id', 'chapter_number', 'updated_at')
            ->chunk(1000, function ($chapters) use (&$urls) {
                foreach ($chapters as $chapter) {
                    if ($chapter->manga) {
                        $urls[] = [
                            'loc' => route('chapter.show', [
                                'manga' => $chapter->manga->slug,
                                'chapterNumber' => $chapter->chapter_number
                            ]),
                            'lastmod' => $chapter->updated_at->format('Y-m-d'),
                            'changefreq' => 'weekly',
                            'priority' => '0.6',
                        ];
                    }
                }
            });

        $xml = $this->buildUrlset($urls);
        File::put($this->publicPath . '/sitemap-chapters.xml', $xml);

        return count($urls);
    }

    /**
     * Generate genres sitemap
     */
    protected function generateGenresSitemap(): int
    {
        $urls = [];

        Genre::select('slug', 'updated_at')
            ->chunk(1000, function ($genres) use (&$urls) {
                foreach ($genres as $genre) {
                    $urls[] = [
                        'loc' => $this->baseUrl . '/manga?genre=' . $genre->slug,
                        'lastmod' => $genre->updated_at->format('Y-m-d'),
                        'changefreq' => 'weekly',
                        'priority' => '0.7',
                    ];
                }
            });

        $xml = $this->buildUrlset($urls);
        File::put($this->publicPath . '/sitemap-genres.xml', $xml);

        return count($urls);
    }

    /**
     * Build XML urlset from URLs array
     */
    protected function buildUrlset(array $urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($urls as $url) {
            $xml .= '  <url>' . PHP_EOL;
            $xml .= '    <loc>' . htmlspecialchars($url['loc']) . '</loc>' . PHP_EOL;

            if (isset($url['lastmod'])) {
                $xml .= '    <lastmod>' . $url['lastmod'] . '</lastmod>' . PHP_EOL;
            }

            if (isset($url['changefreq'])) {
                $xml .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . PHP_EOL;
            }

            if (isset($url['priority'])) {
                $xml .= '    <priority>' . $url['priority'] . '</priority>' . PHP_EOL;
            }

            $xml .= '  </url>' . PHP_EOL;
        }

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * Get sitemap file path
     */
    public function getSitemapPath(string $type = 'index'): string
    {
        $filename = $type === 'index' ? 'sitemap.xml' : "sitemap-{$type}.xml";
        return $this->publicPath . '/' . $filename;
    }

    /**
     * Check if sitemap exists
     */
    public function exists(string $type = 'index'): bool
    {
        return File::exists($this->getSitemapPath($type));
    }
}
