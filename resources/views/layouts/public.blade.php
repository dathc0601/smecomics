<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ setting('site_title', config('app.name', 'SME Comics')) }} - @yield('title', setting('site_tagline', 'Read Manga Online'))</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="{{ setting('site_description', 'Read manga online for free') }}">
    <meta name="keywords" content="{{ setting('site_keywords', 'manga, manhwa, comics') }}">
    <meta name="author" content="{{ setting('site_author', 'SME Comics') }}">

    <!-- Open Graph Tags -->
    <meta property="og:title" content="{{ setting('og_title', setting('site_title', 'SME Comics')) }}">
    <meta property="og:description" content="{{ setting('og_description', setting('site_description', 'Read manga online for free')) }}">
    <meta property="og:type" content="{{ setting('og_type', 'website') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    @if(setting('og_image'))
    <meta property="og:image" content="{{ \Illuminate\Support\Facades\Storage::url(setting('og_image')) }}">
    @endif

    <!-- Favicon -->
    @if(setting('site_favicon'))
    <link rel="icon" type="image/x-icon" href="{{ \Illuminate\Support\Facades\Storage::url(setting('site_favicon')) }}">
    @endif

    <!-- Google Search Console Verification -->
    @if(setting('google_search_console'))
    <meta name="google-site-verification" content="{{ setting('google_search_console') }}">
    @endif

    <!-- Bing Webmaster Verification -->
    @if(setting('bing_webmaster'))
    <meta name="msvalidate.01" content="{{ setting('bing_webmaster') }}">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom CSS -->
    @if(setting('custom_css'))
    <style>{!! setting('custom_css') !!}</style>
    @endif

    <!-- Custom JavaScript (Header) -->
    @if(setting('custom_js_header'))
    <script>{!! setting('custom_js_header') !!}</script>
    @endif

    <!-- Google Analytics 4 -->
    @if(setting('google_analytics'))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ setting('google_analytics') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ setting('google_analytics') }}');
    </script>
    @endif

    <!-- Google Tag Manager -->
    @if(setting('google_tag_manager'))
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','{{ setting('google_tag_manager') }}');</script>
    @endif

    <!-- Facebook Pixel -->
    @if(setting('facebook_pixel'))
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '{{ setting('facebook_pixel') }}');
    fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id={{ setting('facebook_pixel') }}&ev=PageView&noscript=1"
    /></noscript>
    @endif
</head>
<body class="font-sans antialiased bg-manga-gradient min-h-screen">
    <!-- Navigation -->
    <nav class="bg-manga-nav shadow-manga sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-white flex items-center">
                        @if(setting('site_logo'))
                            <img src="{{ \Illuminate\Support\Facades\Storage::url(setting('site_logo')) }}" alt="{{ setting('site_title', 'SME Comics') }}" class="h-8 mr-2">
                        @else
                            <span>{{ setting('site_title', 'SME Comics') }}</span>
                        @endif
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
                {!! setting('footer_copyright', '&copy; ' . date('Y') . ' SME Comics. All rights reserved.') !!}
            </p>
            @if(setting('footer_disclaimer'))
            <p class="text-sm text-peach-600 mt-2">
                {!! setting('footer_disclaimer') !!}
            </p>
            @endif
            @if(setting('footer_custom_text'))
            <div class="text-sm text-peach-600 mt-4">
                {!! setting('footer_custom_text') !!}
            </div>
            @endif

            <!-- Social Media Links -->
            @if(setting('social_facebook') || setting('social_twitter') || setting('social_instagram') || setting('social_discord') || setting('social_reddit') || setting('social_youtube'))
            <div class="flex justify-center space-x-4 mt-6">
                @if(setting('social_facebook'))
                <a href="{{ setting('social_facebook') }}" target="_blank" rel="noopener" class="text-peach-600 hover:text-peach-800 transition">Facebook</a>
                @endif
                @if(setting('social_twitter'))
                <a href="{{ setting('social_twitter') }}" target="_blank" rel="noopener" class="text-peach-600 hover:text-peach-800 transition">Twitter</a>
                @endif
                @if(setting('social_instagram'))
                <a href="{{ setting('social_instagram') }}" target="_blank" rel="noopener" class="text-peach-600 hover:text-peach-800 transition">Instagram</a>
                @endif
                @if(setting('social_discord'))
                <a href="{{ setting('social_discord') }}" target="_blank" rel="noopener" class="text-peach-600 hover:text-peach-800 transition">Discord</a>
                @endif
                @if(setting('social_reddit'))
                <a href="{{ setting('social_reddit') }}" target="_blank" rel="noopener" class="text-peach-600 hover:text-peach-800 transition">Reddit</a>
                @endif
                @if(setting('social_youtube'))
                <a href="{{ setting('social_youtube') }}" target="_blank" rel="noopener" class="text-peach-600 hover:text-peach-800 transition">YouTube</a>
                @endif
            </div>
            @endif

            <!-- Contact Information -->
            @if(setting('contact_email') || setting('contact_phone'))
            <div class="text-sm text-peach-600 mt-4">
                @if(setting('contact_email'))
                <p>Email: <a href="mailto:{{ setting('contact_email') }}" class="hover:text-peach-800">{{ setting('contact_email') }}</a></p>
                @endif
                @if(setting('contact_phone'))
                <p>Phone: {{ setting('contact_phone') }}</p>
                @endif
            </div>
            @endif
        </div>
    </footer>

    @stack('scripts')

    <!-- Custom JavaScript (Footer) -->
    @if(setting('custom_js_footer'))
    <script>{!! setting('custom_js_footer') !!}</script>
    @endif

    <!-- Google Tag Manager (noscript) -->
    @if(setting('google_tag_manager'))
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ setting('google_tag_manager') }}"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @endif
</body>
</html>
