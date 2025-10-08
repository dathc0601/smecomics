<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = \App\Models\Author::withCount('mangas')->latest()->paginate(20);
        return view('admin.authors.index', compact('authors'));
    }

    public function create()
    {
        return view('admin.authors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:authors,slug',
            'bio' => 'nullable|string',
            'website' => 'nullable|url',
        ]);

        \App\Models\Author::create($validated);

        return redirect()->route('admin.authors.index')->with('success', 'Author created successfully!');
    }

    public function edit(\App\Models\Author $author)
    {
        return view('admin.authors.edit', compact('author'));
    }

    public function update(Request $request, \App\Models\Author $author)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:authors,slug,' . $author->id,
            'bio' => 'nullable|string',
            'website' => 'nullable|url',
        ]);

        $author->update($validated);

        return redirect()->route('admin.authors.index')->with('success', 'Author updated successfully!');
    }

    public function destroy(\App\Models\Author $author)
    {
        $author->delete();

        return redirect()->route('admin.authors.index')->with('success', 'Author deleted successfully!');
    }
}
