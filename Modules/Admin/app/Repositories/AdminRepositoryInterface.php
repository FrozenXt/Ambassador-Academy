<?php

namespace Modules\Admin\Repositories;

interface AdminRepositoryInterface
{
    public function findByEmail(string $email);
    public function findById(int $id);
    public function getTotalUsers();
    public function getTodayUsers();
    public function getThisMonthUsers();
    public function getRecentUsers(int $limit = 10);
}
