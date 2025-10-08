@extends('layouts.public')

@section('title', 'My Bookmarks')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">My Bookmarks</h1>
        <p class="text-gray-600">Your saved manga collection</p>
    </div>

    @if($bookmarks->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4 mb-8">
            @foreach($bookmarks as $bookmark)
            <div class="manga-card group relative">
                <a href="{{ route('manga.show', $bookmark->manga->slug) }}">
                    <div class="relative">
                        <img src="{{ asset('storage/' . $bookmark->manga->cover_image) ?? 'https://placehold.co/300x400?text=' . urlencode($bookmark->manga->title) }}"
                             alt="{{ $bookmark->manga->title }}"
                             class="manga-card-cover">
                        @if($bookmark->manga->is_hot)
                        <span class="absolute top-2 left-2 badge-hot">HOT</span>
                        @endif
                        @if($bookmark->manga->is_18plus)
                        <span class="absolute top-2 right-2 badge-18plus">18+</span>
                        @endif
                        @if($bookmark->manga->status == 'completed')
                        <span class="absolute bottom-2 right-2 badge-end">END</span>
                        @endif
                    </div>
                    <div class="p-3">
                        <h3 class="font-semibold text-sm mb-1 line-clamp-2 group-hover:text-orange-manga-600 transition">
                            {{ $bookmark->manga->title }}
                        </h3>
                        <p class="text-xs text-gray-500 capitalize mb-1">{{ $bookmark->manga->type }}</p>
                        @if($bookmark->manga->latestChapter)
                        <p class="text-xs text-orange-manga-600 font-medium">
                            Ch. {{ $bookmark->manga->latestChapter->chapter_number }}
                        </p>
                        @endif
                        <p class="text-xs text-gray-400 mt-2">
                            Bookmarked {{ $bookmark->created_at->diffForHumans() }}
                        </p>
                    </div>
                </a>

                <!-- Remove Bookmark Button -->
                <form action="{{ route('bookmarks.toggle', $bookmark->manga) }}" method="POST" class="absolute top-2 right-2">
                    @csrf
                    <button type="submit"
                            class="bg-red-500 hover:bg-red-600 text-white rounded-full p-2 shadow-lg transition"
                            title="Remove bookmark">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </form>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($bookmarks->hasPages())
        <div class="mt-8">
            {{ $bookmarks->links() }}
        </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <div class="text-6xl mb-4">📚</div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">No bookmarks yet</h3>
            <p class="text-gray-600 mb-6">Start bookmarking your favorite manga to see them here</p>
            <a href="{{ route('manga.index') }}" class="btn-primary">
                Browse Manga
            </a>
        </div>
    @endif
</div>
@endsection
