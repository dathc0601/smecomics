<?php

namespace App\Http\Controllers;

use App\Services\SitemapGenerator;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    protected SitemapGenerator $generator;

    public function __construct(SitemapGenerator $generator)
    {
        $this->generator = $generator;
    }

    /**
     * Show the main sitemap index
     */
    public function index(): Response
    {
        return $this->serveSitemap('index');
    }

    /**
     * Show a specific sitemap
     */
    public function show(string $type): Response
    {
        $allowedTypes = ['pages', 'manga', 'chapters', 'genres'];

        if (!in_array($type, $allowedTypes)) {
            abort(404);
        }

        return $this->serveSitemap($type);
    }

    /**
     * Serve sitemap XML file
     */
    protected function serveSitemap(string $type): Response
    {
        $path = $this->generator->getSitemapPath($type);

        if (!file_exists($path)) {
            abort(404, 'Sitemap not found. Please run: php artisan sitemap:generate');
        }

        $content = file_get_contents($path);

        return response($content, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
