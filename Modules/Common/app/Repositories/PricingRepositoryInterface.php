<?php


namespace Modules\Common\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Common\Entities\Pricing;

interface PricingRepositoryInterface
{
    public function getAll(array $filters = []): Collection;
    public function getPaginated(array $filters = [], int $perPage = 10): LengthAwarePaginator;
    public function findById(int $id): ?Pricing;
    public function findBySlug(string $slug): ?Pricing;
    public function create(array $data): Pricing;
    public function update(int $id, array $data): Pricing;
    public function delete(int $id): bool;
    public function getActivePricings(): Collection;
    public function toggleStatus(int $id): Pricing;
    public function updateSortOrder(array $orders): bool;
}
