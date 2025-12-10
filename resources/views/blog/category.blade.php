@extends('layouts.public')

@section('title', $category->name . ' - Blog - ')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Category Header -->
    <header class="text-center mb-12 relative">
        <div class="absolute inset-0 -z-10 overflow-hidden">
            <div class="absolute top-0 left-1/4 w-72 h-72 bg-orange-manga-300/30 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-peach-300/30 rounded-full blur-3xl"></div>
        </div>
        <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-orange-manga-600 hover:text-orange-manga-700 font-medium mb-4 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Blog
        </a>
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-orange-manga-500 to-peach-500 rounded-2xl shadow-lg mb-6">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
        </div>
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4 tracking-tight">
            {{ $category->name }}
        </h1>
        @if($category->description)
        <p class="text-xl text-gray-600 max-w-2xl mx-auto font-light">
            {{ $category->description }}
        </p>
        @endif
        <p class="text-gray-500 mt-4">
            {{ $posts->total() }} {{ Str::plural('article', $posts->total()) }} in this category
        </p>
    </header>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Posts Grid -->
        <div class="lg:col-span-2">
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
                                @foreach($post->categories->take(2) as $cat)
                                    <span class="px-2 py-0.5 bg-orange-manga-50 text-orange-manga-600 text-xs font-semibold rounded-full {{ $cat->id === $category->id ? 'ring-2 ring-orange-manga-400' : '' }}">
                                        {{ $cat->name }}
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
                <h3 class="text-xl font-bold text-gray-600 mb-2">No posts in this category</h3>
                <p class="text-gray-500 mb-4">Check back soon for new articles!</p>
                <a href="{{ route('blog.index') }}" class="btn-primary">Browse All Posts</a>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <aside class="lg:col-span-1 space-y-8">
            <!-- All Categories Widget -->
            <div class="bg-white rounded-2xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-6 h-6 bg-gradient-to-br from-orange-manga-500 to-peach-500 rounded-lg flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </span>
                    All Categories
                </h3>
                <ul class="space-y-2">
                    @foreach($categories as $cat)
                    <li>
                        <a href="{{ route('blog.category', $cat) }}"
                           class="flex items-center justify-between py-2 px-3 rounded-lg transition-colors group {{ $cat->id === $category->id ? 'bg-orange-manga-100 text-orange-manga-700' : 'hover:bg-orange-manga-50' }}">
                            <span class="{{ $cat->id === $category->id ? 'font-semibold' : 'text-gray-700 group-hover:text-orange-manga-600 font-medium' }}">{{ $cat->name }}</span>
                            <span class="text-xs {{ $cat->id === $category->id ? 'bg-orange-manga-200 text-orange-manga-700' : 'bg-gray-100 group-hover:bg-orange-manga-100 text-gray-600 group-hover:text-orange-manga-600' }} px-2 py-1 rounded-full transition-colors">
                                {{ $cat->posts_count }}
                            </span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            <!-- Back to Blog CTA -->
            <div class="bg-gradient-to-br from-orange-manga-500 to-peach-500 rounded-2xl shadow-lg p-6 text-white text-center">
                <h3 class="text-lg font-bold mb-2">Explore More</h3>
                <p class="text-white/80 text-sm mb-4">Discover all our latest articles and stories.</p>
                <a href="{{ route('blog.index') }}"
                   class="block w-full px-4 py-3 bg-white text-orange-manga-600 font-semibold rounded-xl text-center hover:bg-orange-manga-50 transition-colors">
                    View All Posts
                </a>
            </div>
        </aside>
    </div>
</div>
@endsection
