<?php

namespace Modules\Common\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Common\Entities\Client;

interface ClientRepositoryInterface
{
    public function getAll(array $filters = []): Collection;
    public function getPaginated(array $filters = [], int $perPage = 10): LengthAwarePaginator;
    public function findById(int $id): ?Client;
    public function findByEmail(string $email): ?Client;
    public function findByClientCode(string $clientCode): ?Client;
    public function create(array $data): Client;
    public function update(int $id, array $data): Client;
    public function delete(int $id): bool;
    public function forceDelete(int $id): bool;
    public function restore(int $id): bool;
    public function toggleStatus(int $id): Client;
    public function verify(int $id): Client;
    public function getStatistics(): array;
    public function getActiveClients(): Collection;
    public function getVerifiedClients(): Collection;
    public function getByType(string $type): Collection;
    public function getByIndustry(string $industry): Collection;
    public function getByCountry(string $country): Collection;
    public function getClientsWithHighCreditLimit(float $minCreditLimit = 10000): Collection;
    public function search(string $searchTerm, ?int $limit = null): Collection;
    public function bulkUpdateStatus(array $ids, bool $status): bool;
    public function bulkDelete(array $ids): bool;
    public function countByStatus(bool $isActive = true): int;
    public function getRecentClients(int $limit = 5): Collection;
    public function getUnassignedClients(): Collection;
}
