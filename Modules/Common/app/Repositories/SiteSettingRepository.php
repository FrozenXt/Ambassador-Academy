<?php

namespace Modules\Common\Repositories;

use Modules\Common\Entities\SiteSetting;

class SiteSettingRepository implements SiteSettingRepositoryInterface
{
    protected $model;

    public function __construct(SiteSetting $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->all()->groupBy('group');
    }

    public function getByGroup(string $group)
    {
        return $this->model->where('group', $group)->get();
    }

    public function getByKey(string $key)
    {
        return $this->model->where('key', $key)->first();
    }

    public function updateOrCreate(string $key, $value, string $group, string $type = 'text', ?string $icon = null)
    {
        $data = ['value' => $value, 'group' => $group, 'type' => $type];

        if ($icon !== null) {
            $data['icon'] = $icon;
        }

        return \Modules\Common\Entities\SiteSetting::updateOrCreate(
            ['key' => $key],
            $data
        );
    }

    public function setActive(string $key, bool $active): void
    {
        \Modules\Common\Entities\SiteSetting::where('key', $key)
            ->update(['is_active' => $active]);
    }

    public function updateGroup(string $group, array $data)
    {
        foreach ($data as $key => $value) {
            $this->model->where('key', $key)->update(['value' => $value]);
        }
        return true;
    }

    public function resetToDefault(string $group = null)
    {
        $query = $this->model->query();
        if ($group) {
            $query->where('group', $group);
        }
        return $query->update(['value' => null]);
    }
    public function deleteByKey(string $key): bool
    {
        return \Modules\Common\Entities\SiteSetting::where('key', $key)->delete(); // permanent, no SoftDeletes trait = hard delete
    }
}
