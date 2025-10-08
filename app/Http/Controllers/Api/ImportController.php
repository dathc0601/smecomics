<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{Manga, Chapter, ChapterImage, Genre, Tag};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Storage, Validator, DB};
use Illuminate\Support\Str;

class ImportController extends Controller
{
    /**
     * Import a manga with metadata and cover image
     *
     * Expected request format:
     * - title: string (required)
     * - slug: string (optional, auto-generated from title if not provided)
     * - description: string (optional)
     * - cover_image: file (optional, image file)
     * - genres: array (optional, array of genre names)
     * - tags: array (optional, array of tag names)
     * - type: string (optional: manhwa|manga|novel, default: manhwa)
     * - status: string (optional: ongoing|completed|hiatus, default: ongoing)
     * - translation_team: string (optional)
     */
    public function importManga(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:mangas,slug',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:20480',
            'genres' => 'nullable|array',
            'genres.*' => 'string',
            'tags' => 'nullable|array',
            'tags.*' => 'string',
            'type' => 'nullable|in:manhwa,manga,novel',
            'status' => 'nullable|in:ongoing,completed,hiatus',
            'translation_team' => 'nullable|string|max:255',
            'is_18plus' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Generate slug if not provided
            $slug = $request->slug ?? Str::slug($request->title);

            // Check for duplicate slug
            if (Manga::where('slug', $slug)->exists()) {
                $slug = $slug . '-' . uniqid();
            }

            // Handle cover image upload
            $coverImagePath = null;
            if ($request->hasFile('cover_image')) {
                $coverImagePath = $request->file('cover_image')->store('manga/covers', 'public');
            }

            // Determine if manga is 18+
            $is18Plus = $request->is_18plus ?? $this->checkIs18Plus($request->genres ?? []);

            // Create manga
            $manga = Manga::create([
                'title' => $request->title,
                'slug' => $slug,
                'description' => $request->description,
                'cover_image' => $coverImagePath,
                'type' => $request->type ?? $this->determineType($request->genres ?? []),
                'status' => $request->status ?? 'ongoing',
                'is_18plus' => $is18Plus,
                'translation_team' => $request->translation_team,
                'view_count' => 0,
                'follower_count' => 0,
                'rating' => 0,
                'rating_count' => 0,
                'is_featured' => false,
                'is_hot' => false,
            ]);

            // Handle genres
            if ($request->has('genres') && is_array($request->genres)) {
                $genreIds = [];
                foreach ($request->genres as $genreName) {
                    $genre = Genre::firstOrCreate(
                        ['slug' => Str::slug($genreName)],
                        ['name' => $genreName]
                    );
                    $genreIds[] = $genre->id;
                }
                $manga->genres()->attach($genreIds);
            }

            // Handle tags
            if ($request->has('tags') && is_array($request->tags)) {
                $tagIds = [];
                foreach ($request->tags as $tagName) {
                    $tag = Tag::firstOrCreate(
                        ['slug' => Str::slug($tagName)],
                        ['name' => $tagName]
                    );
                    $tagIds[] = $tag->id;
                }
                $manga->tags()->attach($tagIds);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Manga imported successfully',
                'data' => [
                    'id' => $manga->id,
                    'title' => $manga->title,
                    'slug' => $manga->slug,
                    'cover_image_url' => $manga->cover_image ? asset('storage/' . $manga->cover_image) : null,
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            // Delete uploaded cover image if manga creation failed
            if (isset($coverImagePath) && Storage::disk('public')->exists($coverImagePath)) {
                Storage::disk('public')->delete($coverImagePath);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to import manga',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Import chapters with images for a manga
     *
     * Expected request format:
     * - chapters: array (required, array of chapter objects)
     *   - chapter_number: numeric (required)
     *   - title: string (optional)
     *   - images: array (required, array of image files)
     */
    public function importChapters(Request $request, Manga $manga)
    {
        $validator = Validator::make($request->all(), [
            'chapters' => 'required|array',
            'chapters.*.chapter_number' => 'required|numeric',
            'chapters.*.title' => 'nullable|string|max:255',
            'chapters.*.images' => 'required|array',
            'chapters.*.images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:20480',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $importedChapters = [];
            $uploadedFiles = []; // Track uploaded files for cleanup on error

            foreach ($request->chapters as $chapterData) {
                // Check if chapter already exists
                $existingChapter = Chapter::where('manga_id', $manga->id)
                    ->where('chapter_number', $chapterData['chapter_number'])
                    ->first();

                if ($existingChapter) {
                    continue; // Skip existing chapters
                }

                // Create chapter
                $chapter = Chapter::create([
                    'manga_id' => $manga->id,
                    'chapter_number' => $chapterData['chapter_number'],
                    'title' => $chapterData['title'] ?? null,
                    'view_count' => 0,
                    'published_at' => now(),
                ]);

                // Upload and save images
                if (isset($chapterData['images']) && is_array($chapterData['images'])) {
                    foreach ($chapterData['images'] as $index => $image) {
                        $path = $image->store('manga/chapters/' . $chapter->id, 'public');
                        $uploadedFiles[] = $path;

                        ChapterImage::create([
                            'chapter_id' => $chapter->id,
                            'image_path' => $path,
                            'order' => $index + 1,
                        ]);
                    }
                }

                $importedChapters[] = [
                    'id' => $chapter->id,
                    'chapter_number' => $chapter->chapter_number,
                    'title' => $chapter->title,
                    'images_count' => count($chapterData['images'] ?? []),
                ];
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => count($importedChapters) . ' chapter(s) imported successfully',
                'data' => [
                    'manga' => [
                        'id' => $manga->id,
                        'title' => $manga->title,
                        'slug' => $manga->slug,
                    ],
                    'imported_chapters' => $importedChapters,
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            // Clean up uploaded files
            foreach ($uploadedFiles as $filePath) {
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to import chapters',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all available genres
     */
    public function getGenres()
    {
        $genres = Genre::orderBy('name')->get(['id', 'name', 'slug']);

        return response()->json([
            'success' => true,
            'data' => $genres
        ]);
    }

    /**
     * API status check
     */
    public function status()
    {
        return response()->json([
            'success' => true,
            'message' => 'Import API is ready',
            'timestamp' => now()->toIso8601String(),
            'statistics' => [
                'total_manga' => Manga::count(),
                'total_chapters' => Chapter::count(),
                'total_genres' => Genre::count(),
                'total_tags' => Tag::count(),
            ]
        ]);
    }

    /**
     * Helper: Check if manga should be marked as 18+
     */
    private function checkIs18Plus(array $genres): bool
    {
        $adultKeywords = ['19+', 'adult', '18+', 'mature', 'smut'];

        foreach ($genres as $genre) {
            $genreLower = strtolower($genre);
            foreach ($adultKeywords as $keyword) {
                if (str_contains($genreLower, $keyword)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Helper: Determine manga type from genres
     */
    private function determineType(array $genres): string
    {
        foreach ($genres as $genre) {
            $genreLower = strtolower($genre);
            if (str_contains($genreLower, 'manhwa')) {
                return 'manhwa';
            }
            if (str_contains($genreLower, 'manga')) {
                return 'manga';
            }
            if (str_contains($genreLower, 'novel')) {
                return 'novel';
            }
        }

        // Default to manhwa since most of the crawled data appears to be manhwa
        return 'manhwa';
    }
}
