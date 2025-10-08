<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SME Comics') }} - @yield('title', 'Read Manga Online')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-manga-gradient min-h-screen">
    <!-- Navigation -->
    <nav class="bg-manga-nav shadow-manga sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-white">
                        SME Comics
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex space-x-6">
                    <a href="{{ route('manga.index', ['status' => 'ongoing']) }}" class="text-white hover:text-orange-manga-100 font-medium transition">
                        Ongoing
                    </a>
                    <a href="{{ route('manga.index', ['status' => 'completed']) }}" class="text-white hover:text-orange-manga-100 font-medium transition">
                        Completed
                    </a>
                    <a href="{{ route('manga.index', ['type' => 'manhwa']) }}" class="text-white hover:text-orange-manga-100 font-medium transition">
                        Manhwa
                    </a>
                    <a href="{{ route('manga.index') }}" class="text-white hover:text-orange-manga-100 font-medium transition">
                        All Manga
                    </a>
                </div>

                <!-- Auth Links -->
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('bookmarks.index') }}" class="text-white hover:text-orange-manga-100 font-medium transition">
                            Bookmarks
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-white hover:text-orange-manga-100 font-medium transition">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-white hover:text-orange-manga-100 font-medium transition">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="bg-white text-orange-manga-600 px-4 py-2 rounded-full font-semibold hover:bg-orange-manga-50 transition">
                            Register
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-peach-100 mt-16 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-peach-700">
                &copy; {{ date('Y') }} SME Comics. All rights reserved.
            </p>
            <p class="text-sm text-peach-600 mt-2">
                Disclaimer: All manga content is the property of their respective owners.
            </p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
