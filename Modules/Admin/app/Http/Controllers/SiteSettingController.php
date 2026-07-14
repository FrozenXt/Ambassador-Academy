<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Common\Services\SiteSettingService;
use Modules\Admin\Http\Requests\{
    GeneralSettingsRequest,
    SocialSettingsRequest,
    SeoSettingsRequest,
    ScriptsSettingsRequest,
    FooterSettingsRequest
};

class SiteSettingController extends Controller
{
    protected $service;

    public function __construct(SiteSettingService $service)
    {
        $this->service = $service;
    }

    // General Settings
    public function general()
    {
        $settings = $this->service->getByGroup('general');
        return view('admin::settings.general', compact('settings'));
    }

    public function updateGeneral(GeneralSettingsRequest $request)
    {
        $data = $request->only([
            'site_name',
            'site_sub',
            'site_email',
            'site_phone',
            'site_telephone',
            'site_address',
            'site_description',
            'recaptcha_site_key',
            'recaptcha_secret_key',
            'google_map_embed',
            'opening_hours_weekday',
            'opening_hours_weekend',

        ]);

        $logo    = $request->file('site_logo') ?: $request->input('old_site_logo');
        $favicon = $request->file('site_favicon') ?: $request->input('old_site_favicon');

        $this->service->updateGeneral($data, $logo, $favicon);

        return back()->with('success', 'General settings updated successfully.');
    }

    // Social Settings
    public function social()
    {
        $settings = $this->service->getByGroup('social');
        return view('admin::settings.social', compact('settings'));
    }

    public function updateSocial(SocialSettingsRequest $request)
    {
        $this->service->updateSocial($request->only([
            'facebook_url',
            'twitter_url',
            'instagram_url',
            'youtube_url',
            'linkedin_url',
            'whatsapp_url',
            'viber_url',
            'tiktok_url',
        ]));

        return back()->with('success', 'Social settings updated successfully.');
    }

    // SEO Settings
    public function seo()
    {
        $settings = $this->service->getByGroup('seo');
        return view('admin::settings.seo', compact('settings'));
    }

    public function updateSeo(SeoSettingsRequest $request)
    {
        $this->service->updateSeo($request->only([
            'meta_title',
            'meta_description',
            'meta_keywords',
            'google_analytics',
        ]));

        return back()->with('success', 'SEO settings updated successfully.');
    }

    // Scripts
    public function scripts()
    {
        $settings = $this->service->getByGroup('scripts');
        return view('admin::settings.scripts', compact('settings'));
    }

    public function updateScripts(ScriptsSettingsRequest $request)
    {
        $this->service->updateScripts($request->only(['header_scripts', 'footer_scripts']));

        return back()->with('success', 'Scripts updated successfully.');
    }

    // Footer
    public function footer()
    {
        $settings = $this->service->getByGroup('footer');
        return view('admin::settings.footer', compact('settings'));
    }

    public function updateFooter(FooterSettingsRequest $request)
    {
        $this->service->updateFooter($request->only(['footer_text', 'footer_about']));

        return back()->with('success', 'Footer settings updated successfully.');
    }

    // Reset
    public function reset(string $group)
    {
        $this->service->resetGroup($group);
        return back()->with('success', ucfirst($group) . ' settings reset to default.');
    }
}
