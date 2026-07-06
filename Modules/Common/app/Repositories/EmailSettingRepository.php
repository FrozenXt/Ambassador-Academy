<?php

namespace Modules\Common\Repositories;

use Modules\Common\Entities\EmailSetting;

class EmailSettingRepository implements EmailSettingRepositoryInterface
{
    protected $model;

    public function __construct(EmailSetting $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->latest()->get();
    }

    public function findById(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function getActive()
    {
        return $this->model->where('is_active', true)->latest()->first();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $setting = $this->findById($id);
        $setting->update($data);
        return $setting;
    }

    public function delete(int $id)
    {
        $setting = $this->findById($id);
        $setting->delete();
        return true;
    }

    public function setActive(int $id)
    {
        // Deactivate all
        $this->model->where('is_active', true)->update(['is_active' => false]);
        // Activate selected
        $setting = $this->findById($id);
        $setting->update(['is_active' => true]);
        return $setting;
    }
}
