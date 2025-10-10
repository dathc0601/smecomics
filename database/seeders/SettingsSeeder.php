<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'site_title', 'value' => 'SME Comics', 'type' => 'text'],
            ['key' => 'site_tagline', 'value' => 'Read manga online for free', 'type' => 'text'],
            ['key' => 'site_description', 'value' => 'Read the latest manga chapters online for free. Updated daily with new releases.', 'type' => 'textarea'],
            ['key' => 'site_logo', 'value' => '', 'type' => 'image'],
            ['key' => 'site_favicon', 'value' => '', 'type' => 'image'],

            // SEO
            ['key' => 'site_keywords', 'value' => 'manga, manhwa, comics, read manga online, free manga', 'type' => 'text'],
            ['key' => 'site_author', 'value' => 'SME Comics Team', 'type' => 'text'],
            ['key' => 'og_title', 'value' => '', 'type' => 'text'],
            ['key' => 'og_description', 'value' => '', 'type' => 'textarea'],
            ['key' => 'og_type', 'value' => 'website', 'type' => 'text'],
            ['key' => 'og_image', 'value' => '', 'type' => 'image'],

            // Analytics
            ['key' => 'google_analytics', 'value' => '', 'type' => 'text'],
            ['key' => 'google_tag_manager', 'value' => '', 'type' => 'text'],
            ['key' => 'facebook_pixel', 'value' => '', 'type' => 'text'],
            ['key' => 'google_search_console', 'value' => '', 'type' => 'text'],
            ['key' => 'bing_webmaster', 'value' => '', 'type' => 'text'],

            // Social Media
            ['key' => 'social_facebook', 'value' => '', 'type' => 'text'],
            ['key' => 'social_twitter', 'value' => '', 'type' => 'text'],
            ['key' => 'social_instagram', 'value' => '', 'type' => 'text'],
            ['key' => 'social_discord', 'value' => '', 'type' => 'text'],
            ['key' => 'social_reddit', 'value' => '', 'type' => 'text'],
            ['key' => 'social_youtube', 'value' => '', 'type' => 'text'],

            // Contact
            ['key' => 'contact_email', 'value' => '', 'type' => 'text'],
            ['key' => 'contact_phone', 'value' => '', 'type' => 'text'],
            ['key' => 'contact_address', 'value' => '', 'type' => 'textarea'],
            ['key' => 'support_url', 'value' => '', 'type' => 'text'],

            // Footer
            ['key' => 'footer_copyright', 'value' => '© ' . date('Y') . ' SME Comics. All rights reserved.', 'type' => 'text'],
            ['key' => 'footer_disclaimer', 'value' => 'All manga content is the property of their respective owners.', 'type' => 'textarea'],
            ['key' => 'footer_custom_text', 'value' => '', 'type' => 'textarea'],

            // Advanced
            ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean'],
            ['key' => 'maintenance_message', 'value' => 'We are currently performing scheduled maintenance. We\'ll be back soon!', 'type' => 'textarea'],
            ['key' => 'timezone', 'value' => 'UTC', 'type' => 'text'],
            ['key' => 'date_format', 'value' => 'Y-m-d', 'type' => 'text'],
            ['key' => 'items_per_page', 'value' => '20', 'type' => 'number'],
            ['key' => 'custom_css', 'value' => '', 'type' => 'textarea'],
            ['key' => 'custom_js_header', 'value' => '', 'type' => 'textarea'],
            ['key' => 'custom_js_footer', 'value' => '', 'type' => 'textarea'],
            ['key' => 'robots_txt', 'value' => '', 'type' => 'textarea'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'type' => $setting['type']]
            );
        }

        $this->command->info('Default settings have been seeded successfully!');
    }
}
