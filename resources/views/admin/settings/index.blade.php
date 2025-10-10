@extends('admin.layout')

@section('title', 'Settings')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800">Settings</h1>
    <p class="text-gray-600">Manage your website settings</p>
</div>

<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <!-- Tab Navigation -->
    <div class="bg-white rounded-t-lg shadow-md">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <button type="button" class="tab-button active" data-tab="general">
                    General
                </button>
                <button type="button" class="tab-button" data-tab="seo">
                    SEO
                </button>
                <button type="button" class="tab-button" data-tab="analytics">
                    Analytics
                </button>
                <button type="button" class="tab-button" data-tab="social">
                    Social Media
                </button>
                <button type="button" class="tab-button" data-tab="contact">
                    Contact
                </button>
                <button type="button" class="tab-button" data-tab="advanced">
                    Advanced
                </button>
            </nav>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="bg-white rounded-b-lg shadow-md p-6">
        <!-- General Tab -->
        <div class="tab-content active" id="general">
            <h2 class="text-xl font-bold mb-6">General Settings</h2>

            <div class="form-group">
                <label class="form-label">Site Title</label>
                <input type="text" name="site_title" value="{{ old('site_title', $settings['site_title'] ?? 'SME Comics') }}" class="form-input" placeholder="SME Comics">
                @error('site_title')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Site Tagline</label>
                <input type="text" name="site_tagline" value="{{ old('site_tagline', $settings['site_tagline'] ?? '') }}" class="form-input" placeholder="Read manga online for free">
                @error('site_tagline')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Site Description</label>
                <textarea name="site_description" rows="3" class="form-textarea" placeholder="A brief description of your website">{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>
                @error('site_description')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Site Logo</label>
                <input type="file" name="site_logo" class="form-input" accept="image/png,image/jpeg,image/jpg,image/svg+xml">
                @if(!empty($settings['site_logo']))
                    <div class="mt-2">
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($settings['site_logo']) }}" alt="Site Logo" class="max-h-20">
                        <button type="button" onclick="deleteImage('site_logo')" class="text-red-600 text-sm hover:underline mt-1">Remove Logo</button>
                    </div>
                @endif
                @error('site_logo')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Favicon</label>
                <input type="file" name="site_favicon" class="form-input" accept="image/png,image/x-icon,image/jpeg,image/jpg">
                @if(!empty($settings['site_favicon']))
                    <div class="mt-2">
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($settings['site_favicon']) }}" alt="Favicon" class="max-h-8">
                        <button type="button" onclick="deleteImage('site_favicon')" class="text-red-600 text-sm hover:underline mt-1">Remove Favicon</button>
                    </div>
                @endif
                @error('site_favicon')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                <p class="text-xs text-gray-500 mt-1">Recommended size: 32x32px or 16x16px</p>
            </div>
        </div>

        <!-- SEO Tab -->
        <div class="tab-content" id="seo">
            <h2 class="text-xl font-bold mb-6">SEO Settings</h2>

            <div class="form-group">
                <label class="form-label">Meta Keywords</label>
                <input type="text" name="site_keywords" value="{{ old('site_keywords', $settings['site_keywords'] ?? '') }}" class="form-input" placeholder="manga, manhwa, comics, read online">
                @error('site_keywords')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                <p class="text-xs text-gray-500 mt-1">Comma-separated keywords</p>
            </div>

            <div class="form-group">
                <label class="form-label">Site Author</label>
                <input type="text" name="site_author" value="{{ old('site_author', $settings['site_author'] ?? '') }}" class="form-input" placeholder="SME Comics Team">
                @error('site_author')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <h3 class="text-lg font-semibold mt-8 mb-4">Open Graph Tags</h3>

            <div class="form-group">
                <label class="form-label">OG Title</label>
                <input type="text" name="og_title" value="{{ old('og_title', $settings['og_title'] ?? '') }}" class="form-input" placeholder="Leave empty to use site title">
                @error('og_title')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">OG Description</label>
                <textarea name="og_description" rows="3" class="form-textarea" placeholder="Leave empty to use site description">{{ old('og_description', $settings['og_description'] ?? '') }}</textarea>
                @error('og_description')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">OG Type</label>
                <input type="text" name="og_type" value="{{ old('og_type', $settings['og_type'] ?? 'website') }}" class="form-input" placeholder="website">
                @error('og_type')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                <p class="text-xs text-gray-500 mt-1">Common values: website, article, blog</p>
            </div>

            <div class="form-group">
                <label class="form-label">OG Image</label>
                <input type="file" name="og_image" class="form-input" accept="image/png,image/jpeg,image/jpg">
                @if(!empty($settings['og_image']))
                    <div class="mt-2">
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($settings['og_image']) }}" alt="OG Image" class="max-h-32">
                        <button type="button" onclick="deleteImage('og_image')" class="text-red-600 text-sm hover:underline mt-1">Remove Image</button>
                    </div>
                @endif
                @error('og_image')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                <p class="text-xs text-gray-500 mt-1">Recommended size: 1200x630px</p>
            </div>
        </div>

        <!-- Analytics Tab -->
        <div class="tab-content" id="analytics">
            <h2 class="text-xl font-bold mb-6">Analytics & Tracking</h2>

            <div class="form-group">
                <label class="form-label">Google Analytics 4 Measurement ID</label>
                <input type="text" name="google_analytics" value="{{ old('google_analytics', $settings['google_analytics'] ?? '') }}" class="form-input" placeholder="G-XXXXXXXXXX">
                @error('google_analytics')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                <p class="text-xs text-gray-500 mt-1">Format: G-XXXXXXXXXX</p>
            </div>

            <div class="form-group">
                <label class="form-label">Google Tag Manager Container ID</label>
                <input type="text" name="google_tag_manager" value="{{ old('google_tag_manager', $settings['google_tag_manager'] ?? '') }}" class="form-input" placeholder="GTM-XXXXXXX">
                @error('google_tag_manager')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                <p class="text-xs text-gray-500 mt-1">Format: GTM-XXXXXXX</p>
            </div>

            <div class="form-group">
                <label class="form-label">Facebook Pixel ID</label>
                <input type="text" name="facebook_pixel" value="{{ old('facebook_pixel', $settings['facebook_pixel'] ?? '') }}" class="form-input" placeholder="123456789012345">
                @error('facebook_pixel')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Google Search Console Verification Code</label>
                <input type="text" name="google_search_console" value="{{ old('google_search_console', $settings['google_search_console'] ?? '') }}" class="form-input" placeholder="abcdefghijklmnopqrstuvwxyz">
                @error('google_search_console')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                <p class="text-xs text-gray-500 mt-1">The content value from the meta tag</p>
            </div>

            <div class="form-group">
                <label class="form-label">Bing Webmaster Verification Code</label>
                <input type="text" name="bing_webmaster" value="{{ old('bing_webmaster', $settings['bing_webmaster'] ?? '') }}" class="form-input" placeholder="abcdefghijklmnopqrstuvwxyz">
                @error('bing_webmaster')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>
        </div>

        <!-- Social Media Tab -->
        <div class="tab-content" id="social">
            <h2 class="text-xl font-bold mb-6">Social Media Links</h2>

            <div class="form-group">
                <label class="form-label">Facebook Page URL</label>
                <input type="url" name="social_facebook" value="{{ old('social_facebook', $settings['social_facebook'] ?? '') }}" class="form-input" placeholder="https://facebook.com/yourpage">
                @error('social_facebook')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Twitter/X Profile URL</label>
                <input type="url" name="social_twitter" value="{{ old('social_twitter', $settings['social_twitter'] ?? '') }}" class="form-input" placeholder="https://twitter.com/yourprofile">
                @error('social_twitter')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Instagram Profile URL</label>
                <input type="url" name="social_instagram" value="{{ old('social_instagram', $settings['social_instagram'] ?? '') }}" class="form-input" placeholder="https://instagram.com/yourprofile">
                @error('social_instagram')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Discord Server URL</label>
                <input type="url" name="social_discord" value="{{ old('social_discord', $settings['social_discord'] ?? '') }}" class="form-input" placeholder="https://discord.gg/yourserver">
                @error('social_discord')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Reddit Community URL</label>
                <input type="url" name="social_reddit" value="{{ old('social_reddit', $settings['social_reddit'] ?? '') }}" class="form-input" placeholder="https://reddit.com/r/yourcommunity">
                @error('social_reddit')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">YouTube Channel URL</label>
                <input type="url" name="social_youtube" value="{{ old('social_youtube', $settings['social_youtube'] ?? '') }}" class="form-input" placeholder="https://youtube.com/@yourchannel">
                @error('social_youtube')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>
        </div>

        <!-- Contact Tab -->
        <div class="tab-content" id="contact">
            <h2 class="text-xl font-bold mb-6">Contact Information</h2>

            <div class="form-group">
                <label class="form-label">Contact Email</label>
                <input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email'] ?? '') }}" class="form-input" placeholder="contact@smecomics.com">
                @error('contact_email')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Contact Phone</label>
                <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}" class="form-input" placeholder="+1 (555) 123-4567">
                @error('contact_phone')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Contact Address</label>
                <textarea name="contact_address" rows="3" class="form-textarea" placeholder="123 Main St, City, State, ZIP">{{ old('contact_address', $settings['contact_address'] ?? '') }}</textarea>
                @error('contact_address')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Support Page URL</label>
                <input type="url" name="support_url" value="{{ old('support_url', $settings['support_url'] ?? '') }}" class="form-input" placeholder="https://support.smecomics.com">
                @error('support_url')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <h3 class="text-lg font-semibold mt-8 mb-4">Footer Content</h3>

            <div class="form-group">
                <label class="form-label">Copyright Text</label>
                <input type="text" name="footer_copyright" value="{{ old('footer_copyright', $settings['footer_copyright'] ?? '© ' . date('Y') . ' SME Comics. All rights reserved.') }}" class="form-input" placeholder="© 2025 SME Comics. All rights reserved.">
                @error('footer_copyright')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Disclaimer Text</label>
                <textarea name="footer_disclaimer" rows="3" class="form-textarea" placeholder="Disclaimer text...">{{ old('footer_disclaimer', $settings['footer_disclaimer'] ?? 'All manga content is the property of their respective owners.') }}</textarea>
                @error('footer_disclaimer')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Custom Footer Text (HTML allowed)</label>
                <textarea name="footer_custom_text" rows="5" class="form-textarea" placeholder="Additional footer content...">{{ old('footer_custom_text', $settings['footer_custom_text'] ?? '') }}</textarea>
                @error('footer_custom_text')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>
        </div>

        <!-- Advanced Tab -->
        <div class="tab-content" id="advanced">
            <h2 class="text-xl font-bold mb-6">Advanced Settings</h2>

            <h3 class="text-lg font-semibold mb-4">Maintenance Mode</h3>

            <div class="form-group">
                <label class="flex items-center">
                    <input type="checkbox" name="maintenance_mode" value="1" {{ old('maintenance_mode', $settings['maintenance_mode'] ?? '0') == '1' ? 'checked' : '' }} class="form-checkbox">
                    <span class="ml-2 text-sm text-gray-700">Enable Maintenance Mode</span>
                </label>
                @error('maintenance_mode')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Maintenance Message</label>
                <textarea name="maintenance_message" rows="3" class="form-textarea" placeholder="We'll be back soon!">{{ old('maintenance_message', $settings['maintenance_message'] ?? 'We are currently performing scheduled maintenance. We\'ll be back soon!') }}</textarea>
                @error('maintenance_message')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <h3 class="text-lg font-semibold mt-8 mb-4">Display Settings</h3>

            <div class="form-group">
                <label class="form-label">Timezone</label>
                <select name="timezone" class="form-select">
                    <option value="UTC" {{ old('timezone', $settings['timezone'] ?? 'UTC') == 'UTC' ? 'selected' : '' }}>UTC</option>
                    <option value="America/New_York" {{ old('timezone', $settings['timezone'] ?? '') == 'America/New_York' ? 'selected' : '' }}>America/New York</option>
                    <option value="America/Chicago" {{ old('timezone', $settings['timezone'] ?? '') == 'America/Chicago' ? 'selected' : '' }}>America/Chicago</option>
                    <option value="America/Los_Angeles" {{ old('timezone', $settings['timezone'] ?? '') == 'America/Los_Angeles' ? 'selected' : '' }}>America/Los Angeles</option>
                    <option value="Europe/London" {{ old('timezone', $settings['timezone'] ?? '') == 'Europe/London' ? 'selected' : '' }}>Europe/London</option>
                    <option value="Europe/Paris" {{ old('timezone', $settings['timezone'] ?? '') == 'Europe/Paris' ? 'selected' : '' }}>Europe/Paris</option>
                    <option value="Asia/Tokyo" {{ old('timezone', $settings['timezone'] ?? '') == 'Asia/Tokyo' ? 'selected' : '' }}>Asia/Tokyo</option>
                    <option value="Asia/Shanghai" {{ old('timezone', $settings['timezone'] ?? '') == 'Asia/Shanghai' ? 'selected' : '' }}>Asia/Shanghai</option>
                    <option value="Asia/Ho_Chi_Minh" {{ old('timezone', $settings['timezone'] ?? '') == 'Asia/Ho_Chi_Minh' ? 'selected' : '' }}>Asia/Ho Chi Minh</option>
                    <option value="Australia/Sydney" {{ old('timezone', $settings['timezone'] ?? '') == 'Australia/Sydney' ? 'selected' : '' }}>Australia/Sydney</option>
                </select>
                @error('timezone')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Date Format</label>
                <select name="date_format" class="form-select">
                    <option value="Y-m-d" {{ old('date_format', $settings['date_format'] ?? 'Y-m-d') == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD (2025-01-15)</option>
                    <option value="m/d/Y" {{ old('date_format', $settings['date_format'] ?? '') == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY (01/15/2025)</option>
                    <option value="d/m/Y" {{ old('date_format', $settings['date_format'] ?? '') == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY (15/01/2025)</option>
                    <option value="F j, Y" {{ old('date_format', $settings['date_format'] ?? '') == 'F j, Y' ? 'selected' : '' }}>Month DD, YYYY (January 15, 2025)</option>
                </select>
                @error('date_format')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Items Per Page</label>
                <input type="number" name="items_per_page" value="{{ old('items_per_page', $settings['items_per_page'] ?? '20') }}" class="form-input" min="5" max="100">
                @error('items_per_page')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                <p class="text-xs text-gray-500 mt-1">Default pagination limit (5-100)</p>
            </div>

            <h3 class="text-lg font-semibold mt-8 mb-4">Custom Code</h3>

            <div class="form-group">
                <label class="form-label">Custom CSS</label>
                <textarea name="custom_css" rows="6" class="form-textarea font-mono text-sm" placeholder=".custom-class { color: red; }">{{ old('custom_css', $settings['custom_css'] ?? '') }}</textarea>
                @error('custom_css')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                <p class="text-xs text-gray-500 mt-1">Will be injected in the &lt;head&gt; section</p>
            </div>

            <div class="form-group">
                <label class="form-label">Custom JavaScript (Header)</label>
                <textarea name="custom_js_header" rows="6" class="form-textarea font-mono text-sm" placeholder="console.log('Custom JS');">{{ old('custom_js_header', $settings['custom_js_header'] ?? '') }}</textarea>
                @error('custom_js_header')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                <p class="text-xs text-gray-500 mt-1">Will be injected in the &lt;head&gt; section</p>
            </div>

            <div class="form-group">
                <label class="form-label">Custom JavaScript (Footer)</label>
                <textarea name="custom_js_footer" rows="6" class="form-textarea font-mono text-sm" placeholder="console.log('Custom JS');">{{ old('custom_js_footer', $settings['custom_js_footer'] ?? '') }}</textarea>
                @error('custom_js_footer')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                <p class="text-xs text-gray-500 mt-1">Will be injected before closing &lt;/body&gt; tag</p>
            </div>

            <div class="form-group">
                <label class="form-label">robots.txt Content</label>
                <textarea name="robots_txt" rows="8" class="form-textarea font-mono text-sm" placeholder="User-agent: *&#10;Disallow:">{{ old('robots_txt', $settings['robots_txt'] ?? '') }}</textarea>
                @error('robots_txt')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                <p class="text-xs text-gray-500 mt-1">Custom robots.txt rules (if you want to override default)</p>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <button type="submit" class="btn-admin-primary">
                Save Settings
            </button>
        </div>
    </div>
</form>

@push('styles')
    <style>
        .tab-button {
            padding: 1rem 1.5rem;
            border-bottom: 2px solid transparent;
            font-medium;
            color: #6b7280;
            transition: all 0.2s;
        }

        .tab-button:hover {
            color: #f97316;
        }

        .tab-button.active {
            color: #f97316;
            border-bottom-color: #f97316;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }
    </style>
@endpush

@push('scripts')
<script>
// Tab switching
document.querySelectorAll('.tab-button').forEach(button => {
    button.addEventListener('click', () => {
        const tab = button.dataset.tab;

        // Remove active class from all buttons and contents
        document.querySelectorAll('.tab-button').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

        // Add active class to clicked button and corresponding content
        button.classList.add('active');
        document.getElementById(tab).classList.add('active');
    });
});

// Delete image function
function deleteImage(field) {
    if (!confirm('Are you sure you want to delete this image?')) {
        return;
    }

    fetch('{{ route('admin.settings.delete-image') }}', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ field: field })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}
</script>
@endpush
@endsection
