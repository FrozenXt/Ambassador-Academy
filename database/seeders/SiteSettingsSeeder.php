<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Common\Entities\SiteSetting;

class SiteSettingsSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            // General
            ['key' => 'site_name',        'value' => 'My CMS',           'group' => 'general', 'type' => 'text'],
            ['key' => 'site_email',       'value' => 'info@mycms.com',    'group' => 'general', 'type' => 'email'],
            ['key' => 'site_phone',       'value' => '+977-9800000000',   'group' => 'general', 'type' => 'text'],
            ['key' => 'site_address',     'value' => 'Kathmandu, Nepal',  'group' => 'general', 'type' => 'text'],
            ['key' => 'site_description', 'value' => 'My CMS Description', 'group' => 'general', 'type' => 'textarea'],
            ['key' => 'site_logo',        'value' => null,                'group' => 'general', 'type' => 'image'],
            ['key' => 'site_favicon',     'value' => null,                'group' => 'general', 'type' => 'image'],

            // Social
            ['key' => 'facebook_url',  'value' => '#', 'group' => 'social', 'type' => 'url'],
            ['key' => 'twitter_url',   'value' => '#', 'group' => 'social', 'type' => 'url'],
            ['key' => 'instagram_url', 'value' => '#', 'group' => 'social', 'type' => 'url'],
            ['key' => 'youtube_url',   'value' => '#', 'group' => 'social', 'type' => 'url'],
            ['key' => 'linkedin_url',  'value' => '#', 'group' => 'social', 'type' => 'url'],
            ['key' => 'whatsapp_url',  'value' => '#', 'group' => 'social', 'type' => 'url'],
            ['key' => 'viber_url',     'value' => '#', 'group' => 'social', 'type' => 'url'],
            ['key' => 'tiktok_url',    'value' => '#', 'group' => 'social', 'type' => 'url'],

            // SEO
            ['key' => 'meta_title',       'value' => 'My CMS',        'group' => 'seo', 'type' => 'text'],
            ['key' => 'meta_description', 'value' => 'My CMS Website', 'group' => 'seo', 'type' => 'textarea'],
            ['key' => 'meta_keywords',    'value' => 'cms, website',   'group' => 'seo', 'type' => 'text'],
            ['key' => 'google_analytics', 'value' => null,             'group' => 'seo', 'type' => 'textarea'],

            // Header & Footer
            ['key' => 'header_scripts', 'value' => null, 'group' => 'scripts', 'type' => 'textarea'],
            ['key' => 'footer_scripts', 'value' => null, 'group' => 'scripts', 'type' => 'textarea'],
            ['key' => 'footer_text',    'value' => '© 2026 My CMS. All rights reserved.', 'group' => 'footer', 'type' => 'text'],
            ['key' => 'footer_about',   'value' => 'We are a CMS website.', 'group' => 'footer', 'type' => 'textarea'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
