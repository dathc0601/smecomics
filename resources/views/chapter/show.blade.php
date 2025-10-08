@extends('layouts.public')

@section('title', $manga->title . ' - ' . $chapter->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Chapter Header -->
    <div class="bg-white rounded-2xl shadow-md p-6 mb-8">
        <div class="text-center mb-4">
            <h1 class="text-2xl font-bold mb-2">
                <a href="{{ route('manga.show', $manga->slug) }}" class="text-orange-manga-600 hover:underline">
                    {{ $manga->title }}
                </a>
            </h1>
            <h2 class="text-xl text-gray-700">{{ $chapter->title }}</h2>
        </div>

        <!-- Navigation -->
        <div class="flex justify-center items-center gap-4">
            @if($prevChapter)
            <a href="{{ route('chapter.show', [$manga->slug, $prevChapter->chapter_number]) }}" class="btn-secondary">
                ← Previous
            </a>
            @endif

            <a href="{{ route('manga.show', $manga->slug) }}" class="btn-secondary">
                📖 All Chapters
            </a>

            @if($nextChapter)
            <a href="{{ route('chapter.show', [$manga->slug, $nextChapter->chapter_number]) }}" class="btn-primary">
                Next →
            </a>
            @endif
        </div>
    </div>

    <!-- Chapter Images -->
    <div class="space-y-2 mb-8">
        @foreach($chapter->images as $image)
        <div class="bg-white rounded-lg overflow-hidden shadow-md">
            <img src="{{ asset('storage/' . $image->image_path) }}"
                 onerror="this.src='https://placehold.co/800x1200?text=Page+{{ $image->order }}'"
                 alt="Page {{ $image->order }}"
                 class="w-full h-auto">
        </div>
        @endforeach
    </div>

    <!-- Bottom Navigation -->
    <div class="bg-white rounded-2xl shadow-md p-6 mb-8">
        <div class="flex justify-center items-center gap-4">
            @if($prevChapter)
            <a href="{{ route('chapter.show', [$manga->slug, $prevChapter->chapter_number]) }}" class="btn-secondary">
                ← Previous Chapter
            </a>
            @endif

            @if($nextChapter)
            <a href="{{ route('chapter.show', [$manga->slug, $nextChapter->chapter_number]) }}" class="btn-primary">
                Next Chapter →
            </a>
            @else
            <a href="{{ route('manga.show', $manga->slug) }}" class="btn-primary">
                Back to Manga
            </a>
            @endif
        </div>
    </div>

    <!-- Comments -->
    <section class="bg-white rounded-2xl shadow-md p-6">
        <h2 class="text-xl font-bold mb-4 text-orange-manga-600">💬 COMMENTS</h2>

        @auth
        <form action="{{ route('comments.store') }}" method="POST" class="mb-6">
            @csrf
            <input type="hidden" name="commentable_type" value="App\Models\Chapter">
            <input type="hidden" name="commentable_id" value="{{ $chapter->id }}">
            <textarea name="content" rows="3" class="w-full rounded-lg border-gray-300 focus:border-orange-manga-500 focus:ring-orange-manga-500" placeholder="Write a comment about this chapter..."></textarea>
            <button type="submit" class="btn-primary mt-2">Post Comment</button>
        </form>
        @else
        <p class="mb-6 text-gray-600">Please <a href="{{ route('login') }}" class="text-orange-manga-600 hover:underline">login</a> to comment.</p>
        @endauth

        <div class="space-y-4">
            @foreach($chapter->comments()->whereNull('parent_id')->latest()->get() as $comment)
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
@endsection
