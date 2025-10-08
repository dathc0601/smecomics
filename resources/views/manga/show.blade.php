@extends('layouts.public')

@section('title', $manga->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-3">
            <!-- Manga Header -->
            <div class="bg-gradient-to-r from-orange-manga-100 to-peach-100 rounded-2xl p-6 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Cover Image -->
                    <div>
                        <img src="{{ $manga->cover_image ? \Illuminate\Support\Facades\Storage::url($manga->cover_image) : 'https://placehold.co/300x400?text=' . urlencode($manga->title) }}"
                             alt="{{ $manga->title }}"
                             class="w-full rounded-xl shadow-manga">
                    </div>

                    <!-- Manga Info -->
                    <div class="md:col-span-2">
                        <h1 class="text-3xl font-bold mb-4">{{ $manga->title }}</h1>

                        <div class="grid grid-cols-2 gap-3 mb-4 text-sm">
                            <div><span class="font-semibold">Author:</span> {{ $manga->author ?? 'Unknown' }}</div>
                            <div><span class="font-semibold">Type:</span> <span class="capitalize">{{ $manga->type }}</span></div>
                            <div><span class="font-semibold">Status:</span> <span class="capitalize badge badge-{{ $manga->status == 'completed' ? 'end' : 'new' }}">{{ $manga->status }}</span></div>
                            <div><span class="font-semibold">Released:</span> {{ $manga->release_year }}</div>
                            <div><span class="font-semibold">Views:</span> {{ number_format($manga->view_count) }}</div>
                            <div><span class="font-semibold">Rating:</span> ⭐ {{ $manga->rating }} ({{ $manga->rating_count }} votes)</div>
                        </div>

                        <!-- Genres -->
                        <div class="mb-4">
                            <span class="font-semibold text-sm">Genres:</span>
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach($manga->genres as $genre)
                                <a href="{{ route('manga.index', ['genre' => $genre->slug]) }}" class="tag-pill">
                                    {{ $genre->name }}
                                </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-wrap gap-3 mt-6">
                            @if($manga->chapters->count() > 0)
                            <a href="{{ route('chapter.show', [$manga->slug, $manga->chapters->first()->chapter_number]) }}" class="btn-primary">
                                First Chapter
                            </a>
                            <a href="{{ route('chapter.show', [$manga->slug, $manga->chapters->last()->chapter_number]) }}" class="btn-secondary">
                                Latest Chapter
                            </a>
                            @endif

                            @auth
                            <form action="{{ route('bookmarks.toggle', $manga) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-secondary">
                                    {{ $isBookmarked ? '❤️ Bookmarked' : '🤍 Bookmark' }}
                                </button>
                            </form>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <section class="bg-white rounded-2xl shadow-md p-6 mb-8">
                <h2 class="text-xl font-bold mb-4 flex items-center text-orange-manga-600">
                    <span class="mr-2">★</span> DESCRIPTION
                </h2>
                <p class="text-gray-700 leading-relaxed">{{ $manga->description }}</p>
            </section>

            <!-- Table of Contents -->
            <section class="bg-white rounded-2xl shadow-md p-6 mb-8">
                <h2 class="text-xl font-bold mb-4 flex items-center text-orange-manga-600">
                    <span class="mr-2">📖</span> TABLE OF CONTENTS
                </h2>
                <div class="gap-3">
                    @foreach($manga->chapters->reverse() as $chapter)
                    <a href="{{ route('chapter.show', [$manga->slug, $chapter->chapter_number]) }}"
                       class="flex justify-between items-center p-3 rounded-lg hover:bg-orange-manga-50 transition">
                        <div>
                            <span class="font-semibold">{{ $chapter->title }}</span>
                        </div>
                        <span class="text-xs text-gray-500">{{ $chapter->published_at->diffForHumans() }}</span>
                    </a>
                    @endforeach
                </div>
            </section>

            <!-- Comments Section -->
            <section class="bg-white rounded-2xl shadow-md p-6">
                <h2 class="text-xl font-bold mb-4 text-orange-manga-600">💬 COMMENTS</h2>

                @auth
                <form action="{{ route('comments.store') }}" method="POST" class="mb-6">
                    @csrf
                    <input type="hidden" name="commentable_type" value="App\Models\Manga">
                    <input type="hidden" name="commentable_id" value="{{ $manga->id }}">
                    <textarea name="content" rows="3" class="w-full rounded-lg border-gray-300 focus:border-orange-manga-500 focus:ring-orange-manga-500" placeholder="Write a comment..."></textarea>
                    <button type="submit" class="btn-primary mt-2">Post Comment</button>
                </form>
                @else
                <p class="mb-6 text-gray-600">Please <a href="{{ route('login') }}" class="text-orange-manga-600 hover:underline">login</a> to comment.</p>
                @endauth

                <div class="space-y-4">
                    @foreach($manga->comments()->whereNull('parent_id')->latest()->get() as $comment)
                    <div class="comment-box">
                        <div class="flex justify-between items-start mb-2">
                            <span class="font-semibold">{{ $comment->user->name }}</span>
                            <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-gray-700">{{ $comment->content }}</p>
                    </div>
                    @endforeach
                </div>
            </section>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Hot Titles -->
            <div class="bg-white rounded-2xl shadow-md p-6 mb-6">
                <h3 class="text-lg font-bold mb-4 text-orange-manga-600">🔥 HOT TITLES</h3>
                <div class="space-y-3">
                    @foreach($hotTitles as $hotManga)
                    <a href="{{ route('manga.show', $hotManga->slug) }}" class="flex items-center space-x-3 hover:bg-orange-manga-50 p-2 rounded-lg transition">
                        <img src="{{ $hotManga->cover_image ?? 'https://placehold.co/60x80' }}"
                             alt="{{ $hotManga->title }}"
                             class="w-12 h-16 object-cover rounded">
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-sm line-clamp-2">{{ $hotManga->title }}</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>

            <!-- Genres Cloud -->
            <div class="bg-white rounded-2xl shadow-md p-6">
                <h3 class="text-lg font-bold mb-4 text-orange-manga-600">🏷️ TAGS</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach(\App\Models\Genre::all() as $genre)
                    <a href="{{ route('manga.index', ['genre' => $genre->slug]) }}" class="tag-pill text-xs">
                        {{ $genre->name }}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
