<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Manga;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function toggle(Manga $manga)
    {
        $bookmark = Bookmark::where('user_id', auth()->id())
            ->where('manga_id', $manga->id)
            ->first();

        if ($bookmark) {
            $bookmark->delete();
            $manga->decrement('follower_count');
            $message = 'Removed from bookmarks';
        } else {
            Bookmark::create([
                'user_id' => auth()->id(),
                'manga_id' => $manga->id,
            ]);
            $manga->increment('follower_count');
            $message = 'Added to bookmarks';
        }

        return back()->with('success', $message);
    }

    public function index()
    {
        $bookmarks = auth()->user()->bookmarks()
            ->with('manga.latestChapter')
            ->latest()
            ->paginate(20);

        return view('bookmarks.index', compact('bookmarks'));
    }
}
