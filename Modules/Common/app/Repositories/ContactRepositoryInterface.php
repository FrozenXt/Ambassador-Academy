<?php

namespace Modules\Common\Repositories;

interface ContactRepositoryInterface
{
    public function paginate(int $perPage = 15, array $filters = []);
    public function findById(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function markAsRead(int $id);
    public function reply(int $id, string $reply);
    public function getStats();
    public function getUnreadCount();
    public function bulkDelete(array $ids);
    public function bulkMarkRead(array $ids);
}
