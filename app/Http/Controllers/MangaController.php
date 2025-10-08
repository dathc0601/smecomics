<?php

namespace App\Http\Controllers;

use App\Models\Manga;
use App\Models\Genre;
use Illuminate\Http\Request;

class MangaController extends Controller
{
    public function index(Request $request)
    {
        $query = Manga::with(['genres', 'latestChapter']);

        // Filter by genre
        if ($request->filled('genre')) {
            $query->whereHas('genres', function ($q) use ($request) {
                $q->where('slug', $request->genre);
            });
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $mangas = $query->paginate(24);
        $genres = Genre::all();

        return view('manga.index', compact('mangas', 'genres'));
    }

    public function show(Manga $manga)
    {
        $manga->load(['genres', 'chapters', 'comments.user', 'comments.replies.user']);
        $manga->increment('view_count');

        // Hot titles for sidebar
        $hotTitles = Manga::where('is_hot', true)
            ->where('id', '!=', $manga->id)
            ->limit(10)
            ->get();

        // User bookmark status
        $isBookmarked = auth()->check() && auth()->user()->bookmarks()
            ->where('manga_id', $manga->id)->exists();

        // User rating
        $userRating = auth()->check() ? auth()->user()->ratings()
            ->where('manga_id', $manga->id)->value('rating') : null;

        return view('manga.show', compact('manga', 'hotTitles', 'isBookmarked', 'userRating'));
    }
}
