<?php

namespace Modules\Common\Repositories;

interface SiteSettingRepositoryInterface
{
    public function getAll();
    public function getByGroup(string $group);
    public function getByKey(string $key);
    public function updateOrCreate(string $key, $value, string $group, string $type = 'text', ?string $icon = null);
    public function setActive(string $key, bool $active): void;
    public function updateGroup(string $group, array $data);
    public function resetToDefault(string $group = null);
    public function deleteByKey(string $key): bool;
}
