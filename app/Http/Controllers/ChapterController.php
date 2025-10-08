<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Manga;
use App\Models\ReadingHistory;
use Illuminate\Http\Request;

class ChapterController extends Controller
{
    public function show(Manga $manga, $chapterNumber)
    {
        $chapter = Chapter::where('manga_id', $manga->id)
            ->where('chapter_number', $chapterNumber)
            ->with(['images', 'comments.user'])
            ->firstOrFail();

        $chapter->increment('view_count');

        // Get previous and next chapters
        $prevChapter = Chapter::where('manga_id', $manga->id)
            ->where('chapter_number', '<', $chapterNumber)
            ->orderBy('chapter_number', 'desc')
            ->first();

        $nextChapter = Chapter::where('manga_id', $manga->id)
            ->where('chapter_number', '>', $chapterNumber)
            ->orderBy('chapter_number', 'asc')
            ->first();

        // Track reading history for all visitors
        if (auth()->check()) {
            // Authenticated user
            ReadingHistory::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'manga_id' => $manga->id,
                    'session_id' => null,
                ],
                [
                    'chapter_id' => $chapter->id,
                    'last_read_at' => now(),
                ]
            );
        } else {
            // Guest user - track by session
            ReadingHistory::updateOrCreate(
                [
                    'session_id' => session()->getId(),
                    'manga_id' => $manga->id,
                    'user_id' => null,
                ],
                [
                    'chapter_id' => $chapter->id,
                    'last_read_at' => now(),
                ]
            );
        }

        return view('chapter.show', compact('manga', 'chapter', 'prevChapter', 'nextChapter'));
    }
}
