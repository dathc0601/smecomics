<?php

use App\Http\Controllers\{
    HomeController,
    MangaController,
    GenreController,
    ChapterController,
    CommentController,
    BookmarkController,
    RatingController,
    ProfileController,
    SitemapController,
    BlogController
};
use App\Http\Controllers\Admin\{
    DashboardController as AdminDashboardController,
    MangaController as AdminMangaController,
    ChapterController as AdminChapterController,
    GenreController as AdminGenreController,
    AuthorController as AdminAuthorController,
    TagController as AdminTagController,
    UserController as AdminUserController,
    SettingController as AdminSettingController,
    BlogPostController as AdminBlogPostController,
    BlogCategoryController as AdminBlogCategoryController,
    UploadController as AdminUploadController
};
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Sitemap routes
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/sitemap-{type}.xml', [SitemapController::class, 'show'])->name('sitemap.show');

// Manga routes
Route::get('/truyen', [MangaController::class, 'index'])->name('manga.index');
Route::get('/truyen/{manga}', [MangaController::class, 'show'])->name('manga.show');

// Genre routes
Route::get('/the-loai/{genre:slug}', [GenreController::class, 'show'])->name('genre.show');

// Chapter reader
Route::get('/truyen/{manga}/chuong/{chapterNumber}', [ChapterController::class, 'show'])->name('chapter.show');

// Blog public routes
Route::prefix('blogs')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/category/{category}', [BlogController::class, 'category'])->name('category');
    Route::get('/tag/{tag}', [BlogController::class, 'tag'])->name('tag');
    Route::get('/author/{author}', [BlogController::class, 'author'])->name('author');
    Route::get('/{post}', [BlogController::class, 'show'])->name('show');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Comments
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Bookmarks
    Route::post('/truyen/{manga}/bookmark', [BookmarkController::class, 'toggle'])->name('bookmarks.toggle');
    Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');

    // Ratings
    Route::post('/truyen/{manga}/rating', [RatingController::class, 'store'])->name('ratings.store');

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

    // Settings
    Route::get('settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [AdminSettingController::class, 'update'])->name('settings.update');
    Route::delete('settings/image', [AdminSettingController::class, 'deleteImage'])->name('settings.delete-image');

    // Blog management
    Route::prefix('blog')->name('blog.')->group(function () {
        Route::resource('posts', AdminBlogPostController::class)->except(['show']);
        Route::resource('categories', AdminBlogCategoryController::class)->except(['show']);
    });

    // TinyMCE image upload
    Route::post('upload/tinymce', [AdminUploadController::class, 'tinymceUpload'])->name('upload.tinymce');
});

require __DIR__.'/auth.php';
