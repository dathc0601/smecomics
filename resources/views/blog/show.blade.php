@extends('layouts.public')

@section('title', ($post->meta_title ?? $post->title) . ' - ')

@push('styles')
<style>
    .prose-content h2 { @apply text-2xl font-bold text-gray-900 mt-8 mb-4; }
    .prose-content h3 { @apply text-xl font-bold text-gray-800 mt-6 mb-3; }
    .prose-content p { @apply text-gray-700 leading-relaxed mb-4; }
    .prose-content a { @apply text-orange-manga-600 hover:text-orange-manga-700 underline; }
    .prose-content ul { @apply list-disc list-inside mb-4 text-gray-700; }
    .prose-content ol { @apply list-decimal list-inside mb-4 text-gray-700; }
    .prose-content blockquote { @apply border-l-4 border-orange-manga-400 pl-4 italic text-gray-600 my-6; }
    .prose-content img { @apply rounded-xl shadow-lg my-6 mx-auto; }
    .prose-content pre { @apply bg-gray-900 text-gray-100 rounded-xl p-4 overflow-x-auto my-6; }
    .prose-content code { @apply bg-gray-100 px-1.5 py-0.5 rounded text-sm text-orange-manga-600; }
    .prose-content pre code { @apply bg-transparent text-gray-100 p-0; }
</style>
@endpush

@section('content')
<article class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Article Header -->
    <header class="max-w-4xl mx-auto text-center mb-10">
        <!-- Categories -->
        <div class="flex flex-wrap justify-center gap-2 mb-6">
            @foreach($post->categories as $category)
                <a href="{{ route('blog.category', $category) }}"
                   class="inline-flex items-center px-4 py-1.5 bg-gradient-to-r from-orange-manga-500 to-peach-500 text-white text-sm font-semibold rounded-full hover:shadow-lg transition-shadow">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>

        <!-- Title -->
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-gray-900 mb-6 leading-tight tracking-tight">
            {{ $post->title }}
        </h1>

        <!-- Excerpt -->
        @if($post->excerpt)
        <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto leading-relaxed">
            {{ $post->excerpt }}
        </p>
        @endif

        <!-- Meta Info -->
        <div class="flex flex-wrap items-center justify-center gap-6 text-sm text-gray-500">
            @if($post->author)
            <a href="{{ route('blog.author', $post->author) }}" class="flex items-center gap-2 hover:text-orange-manga-600 transition-colors">
                <div class="w-10 h-10 bg-gradient-to-br from-orange-manga-400 to-peach-400 rounded-full flex items-center justify-center">
                    <span class="text-white font-bold text-sm">{{ substr($post->author->name, 0, 1) }}</span>
                </div>
                <div class="text-left">
                    <span class="block font-semibold text-gray-800">{{ $post->author->name }}</span>
                    <span class="text-xs">Author</span>
                </div>
            </a>
            @endif

            <div class="flex items-center gap-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span>{{ $post->published_at->format('F d, Y') }}</span>
            </div>

            <div class="flex items-center gap-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ $post->reading_time }} min read</span>
            </div>

            <div class="flex items-center gap-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                <span>{{ number_format($post->view_count) }} views</span>
            </div>
        </div>
    </header>

    <!-- Featured Image -->
    @if($post->featured_image)
    <div class="max-w-5xl mx-auto mb-12">
        <div class="rounded-3xl overflow-hidden shadow-2xl">
            <img src="{{ Storage::url($post->featured_image) }}"
                 alt="{{ $post->title }}"
                 class="w-full h-auto">
        </div>
    </div>
    @endif

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-8 lg:col-start-1">
            <div class="bg-white rounded-3xl shadow-lg p-8 md:p-12">
                <!-- Article Content -->
                <div class="prose-content max-w-none">
                    {!! $post->content !!}
                </div>

                <!-- Tags -->
                @if($post->tags->count() > 0)
                <div class="mt-10 pt-8 border-t border-gray-100">
                    <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Tagged with</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach($post->tags as $tag)
                        <a href="{{ route('blog.tag', $tag) }}"
                           class="inline-flex items-center px-4 py-2 bg-peach-50 hover:bg-peach-100 text-peach-700 rounded-full text-sm font-medium transition-all hover:shadow-md">
                            <span class="mr-1">#</span>{{ $tag->name }}
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Share Section -->
                <div class="mt-10 pt-8 border-t border-gray-100">
                    <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Share this article</h4>
                    <div class="flex gap-3">
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}"
                           target="_blank"
                           class="flex items-center gap-2 px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            <span class="text-sm font-medium">Post</span>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                           target="_blank"
                           class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            <span class="text-sm font-medium">Share</span>
                        </a>
                        <button onclick="navigator.clipboard.writeText(window.location.href); this.textContent='Copied!';"
                                class="flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-sm font-medium">Copy Link</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Author Bio -->
            @if($post->author && $post->author->bio)
            <div class="mt-8 bg-gradient-to-br from-orange-manga-50 to-peach-50 rounded-3xl p-8">
                <div class="flex flex-col sm:flex-row items-start gap-6">
                    <div class="w-20 h-20 bg-gradient-to-br from-orange-manga-400 to-peach-400 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg">
                        <span class="text-3xl font-bold text-white">{{ substr($post->author->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <span class="text-sm font-semibold text-orange-manga-600 uppercase tracking-wider">Written by</span>
                        <h3 class="text-xl font-bold text-gray-900 mt-1">{{ $post->author->name }}</h3>
                        <p class="text-gray-600 mt-2 leading-relaxed">{{ $post->author->bio }}</p>
                        <a href="{{ route('blog.author', $post->author) }}"
                           class="inline-flex items-center mt-4 text-orange-manga-600 hover:text-orange-manga-700 font-semibold">
                            View all posts
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <aside class="lg:col-span-4 space-y-8">
            <!-- Recent Posts -->
            @if($recentPosts->count() > 0)
            <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-24">
                <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <span class="w-6 h-6 bg-gradient-to-br from-orange-manga-500 to-peach-500 rounded-lg flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </span>
                    Recent Posts
                </h3>
                <div class="space-y-4">
                    @foreach($recentPosts as $recent)
                    <a href="{{ route('blog.show', $recent) }}" class="group block">
                        <div class="flex gap-4">
                            <div class="w-16 h-16 flex-shrink-0 rounded-lg overflow-hidden">
                                @if($recent->featured_image)
                                    <img src="{{ Storage::url($recent->featured_image) }}"
                                         alt="{{ $recent->title }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-orange-manga-200 to-peach-200"></div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-semibold text-gray-800 line-clamp-2 group-hover:text-orange-manga-600 transition-colors">
                                    {{ $recent->title }}
                                </h4>
                                <p class="text-xs text-gray-500 mt-1">{{ $recent->published_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </aside>
    </div>

    <!-- Related Posts -->
    @if($relatedPosts->count() > 0)
    <section class="mt-16">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                <span class="w-8 h-1 bg-gradient-to-r from-orange-manga-500 to-peach-500 rounded-full"></span>
                Related Articles
            </h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedPosts as $related)
            <article class="group bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <a href="{{ route('blog.show', $related) }}" class="block">
                    <div class="aspect-video overflow-hidden">
                        @if($related->featured_image)
                            <img src="{{ Storage::url($related->featured_image) }}"
                                 alt="{{ $related->title }}"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-orange-manga-200 to-peach-200"></div>
                        @endif
                    </div>
                    <div class="p-5">
                        <h3 class="font-bold text-gray-900 line-clamp-2 group-hover:text-orange-manga-600 transition-colors">
                            {{ $related->title }}
                        </h3>
                        <div class="flex items-center gap-2 mt-3 text-sm text-gray-500">
                            <span>{{ $related->published_at->format('M d') }}</span>
                            <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                            <span>{{ $related->reading_time }}m read</span>
                        </div>
                    </div>
                </a>
            </article>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Back to Blog -->
    <div class="mt-12 text-center">
        <a href="{{ route('blog.index') }}"
           class="inline-flex items-center gap-2 px-6 py-3 bg-white text-orange-manga-600 font-semibold rounded-full border-2 border-orange-manga-500 hover:bg-orange-manga-50 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Blog
        </a>
    </div>
</article>
@endsection
