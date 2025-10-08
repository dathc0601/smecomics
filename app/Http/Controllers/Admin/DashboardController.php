<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Manga, Chapter, User, Comment};

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_manga' => Manga::count(),
            'total_chapters' => Chapter::count(),
            'total_users' => User::count(),
            'total_comments' => Comment::count(),
            'recent_manga' => Manga::with('author')->latest()->take(5)->get(),
            'recent_chapters' => Chapter::with('manga')->latest()->take(5)->get(),
        ];

        return view('admin.dashboard', $stats);
    }
}
