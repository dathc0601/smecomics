<?php

namespace App\Http\Controllers;

use App\Models\Manga;
use App\Models\ReadingHistory;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Featured manga for carousel
        $featured = Manga::where('is_featured', true)
            ->with(['genres', 'latestChapter'])
            ->limit(5)
            ->get();

        // Newly updated manga
        $newlyUpdated = Manga::with(['genres', 'latestChapter'])
            ->orderBy('updated_at', 'desc')
            ->limit(12)
            ->get();

        // Hot titles
        $hotTitles = Manga::where('is_hot', true)
            ->with(['latestChapter'])
            ->limit(10)
            ->get();

        // Completed 18+ manga
        $completed18Plus = Manga::where('status', 'completed')
            ->where('is_18plus', true)
            ->with(['genres', 'latestChapter'])
            ->limit(6)
            ->get();

        // Reading history for all visitors
        $readingHistory = collect();
        if (auth()->check()) {
            // Authenticated user
            $readingHistory = ReadingHistory::where('user_id', auth()->id())
                ->with(['manga', 'chapter'])
                ->orderBy('last_read_at', 'desc')
                ->limit(8)
                ->get();
        } else {
            // Guest user
            $readingHistory = ReadingHistory::where('session_id', session()->getId())
                ->with(['manga', 'chapter'])
                ->orderBy('last_read_at', 'desc')
                ->limit(8)
                ->get();
        }

        return view('home', compact('featured', 'newlyUpdated', 'hotTitles', 'completed18Plus', 'readingHistory'));
    }
}
