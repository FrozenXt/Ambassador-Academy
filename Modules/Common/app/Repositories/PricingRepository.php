<?php

namespace Modules\Common\Repositories;

use Modules\Common\Entities\Pricing;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PricingRepository implements PricingRepositoryInterface
{
    protected $model;

    public function __construct(Pricing $model)
    {
        $this->model = $model;
    }

    public function getAll(array $filters = []): Collection
    {
        $query = $this->model->query();
        return $this->applyFilters($query, $filters)->ordered()->get();
    }

    public function getPaginated(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->model->query();
        return $this->applyFilters($query, $filters)->ordered()->paginate($perPage);
    }

    public function findById(int $id): ?Pricing
    {
        return $this->model->findOrFail($id);
    }

    public function findBySlug(string $slug): ?Pricing
    {
        return $this->model->where('slug', $slug)->first();
    }

    public function create(array $data): Pricing
    {
        if (isset($data['features']) && is_array($data['features'])) {
            $data['features'] = array_values(array_filter($data['features']));
        }

        return $this->model->create($data);
    }

    public function update(int $id, array $data): Pricing
    {
        $pricing = $this->findById($id);

        if (isset($data['features']) && is_array($data['features'])) {
            $data['features'] = array_values(array_filter($data['features']));
        }

        $pricing->update($data);
        return $pricing->fresh();
    }

    public function delete(int $id): bool
    {
        $pricing = $this->findById($id);
        return $pricing->delete();
    }

    public function getActivePricings(): Collection
    {
        return $this->model->active()->ordered()->get();
    }

    public function toggleStatus(int $id): Pricing
    {
        $pricing = $this->findById($id);
        $pricing->is_active = !$pricing->is_active;
        $pricing->save();
        return $pricing;
    }

    public function updateSortOrder(array $orders): bool
    {
        foreach ($orders as $id => $order) {
            $this->model->where('id', $id)->update(['sort_order' => $order]);
        }
        return true;
    }

    protected function applyFilters(Builder $query, array $filters): Builder
    {
        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        return $query;
    }
}
