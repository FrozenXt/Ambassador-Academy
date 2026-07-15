<?php

namespace Modules\Common\Services;

use Modules\Common\Repositories\SiteSettingRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class SiteSettingService
{
    protected $repository;

    public function __construct(SiteSettingRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAllGrouped()
    {
        return $this->repository->getAll();
    }

    public function getByGroup(string $group)
    {
        return $this->repository->getByGroup($group);
    }

    public function getByKey(string $key, $default = null)
    {
        $setting = $this->repository->getByKey($key);
        return $setting ? $setting->value : $default;
    }

    public function updateGeneral(array $data, $logo = null, $favicon = null)
    {
        // Handle logo
        if ($logo && $logo instanceof \Illuminate\Http\UploadedFile) {
            $data['site_logo'] = $logo->store('settings', 'public');
        } else if (is_string($logo)) {
            $data['site_logo'] = $logo; // keep old value
        }

        // Handle favicon
        if ($favicon && $favicon instanceof \Illuminate\Http\UploadedFile) {
            $data['site_favicon'] = $favicon->store('settings', 'public');
        } else if (is_string($favicon)) {
            $data['site_favicon'] = $favicon; // keep old value
        }

        foreach ($data as $key => $value) {
            \Modules\Common\Entities\SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => 'general']
            );
        }
    }
    public function updateSocial(array $data)
    {
        foreach ($data as $key => $value) {
            $this->repository->updateOrCreate($key, $value, 'social', 'url');
        }
        $this->clearCache();
        return true;
    }

    public function updateSeo(array $data)
    {
        foreach ($data as $key => $value) {
            $type = $key === 'meta_description' ? 'textarea' : 'text';
            $this->repository->updateOrCreate($key, $value, 'seo', $type);
        }
        $this->clearCache();
        return true;
    }

    public function updateScripts(array $data)
    {
        foreach ($data as $key => $value) {
            $this->repository->updateOrCreate($key, $value, 'scripts', 'textarea');
        }
        $this->clearCache();
        return true;
    }

    public function updateFooter(array $data)
    {
        foreach ($data as $key => $value) {
            $this->repository->updateOrCreate($key, $value, 'footer', 'text');
        }
        $this->clearCache();
        return true;
    }

    public function resetGroup(string $group)
    {
        $this->repository->resetToDefault($group);
        $this->clearCache();
        return true;
    }

    private function clearCache()
    {
        Cache::forget('site_settings');
    }
}
