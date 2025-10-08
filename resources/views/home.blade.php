@extends('layouts.public')

@section('title', 'Trang chủ')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Hero Carousel -->
    @if($featured->count() > 0)
    <section class="mb-12">
        <div class="relative bg-gradient-to-r from-orange-manga-200 to-peach-200 rounded-3xl p-8 overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($featured->take(3) as $manga)
                <a href="{{ route('manga.show', $manga->slug) }}" class="manga-card group">
                    <img src="{{ $manga->cover_image ? \Illuminate\Support\Facades\Storage::url($manga->cover_image) : 'https://placehold.co/300x400?text=' . urlencode($manga->title) }}"
                         alt="{{ $manga->title }}"
                         class="manga-card-cover">
                    <div class="p-4">
                        <h3 class="font-bold text-lg mb-2 group-hover:text-orange-manga-600 transition">{{ $manga->title }}</h3>
                        @if($manga->latestChapter)
                        <p class="text-sm text-gray-600">Chapter {{ $manga->latestChapter->chapter_number }}</p>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-3">
            <!-- Newly Updated -->
            <section class="mb-12">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                        <span class="text-orange-manga-500 mr-2">★</span>
                        Newly Updated
                    </h2>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($newlyUpdated as $manga)
                    <a href="{{ route('manga.show', $manga->slug) }}" class="manga-card group">
                        <div class="relative">
                            <img src="{{ $manga->cover_image ? \Illuminate\Support\Facades\Storage::url($manga->cover_image) : 'https://placehold.co/300x400?text=' . urlencode($manga->title) }}"
                                 alt="{{ $manga->title }}"
                                 class="manga-card-cover">
                            @if($manga->is_hot)
                            <span class="absolute top-2 left-2 badge-hot">HOT</span>
                            @endif
                            @if($manga->is_18plus)
                            <span class="absolute top-2 right-2 badge-18plus">18+</span>
                            @endif
                        </div>
                        <div class="p-3">
                            <h3 class="font-semibold text-sm mb-1 line-clamp-2 group-hover:text-orange-manga-600 transition">
                                {{ $manga->title }}
                            </h3>
                            <p class="text-xs text-gray-500 capitalize">{{ $manga->type }}</p>
                            @if($manga->latestChapter)
                            <p class="text-xs text-orange-manga-600 font-medium mt-1">
                                Ch. {{ $manga->latestChapter->chapter_number }}
                            </p>
                            @endif
                        </div>
                    </a>
                    @endforeach
                </div>

                <div class="text-center mt-8">
                    <a href="{{ route('manga.index') }}" class="btn-primary">
                        View More
                    </a>
                </div>
            </section>

            <!-- Completed 18+ Section -->
            @if($completed18Plus->count() > 0)
            <section>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                        <span class="text-red-500 mr-2">🔞</span>
                        Completed Mature
                    </h2>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($completed18Plus as $manga)
                    <a href="{{ route('manga.show', $manga->slug) }}" class="manga-card group">
                        <div class="relative">
                            <img src="{{ $manga->cover_image ? \Illuminate\Support\Facades\Storage::url($manga->cover_image) : 'https://placehold.co/300x400?text=' . urlencode($manga->title) }}"
                                 alt="{{ $manga->title }}"
                                 class="manga-card-cover">
                            <span class="absolute top-2 left-2 badge-18plus">18+</span>
                            <span class="absolute top-2 right-2 badge-end">END</span>
                        </div>
                        <div class="p-3">
                            <h3 class="font-semibold text-sm mb-1 line-clamp-2 group-hover:text-orange-manga-600 transition">
                                {{ $manga->title }}
                            </h3>
                            <p class="text-xs text-gray-500">{{ $manga->chapters->count() }} Chapters</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </section>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Reading History -->
            @if($readingHistory->count() > 0)
            <div class="bg-white rounded-2xl shadow-md p-6 mb-6">
                <h3 class="text-lg font-bold mb-4 text-orange-manga-600">📖 Continue Reading</h3>
                <div class="space-y-3">
                    @foreach($readingHistory as $history)
                    <a href="{{ route('chapter.show', ['manga' => $history->manga->slug, 'chapterNumber' => $history->chapter->chapter_number]) }}"
                       class="flex items-center space-x-3 hover:bg-orange-manga-50 p-2 rounded-lg transition">
                        <img src="{{ $history->manga->cover_image ? \Illuminate\Support\Facades\Storage::url($history->manga->cover_image) : 'https://placehold.co/60x80?text=' . urlencode(substr($history->manga->title, 0, 1)) }}"
                             alt="{{ $history->manga->title }}"
                             class="w-12 h-16 object-cover rounded">
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-sm line-clamp-2">{{ $history->manga->title }}</p>
                            <p class="text-xs text-orange-manga-600">Ch. {{ $history->chapter->chapter_number }}</p>
                            <p class="text-xs text-gray-400">{{ $history->last_read_at->diffForHumans() }}</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Hot Titles -->
            <div class="bg-white rounded-2xl shadow-md p-6 mb-6">
                <h3 class="text-lg font-bold mb-4 text-orange-manga-600">🔥 HOT TITLES</h3>
                <div class="space-y-3">
                    @foreach($hotTitles as $manga)
                    <a href="{{ route('manga.show', $manga->slug) }}" class="flex items-center space-x-3 hover:bg-orange-manga-50 p-2 rounded-lg transition">
                        <img src="{{ $manga->cover_image ? \Illuminate\Support\Facades\Storage::url($manga->cover_image) : 'https://placehold.co/60x80?text=' . urlencode(substr($manga->title, 0, 1)) }}"
                             alt="{{ $manga->title }}"
                             class="w-12 h-16 object-cover rounded">
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-sm line-clamp-2">{{ $manga->title }}</p>
                            @if($manga->latestChapter)
                            <p class="text-xs text-gray-500">Ch. {{ $manga->latestChapter->chapter_number }}</p>
                            @endif
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
