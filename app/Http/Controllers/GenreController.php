<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Manga;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function show(Genre $genre, Request $request)
    {
        $query = Manga::with(['genres', 'latestChapter'])
            ->whereHas('genres', function ($q) use ($genre) {
                $q->where('genres.id', $genre->id);
            });

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

        return view('genre.show', compact('genre', 'mangas', 'genres'));
    }
}
