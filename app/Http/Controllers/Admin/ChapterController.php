<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Manga, Chapter, ChapterImage};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChapterController extends Controller
{
    public function index(Request $request)
    {
        $query = Chapter::with('manga');

        if ($request->has('manga_id')) {
            $query->where('manga_id', $request->manga_id);
        }

        $chapters = $query->latest()->paginate(20);
        $mangas = Manga::all();

        return view('admin.chapters.index', compact('chapters', 'mangas'));
    }

    public function create()
    {
        $mangas = Manga::all();
        return view('admin.chapters.create', compact('mangas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'manga_id' => 'required|exists:mangas,id',
            'chapter_number' => 'required|numeric',
            'title' => 'nullable|string|max:255',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
        ]);

        $chapter = Chapter::create($validated);

        // Handle multiple image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('manga/chapters/' . $chapter->id, 'public');

                ChapterImage::create([
                    'chapter_id' => $chapter->id,
                    'image_path' => $path,
                    'order' => $index + 1,
                ]);
            }
        }

        return redirect()->route('admin.chapters.index')->with('success', 'Chapter created successfully!');
    }

    public function edit(Chapter $chapter)
    {
        $chapter->load('images');
        $mangas = Manga::all();
        return view('admin.chapters.edit', compact('chapter', 'mangas'));
    }

    public function update(Request $request, Chapter $chapter)
    {
        $validated = $request->validate([
            'manga_id' => 'required|exists:mangas,id',
            'chapter_number' => 'required|numeric',
            'title' => 'nullable|string|max:255',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
        ]);

        $chapter->update($validated);

        // Handle multiple image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('manga/chapters/' . $chapter->id, 'public');

                ChapterImage::create([
                    'chapter_id' => $chapter->id,
                    'image_path' => $path,
                    'order' => $chapter->images()->count() + $index + 1,
                ]);
            }
        }

        return redirect()->route('admin.chapters.index')->with('success', 'Chapter updated successfully!');
    }

    public function destroy(Chapter $chapter)
    {
        // Delete all chapter images
        foreach ($chapter->images as $image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
        }

        $chapter->delete();

        return redirect()->route('admin.chapters.index')->with('success', 'Chapter deleted successfully!');
    }

    public function deleteImage(Chapter $chapter, ChapterImage $image)
    {
        // Verify the image belongs to this chapter
        if ($image->chapter_id !== $chapter->id) {
            return response()->json(['success' => false, 'message' => 'Image not found'], 404);
        }

        // Delete the physical file
        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        // Delete the database record
        $image->delete();

        // Reorder remaining images
        $remainingImages = $chapter->images()->orderBy('order')->get();
        foreach ($remainingImages as $index => $img) {
            $img->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true, 'message' => 'Image deleted successfully']);
    }

    public function reorderImages(Request $request, Chapter $chapter)
    {
        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'required|integer|exists:chapter_images,id'
        ]);

        foreach ($validated['order'] as $index => $imageId) {
            ChapterImage::where('id', $imageId)
                ->where('chapter_id', $chapter->id)
                ->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true, 'message' => 'Images reordered successfully']);
    }

    public function uploadImages(Request $request, Chapter $chapter)
    {
        $validated = $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
        ]);

        $uploadedImages = [];
        $currentMaxOrder = $chapter->images()->max('order') ?? 0;

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('manga/chapters/' . $chapter->id, 'public');

                $chapterImage = ChapterImage::create([
                    'chapter_id' => $chapter->id,
                    'image_path' => $path,
                    'order' => $currentMaxOrder + $index + 1,
                ]);

                $uploadedImages[] = [
                    'id' => $chapterImage->id,
                    'url' => asset('storage/' . $chapterImage->image_path),
                    'order' => $chapterImage->order,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'message' => count($uploadedImages) . ' image(s) uploaded successfully',
            'images' => $uploadedImages
        ]);
    }
}
