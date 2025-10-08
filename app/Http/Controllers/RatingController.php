<?php

namespace App\Http\Controllers;

use App\Models\Manga;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request, Manga $manga)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        Rating::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'manga_id' => $manga->id,
            ],
            [
                'rating' => $validated['rating'],
            ]
        );

        // Recalculate manga average rating
        $avgRating = $manga->ratings()->avg('rating');
        $ratingCount = $manga->ratings()->count();

        $manga->update([
            'rating' => round($avgRating, 2),
            'rating_count' => $ratingCount,
        ]);

        return back()->with('success', 'Rating submitted successfully!');
    }
}
