<?php

use App\Http\Controllers\{
    HomeController,
    MangaController,
    ChapterController,
    CommentController,
    BookmarkController,
    RatingController,
    ProfileController
};
use App\Http\Controllers\Admin\{
    DashboardController as AdminDashboardController,
    MangaController as AdminMangaController,
    ChapterController as AdminChapterController,
    GenreController as AdminGenreController,
    AuthorController as AdminAuthorController,
    TagController as AdminTagController,
    UserController as AdminUserController
};
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Manga routes
Route::get('/manga', [MangaController::class, 'index'])->name('manga.index');
Route::get('/manga/{manga}', [MangaController::class, 'show'])->name('manga.show');

// Chapter reader
Route::get('/manga/{manga}/chapter/{chapterNumber}', [ChapterController::class, 'show'])->name('chapter.show');

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Comments
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Bookmarks
    Route::post('/manga/{manga}/bookmark', [BookmarkController::class, 'toggle'])->name('bookmarks.toggle');
    Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');

    // Ratings
    Route::post('/manga/{manga}/rating', [RatingController::class, 'store'])->name('ratings.store');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('manga', AdminMangaController::class)->except(['show']);
    Route::resource('chapters', AdminChapterController::class)->except(['show']);

    // Chapter image management routes
    Route::delete('chapters/{chapter}/images/{image}', [AdminChapterController::class, 'deleteImage'])->name('chapters.images.delete');
    Route::post('chapters/{chapter}/images/reorder', [AdminChapterController::class, 'reorderImages'])->name('chapters.images.reorder');
    Route::post('chapters/{chapter}/images/upload', [AdminChapterController::class, 'uploadImages'])->name('chapters.images.upload');

    Route::resource('genres', AdminGenreController::class)->except(['show']);
    Route::resource('authors', AdminAuthorController::class)->except(['show']);
    Route::resource('tags', AdminTagController::class)->except(['show']);
    Route::resource('users', AdminUserController::class)->only(['index', 'edit', 'update', 'destroy']);
    Route::post('users/{user}/toggle-admin', [AdminUserController::class, 'toggleAdmin'])->name('users.toggle-admin');
});

require __DIR__.'/auth.php';
