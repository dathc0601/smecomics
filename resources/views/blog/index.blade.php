@extends('layouts.public')

@section('title', 'Blog - ')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Blog Header -->
    <header class="text-center mb-12 relative">
        <div class="absolute inset-0 -z-10 overflow-hidden">
            <div class="absolute top-0 left-1/4 w-72 h-72 bg-orange-manga-300/30 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-peach-300/30 rounded-full blur-3xl"></div>
        </div>
        <span class="inline-block px-4 py-1 bg-orange-manga-100 text-orange-manga-700 rounded-full text-sm font-semibold mb-4 tracking-wider uppercase">
            Our Stories
        </span>
        <h1 class="text-5xl md:text-6xl font-extrabold text-gray-900 mb-4 tracking-tight">
            The <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-manga-500 to-peach-500">Blog</span>
        </h1>
        <p class="text-xl text-gray-600 max-w-2xl mx-auto font-light">
            News, reviews, guides, and insights from the world of manga and manhwa
        </p>
    </header>

    <!-- Featured Posts Section -->
    @if($featuredPosts->count() > 0)
    <section class="mb-16">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                <span class="w-8 h-1 bg-gradient-to-r from-orange-manga-500 to-peach-500 rounded-full"></span>
                Featured Stories
            </h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($featuredPosts as $index => $featured)
                @if($index === 0)
                <!-- Large Featured Card -->
                <a href="{{ route('blog.show', $featured) }}"
                   class="group relative block lg:row-span-2 rounded-3xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-1">
                    <div class="aspect-[4/3] lg:aspect-auto lg:absolute lg:inset-0">
                        @if($featured->featured_image)
                            <img src="{{ Storage::url($featured->featured_image) }}"
                                 alt="{{ $featured->title }}"
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-orange-manga-400 to-peach-400"></div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                    </div>
                    <div class="relative lg:absolute lg:bottom-0 lg:left-0 lg:right-0 p-8 text-white">
                        <div class="flex flex-wrap gap-2 mb-4">
                            @foreach($featured->categories->take(2) as $cat)
                                <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-semibold uppercase tracking-wider">
                                    {{ $cat->name }}
                                </span>
                            @endforeach
                        </div>
                        <h3 class="text-2xl lg:text-3xl font-bold mb-3 leading-tight group-hover:text-orange-manga-200 transition-colors">
                            {{ $featured->title }}
                        </h3>
                        <p class="text-white/80 mb-4 line-clamp-2 hidden lg:block">{{ $featured->excerpt }}</p>
                        <div class="flex items-center gap-4 text-sm text-white/70">
                            @if($featured->author)
                                <span>By {{ $featured->author->name }}</span>
                            @endif
                            <span>{{ $featured->published_at->format('M d, Y') }}</span>
                            <span>{{ $featured->reading_time }} min read</span>
                        </div>
                    </div>
                </a>
                @else
                <!-- Smaller Featured Cards -->
                <a href="{{ route('blog.show', $featured) }}"
                   class="group relative block rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 bg-white">
                    <div class="flex flex-col sm:flex-row">
                        <div class="sm:w-2/5 aspect-video sm:aspect-square">
                            @if($featured->featured_image)
                                <img src="{{ Storage::url($featured->featured_image) }}"
                                     alt="{{ $featured->title }}"
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-orange-manga-300 to-peach-300"></div>
                            @endif
                        </div>
                        <div class="sm:w-3/5 p-6 flex flex-col justify-center">
                            <div class="flex gap-2 mb-3">
                                @foreach($featured->categories->take(1) as $cat)
                                    <span class="px-2 py-1 bg-orange-manga-100 text-orange-manga-700 rounded-full text-xs font-semibold">
                                        {{ $cat->name }}
                                    </span>
                                @endforeach
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-orange-manga-600 transition-colors line-clamp-2">
                                {{ $featured->title }}
                            </h3>
                            <div class="flex items-center gap-3 text-sm text-gray-500">
                                <span>{{ $featured->published_at->format('M d, Y') }}</span>
                                <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                <span>{{ $featured->reading_time }} min</span>
                            </div>
                        </div>
                    </div>
                </a>
                @endif
            @endforeach
        </div>
    </section>
    @endif

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Posts Grid -->
        <div class="lg:col-span-2">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                    <span class="w-8 h-1 bg-gradient-to-r from-orange-manga-500 to-peach-500 rounded-full"></span>
                    Latest Articles
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
                                <div class="flex items-center gap-2">
                                    @if($post->author)
                                        <span class="font-medium text-gray-700">{{ $post->author->name }}</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-3">
                                    <span>{{ $post->published_at->format('M d') }}</span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $post->reading_time }}m
                                    </span>
                                </div>
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
                <h3 class="text-xl font-bold text-gray-600 mb-2">No posts yet</h3>
                <p class="text-gray-500">Check back soon for new articles!</p>
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

            <!-- Popular Tags Widget -->
            @if($popularTags->count() > 0)
            <div class="bg-white rounded-2xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-6 h-6 bg-gradient-to-br from-peach-500 to-orange-manga-500 rounded-lg flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                        </svg>
                    </span>
                    Popular Tags
                </h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($popularTags as $tag)
                    <a href="{{ route('blog.tag', $tag) }}"
                       class="inline-flex items-center px-3 py-1.5 bg-peach-50 hover:bg-peach-100 text-peach-700 rounded-full text-sm font-medium transition-colors">
                        #{{ $tag->name }}
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Newsletter Signup -->
            <div class="bg-gradient-to-br from-orange-manga-500 to-peach-500 rounded-2xl shadow-lg p-6 text-white">
                <h3 class="text-lg font-bold mb-2">Stay Updated</h3>
                <p class="text-white/80 text-sm mb-4">Get the latest manga news and reviews delivered to your inbox.</p>
                <div class="relative">
                    <a href="{{ route('home') }}"
                       class="block w-full px-4 py-3 bg-white text-orange-manga-600 font-semibold rounded-xl text-center hover:bg-orange-manga-50 transition-colors">
                        Explore Manga
                    </a>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection
