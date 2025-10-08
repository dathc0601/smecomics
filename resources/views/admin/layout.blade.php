<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - SME Comics</title>
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="px-6 py-4">
                <h1 class="text-2xl font-bold">SME Comics Admin</h1>
            </div>
            <nav class="mt-6">
                <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    📊 Dashboard
                </a>
                <a href="{{ route('admin.manga.index') }}" class="admin-nav-link {{ request()->routeIs('admin.manga.*') ? 'active' : '' }}">
                    📚 Manga
                </a>
                <a href="{{ route('admin.chapters.index') }}" class="admin-nav-link {{ request()->routeIs('admin.chapters.*') ? 'active' : '' }}">
                    📖 Chapters
                </a>
                <a href="{{ route('admin.genres.index') }}" class="admin-nav-link {{ request()->routeIs('admin.genres.*') ? 'active' : '' }}">
                    🏷️ Genres
                </a>
                <a href="{{ route('admin.authors.index') }}" class="admin-nav-link {{ request()->routeIs('admin.authors.*') ? 'active' : '' }}">
                    ✍️ Authors
                </a>
                <a href="{{ route('admin.tags.index') }}" class="admin-nav-link {{ request()->routeIs('admin.tags.*') ? 'active' : '' }}">
                    🔖 Tags
                </a>
                <a href="{{ route('admin.users.index') }}" class="admin-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    👥 Users
                </a>
                <hr class="my-4 border-gray-700">
                <a href="{{ route('home') }}" class="admin-nav-link">
                    🏠 View Site
                </a>
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="admin-nav-link w-full text-left">
                        🚪 Logout
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-content flex-1">
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
