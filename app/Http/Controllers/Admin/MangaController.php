<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Manga, Genre, Author, Tag};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MangaController extends Controller
{
    public function index(Request $request)
    {
        $query = Manga::with(['author', 'genres', 'tags']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $mangas = $query->latest()->paginate(20);

        return view('admin.manga.index', compact('mangas'));
    }

    public function create()
    {
        $genres = Genre::all();
        $authors = Author::all();
        $tags = Tag::all();

        return view('admin.manga.create', compact('genres', 'authors', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:mangas,slug',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
            'type' => 'required|in:manhwa,manga,novel',
            'status' => 'required|in:ongoing,completed,hiatus',
            'is_18plus' => 'boolean',
            'author_id' => 'nullable|exists:authors,id',
            'translation_team' => 'nullable|string|max:255',
            'release_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'is_featured' => 'boolean',
            'is_hot' => 'boolean',
            'genres' => 'nullable|array',
            'genres.*' => 'exists:genres,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        // Handle image upload
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('manga/covers', 'public');
        }

        $manga = Manga::create($validated);

        // Attach genres and tags
        if ($request->has('genres')) {
            $manga->genres()->attach($request->genres);
        }

        if ($request->has('tags')) {
            $manga->tags()->attach($request->tags);
        }

        return redirect()->route('admin.manga.index')->with('success', 'Manga created successfully!');
    }

    public function edit(Manga $manga)
    {
        $manga->load(['genres', 'tags', 'author']);
        $genres = Genre::all();
        $authors = Author::all();
        $tags = Tag::all();

        return view('admin.manga.edit', compact('manga', 'genres', 'authors', 'tags'));
    }

    public function update(Request $request, Manga $manga)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:mangas,slug,' . $manga->id,
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
            'type' => 'required|in:manhwa,manga,novel',
            'status' => 'required|in:ongoing,completed,hiatus',
            'is_18plus' => 'boolean',
            'author_id' => 'nullable|exists:authors,id',
            'translation_team' => 'nullable|string|max:255',
            'release_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'is_featured' => 'boolean',
            'is_hot' => 'boolean',
            'genres' => 'nullable|array',
            'genres.*' => 'exists:genres,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        // Handle image upload
        if ($request->hasFile('cover_image')) {
            // Delete old image
            if ($manga->cover_image && Storage::disk('public')->exists($manga->cover_image)) {
                Storage::disk('public')->delete($manga->cover_image);
            }

            $validated['cover_image'] = $request->file('cover_image')->store('manga/covers', 'public');
        }

        $manga->update($validated);

        // Sync genres and tags
        if ($request->has('genres')) {
            $manga->genres()->sync($request->genres);
        } else {
            $manga->genres()->detach();
        }

        if ($request->has('tags')) {
            $manga->tags()->sync($request->tags);
        } else {
            $manga->tags()->detach();
        }

        return redirect()->route('admin.manga.index')->with('success', 'Manga updated successfully!');
    }

    public function destroy(Manga $manga)
    {
        // Delete cover image
        if ($manga->cover_image && Storage::disk('public')->exists($manga->cover_image)) {
            Storage::disk('public')->delete($manga->cover_image);
        }

        $manga->delete();

        return redirect()->route('admin.manga.index')->with('success', 'Manga deleted successfully!');
    }
}
