<?php


namespace Modules\Common\Repositories;

use Modules\Common\Entities\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

\Illuminate\Support\Facades\DB::raw('count(*) as total');

class ClientRepository implements ClientRepositoryInterface
{
    protected $model;

    public function __construct(Client $model)
    {
        $this->model = $model;
    }

    public function getAll(array $filters = []): Collection
    {
        $query = $this->model->with('assignedUser');

        return $this->applyFilters($query, $filters)->latest()->get();
    }

    public function getPaginated(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->model->with('assignedUser');

        return $this->applyFilters($query, $filters)->latest()->paginate($perPage);
    }

    public function findById(int $id): ?Client
    {
        return $this->model->with('assignedUser')->findOrFail($id);
    }

    public function findByEmail(string $email): ?Client
    {
        return $this->model->where('email', $email)->first();
    }

    public function findByClientCode(string $clientCode): ?Client
    {
        return $this->model->where('client_code', $clientCode)->first();
    }

    public function create(array $data): Client
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Client
    {
        $client = $this->findById($id);
        $client->update($data);
        return $client->fresh();
    }

    public function delete(int $id): bool
    {
        $client = $this->findById($id);
        return $client->delete();
    }

    public function forceDelete(int $id): bool
    {
        $client = $this->findById($id);
        return $client->forceDelete();
    }

    public function restore(int $id): bool
    {
        $client = $this->model->withTrashed()->findOrFail($id);
        return $client->restore();
    }

    public function toggleStatus(int $id): Client
    {
        $client = $this->findById($id);
        $client->is_active = !$client->is_active;
        $client->save();
        return $client;
    }

    public function verify(int $id): Client
    {
        $client = $this->findById($id);
        $client->is_verified = true;
        $client->verified_at = now();
        $client->save();
        return $client;
    }

    public function getStatistics(): array
    {
        return [

            'total' => $this->model->count(),
            'active' => $this->model->active()->count(),
            'inactive' => $this->model->where('is_active', false)->count(),
            'verified' => $this->model->verified()->count(),
            'unverified' => $this->model->where('is_verified', false)->count(),
            'total_revenue' => $this->model->sum('annual_revenue'),
            'by_type' => $this->model->groupBy('client_type')
                ->selectRaw('client_type, count(*) as total')
                ->get()
                ->toArray(),
            'by_industry' => $this->model->whereNotNull('industry_type')
                ->groupBy('industry_type')
                ->selectRaw('industry_type, count(*) as total')
                ->limit(10)
                ->get()
                ->toArray(),
            'by_country' => $this->model->whereNotNull('country')
                ->groupBy('country')
                ->selectRaw('country, count(*) as total')
                ->limit(10)
                ->get()
                ->toArray(),
        ];
    }

    public function getActiveClients(): Collection
    {
        return $this->model->active()->latest()->get();
    }

    public function getVerifiedClients(): Collection
    {
        return $this->model->verified()->latest()->get();
    }

    public function getByType(string $type): Collection
    {
        return $this->model->where('client_type', $type)->latest()->get();
    }

    public function getByIndustry(string $industry): Collection
    {
        return $this->model->where('industry_type', $industry)->latest()->get();
    }

    public function getByCountry(string $country): Collection
    {
        return $this->model->where('country', $country)->latest()->get();
    }

    public function getClientsWithHighCreditLimit(float $minCreditLimit = 10000): Collection
    {
        return $this->model->where('credit_limit', '>=', $minCreditLimit)
            ->orderBy('credit_limit', 'desc')
            ->get();
    }

    public function search(string $searchTerm, ?int $limit = null): Collection
    {
        $query = $this->model->search($searchTerm);

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    public function bulkUpdateStatus(array $ids, bool $status): bool
    {
        return $this->model->whereIn('id', $ids)
            ->update(['is_active' => $status]) > 0;
    }

    public function bulkDelete(array $ids): bool
    {
        return $this->model->whereIn('id', $ids)->delete() > 0;
    }

    public function countByStatus(bool $isActive = true): int
    {
        return $this->model->where('is_active', $isActive)->count();
    }

    public function getRecentClients(int $limit = 5): Collection
    {
        return $this->model->latest()->limit($limit)->get();
    }

    public function getUnassignedClients(): Collection
    {
        return $this->model->whereNull('assigned_to')->latest()->get();
    }

    /**
     * Apply filters to the query builder
     *
     * @param Builder $query
     * @param array $filters
     * @return Builder
     */
    protected function applyFilters(Builder $query, array $filters): Builder
    {
        // Search filter
        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        // Status filter
        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        // Verification filter
        if (isset($filters['is_verified'])) {
            $query->where('is_verified', $filters['is_verified']);
        }

        // Client type filter
        if (!empty($filters['client_type'])) {
            $query->where('client_type', $filters['client_type']);
        }

        // Industry filter
        if (!empty($filters['industry_type'])) {
            $query->where('industry_type', $filters['industry_type']);
        }

        // Country filter
        if (!empty($filters['country'])) {
            $query->where('country', $filters['country']);
        }

        // Date range filter
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        // Assigned user filter
        if (!empty($filters['assigned_to'])) {
            $query->where('assigned_to', $filters['assigned_to']);
        }

        // Credit limit filter
        if (!empty($filters['min_credit_limit'])) {
            $query->where('credit_limit', '>=', $filters['min_credit_limit']);
        }
        if (!empty($filters['max_credit_limit'])) {
            $query->where('credit_limit', '<=', $filters['max_credit_limit']);
        }

        // Revenue range filter
        if (!empty($filters['min_revenue'])) {
            $query->where('annual_revenue', '>=', $filters['min_revenue']);
        }
        if (!empty($filters['max_revenue'])) {
            $query->where('annual_revenue', '<=', $filters['max_revenue']);
        }

        // Employee count filter
        if (!empty($filters['min_employees'])) {
            $query->where('employee_count', '>=', $filters['min_employees']);
        }
        if (!empty($filters['max_employees'])) {
            $query->where('employee_count', '<=', $filters['max_employees']);
        }

        return $query;
    }
}
