@extends('layouts.public')

@section('title', $author->name . ' - Blog - ')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Author Header -->
    <header class="mb-12 relative">
        <div class="absolute inset-0 -z-10 overflow-hidden">
            <div class="absolute top-0 left-1/4 w-72 h-72 bg-orange-manga-300/30 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-peach-300/30 rounded-full blur-3xl"></div>
        </div>

        <div class="text-center mb-6">
            <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-orange-manga-600 hover:text-orange-manga-700 font-medium mb-4 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Blog
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-xl p-8 md:p-12">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
                <!-- Author Avatar -->
                <div class="w-32 h-32 bg-gradient-to-br from-orange-manga-400 to-peach-400 rounded-3xl flex items-center justify-center shadow-lg flex-shrink-0">
                    <span class="text-5xl font-bold text-white">{{ substr($author->name, 0, 1) }}</span>
                </div>

                <!-- Author Info -->
                <div class="flex-1 text-center md:text-left">
                    <span class="inline-block px-3 py-1 bg-orange-manga-100 text-orange-manga-700 rounded-full text-sm font-semibold mb-3">
                        Author
                    </span>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-3">
                        {{ $author->name }}
                    </h1>
                    @if($author->bio)
                    <p class="text-gray-600 text-lg leading-relaxed mb-4 max-w-2xl">
                        {{ $author->bio }}
                    </p>
                    @endif
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 text-sm text-gray-500">
                        <span class="flex items-center gap-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                            </svg>
                            {{ $posts->total() }} {{ Str::plural('article', $posts->total()) }}
                        </span>
                        @if($author->website)
                        <a href="{{ $author->website }}" target="_blank" rel="noopener"
                           class="flex items-center gap-1 text-orange-manga-600 hover:text-orange-manga-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                            </svg>
                            Website
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Posts Grid -->
        <div class="lg:col-span-2">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                    <span class="w-8 h-1 bg-gradient-to-r from-orange-manga-500 to-peach-500 rounded-full"></span>
                    Articles by {{ $author->name }}
                </h2>
            </div>

            @if($posts->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                @foreach($posts as $post)
                <article class="group bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <a href="{{ route('blog.show', $post) }}" class="block">
                        <div class="aspect-video overflow-hidden">
                            @if($post->featured_image)
                                <img src="{{ Storage::url($post->featured_image) }}"
                                     alt="{{ $post->title }}"
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-orange-manga-200 to-peach-200 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-5">
                            <div class="flex flex-wrap gap-2 mb-3">
                                @foreach($post->categories->take(2) as $category)
                                    <span class="px-2 py-0.5 bg-orange-manga-50 text-orange-manga-600 text-xs font-semibold rounded-full">
                                        {{ $category->name }}
                                    </span>
                                @endforeach
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-orange-manga-600 transition-colors">
                                {{ $post->title }}
                            </h3>
                            @if($post->excerpt)
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $post->excerpt }}</p>
                            @endif
                            <div class="flex items-center justify-between text-sm text-gray-500">
                                <span>{{ $post->published_at->format('M d, Y') }}</span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $post->reading_time }}m read
                                </span>
                            </div>
                        </div>
                    </a>
                </article>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($posts->hasPages())
            <div class="mt-10">
                {{ $posts->links() }}
            </div>
            @endif
            @else
            <div class="text-center py-16 bg-white rounded-2xl shadow-md">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
                <h3 class="text-xl font-bold text-gray-600 mb-2">No articles yet</h3>
                <p class="text-gray-500 mb-4">This author hasn't published any blog posts yet.</p>
                <a href="{{ route('blog.index') }}" class="btn-primary">Browse All Posts</a>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <aside class="lg:col-span-1 space-y-8">
            <!-- Categories Widget -->
            <div class="bg-white rounded-2xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-6 h-6 bg-gradient-to-br from-orange-manga-500 to-peach-500 rounded-lg flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </span>
                    Categories
                </h3>
                <ul class="space-y-2">
                    @foreach($categories as $category)
                    <li>
                        <a href="{{ route('blog.category', $category) }}"
                           class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-orange-manga-50 transition-colors group">
                            <span class="text-gray-700 group-hover:text-orange-manga-600 font-medium">{{ $category->name }}</span>
                            <span class="text-xs bg-gray-100 group-hover:bg-orange-manga-100 text-gray-600 group-hover:text-orange-manga-600 px-2 py-1 rounded-full transition-colors">
                                {{ $category->posts_count }}
                            </span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            <!-- Explore CTA -->
            <div class="bg-gradient-to-br from-orange-manga-500 to-peach-500 rounded-2xl shadow-lg p-6 text-white text-center">
                <h3 class="text-lg font-bold mb-2">Explore More</h3>
                <p class="text-white/80 text-sm mb-4">Discover articles from other authors.</p>
                <a href="{{ route('blog.index') }}"
                   class="block w-full px-4 py-3 bg-white text-orange-manga-600 font-semibold rounded-xl text-center hover:bg-orange-manga-50 transition-colors">
                    View All Posts
                </a>
            </div>
        </aside>
    </div>
</div>
@endsection
