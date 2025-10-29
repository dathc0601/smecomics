@extends('layouts.public')

@section('title', $genre->name . ' - Genre')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $genre->name }}</h1>
        <p class="text-gray-600">Discover manga in the {{ $genre->name }} genre</p>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-md p-6 mb-8">
        <form action="{{ route('genre.show', $genre) }}" method="GET" class="space-y-4">
            <!-- Search Bar -->
            <div>
                <input type="text"
                       name="search"
                       class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-manga-500"
                       placeholder="Search manga by title..."
                       value="{{ request('search') }}">
            </div>

            <!-- Filter Options -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Type Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                    <select name="type" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-manga-500">
                        <option value="">All Types</option>
                        <option value="manhwa" {{ request('type') == 'manhwa' ? 'selected' : '' }}>Manhwa</option>
                        <option value="manga" {{ request('type') == 'manga' ? 'selected' : '' }}>Manga</option>
                        <option value="novel" {{ request('type') == 'novel' ? 'selected' : '' }}>Novel</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-manga-500">
                        <option value="">All Status</option>
                        <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="hiatus" {{ request('status') == 'hiatus' ? 'selected' : '' }}>Hiatus</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="flex items-end">
                    <button type="submit" class="w-full btn-primary">
                        Apply Filters
                    </button>
                </div>
            </div>

            <!-- Clear Filters -->
            @if(request()->hasAny(['search', 'type', 'status']))
            <div class="text-center">
                <a href="{{ route('genre.show', $genre) }}" class="text-orange-manga-600 hover:text-orange-manga-700 text-sm font-medium">
                    Clear All Filters
                </a>
            </div>
            @endif
        </form>
    </div>

    <!-- Results Count -->
    <div class="mb-6">
        <p class="text-gray-600">
            <span class="font-semibold">{{ $mangas->total() }}</span> manga found
            @if(request('search'))
                for "<span class="font-semibold">{{ request('search') }}</span>"
            @endif
        </p>
    </div>

    <!-- Manga Grid -->
    @if($mangas->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4 mb-8">
            @foreach($mangas as $manga)
            <a href="{{ route('manga.show', $manga->slug) }}" class="manga-card group">
                <div class="relative">
                    <img src="{{ asset('storage/' . $manga->cover_image) ?? 'https://placehold.co/300x400?text=' . urlencode($manga->title) }}"
                         alt="{{ $manga->title }}"
                         class="manga-card-cover">
                    @if($manga->is_hot)
                    <span class="absolute top-2 left-2 badge-hot">HOT</span>
                    @endif
                    @if($manga->is_18plus)
                    <span class="absolute top-2 right-2 badge-18plus">18+</span>
                    @endif
                    @if($manga->status == 'completed')
                    <span class="absolute bottom-2 right-2 badge-end">END</span>
                    @endif
                </div>
                <div class="p-3">
                    <h3 class="font-semibold text-sm mb-1 line-clamp-2 group-hover:text-orange-manga-600 transition">
                        {{ $manga->title }}
                    </h3>
                    <p class="text-xs text-gray-500 capitalize mb-1">{{ $manga->type }}</p>
                    @if($manga->latestChapter)
                    <p class="text-xs text-orange-manga-600 font-medium">
                        Ch. {{ $manga->latestChapter->chapter_number }}
                    </p>
                    @endif
                    @if($manga->genres->count() > 0)
                    <div class="flex flex-wrap gap-1 mt-2">
                        @foreach($manga->genres->take(2) as $genreTag)
                        <span class="text-xs px-2 py-1 bg-orange-manga-100 text-orange-manga-700 rounded">
                            {{ $genreTag->name }}
                        </span>
                        @endforeach
                    </div>
                    @endif
                </div>
            </a>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($mangas->hasPages())
        <div class="mt-8">
            {{ $mangas->links() }}
        </div>
        @endif
    @else
        <!-- No Results -->
        <div class="text-center py-16">
            <div class="text-6xl mb-4">📚</div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">No manga found</h3>
            <p class="text-gray-600 mb-6">Try adjusting your filters or search terms</p>
            <a href="{{ route('genre.show', $genre) }}" class="btn-primary">
                Clear Filters
            </a>
        </div>
    @endif
</div>
@endsection
