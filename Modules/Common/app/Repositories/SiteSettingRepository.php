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

    public function updateOrCreate(string $key, $value, string $group = 'general', string $type = 'text')
    {
        return $this->model->updateOrCreate(
            ['key'   => $key],
            ['value' => $value, 'group' => $group, 'type' => $type]
        );
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
}
