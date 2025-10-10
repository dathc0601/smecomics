<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Display the settings page
     */
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        // Define validation rules
        $rules = [
            'site_title' => 'nullable|string|max:255',
            'site_tagline' => 'nullable|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'site_keywords' => 'nullable|string|max:500',
            'site_author' => 'nullable|string|max:255',

            // Images
            'site_logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'site_favicon' => 'nullable|image|mimes:png,ico,jpg,jpeg|max:1024',
            'og_image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',

            // SEO
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'og_type' => 'nullable|string|max:50',

            // Analytics
            'google_analytics' => 'nullable|string|max:50',
            'google_tag_manager' => 'nullable|string|max:50',
            'facebook_pixel' => 'nullable|string|max:50',
            'google_search_console' => 'nullable|string|max:100',
            'bing_webmaster' => 'nullable|string|max:100',

            // Social
            'social_facebook' => 'nullable|url|max:255',
            'social_twitter' => 'nullable|url|max:255',
            'social_instagram' => 'nullable|url|max:255',
            'social_discord' => 'nullable|url|max:255',
            'social_reddit' => 'nullable|url|max:255',
            'social_youtube' => 'nullable|url|max:255',

            // Contact
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'contact_address' => 'nullable|string|max:500',
            'support_url' => 'nullable|url|max:255',

            // Footer
            'footer_copyright' => 'nullable|string|max:500',
            'footer_disclaimer' => 'nullable|string|max:1000',
            'footer_custom_text' => 'nullable|string|max:1000',

            // Advanced
            'maintenance_mode' => 'nullable|boolean',
            'maintenance_message' => 'nullable|string|max:500',
            'timezone' => 'nullable|string|max:100',
            'date_format' => 'nullable|string|max:50',
            'items_per_page' => 'nullable|integer|min:5|max:100',
            'custom_css' => 'nullable|string',
            'custom_js_header' => 'nullable|string',
            'custom_js_footer' => 'nullable|string',
            'robots_txt' => 'nullable|string',
        ];

        $validated = $request->validate($rules);

        // Handle file uploads
        $imageFields = ['site_logo', 'site_favicon', 'og_image'];

        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                // Delete old image if exists
                $oldImage = Setting::get($field);
                if ($oldImage) {
                    Setting::deleteImage($oldImage);
                }

                // Upload new image
                $path = $request->file($field)->store('settings', 'public');
                Setting::set($field, $path, 'image');
            }
        }

        // Save all other settings
        foreach ($validated as $key => $value) {
            // Skip file uploads (already handled)
            if (in_array($key, $imageFields)) {
                continue;
            }

            // Determine type
            $type = 'text';
            if ($key === 'maintenance_mode') {
                $type = 'boolean';
                $value = $request->has('maintenance_mode') ? '1' : '0';
            } elseif (in_array($key, ['items_per_page'])) {
                $type = 'number';
            } elseif (in_array($key, ['site_description', 'footer_disclaimer', 'footer_custom_text', 'custom_css', 'custom_js_header', 'custom_js_footer', 'robots_txt', 'maintenance_message'])) {
                $type = 'textarea';
            }

            Setting::set($key, $value ?? '', $type);
        }

        return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully!');
    }

    /**
     * Delete an uploaded image
     */
    public function deleteImage(Request $request)
    {
        $field = $request->input('field');

        if (!in_array($field, ['site_logo', 'site_favicon', 'og_image'])) {
            return response()->json(['success' => false, 'message' => 'Invalid field']);
        }

        $oldImage = Setting::get($field);
        if ($oldImage) {
            Setting::deleteImage($oldImage);
            Setting::set($field, '', 'image');
        }

        return response()->json(['success' => true]);
    }
}
