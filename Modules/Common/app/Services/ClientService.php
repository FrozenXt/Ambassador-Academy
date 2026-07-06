<?php


namespace Modules\Common\Services;

use Modules\Common\Repositories\ClientRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Common\Entities\Client;

class ClientService
{
    protected $clientRepository;

    public function __construct(ClientRepositoryInterface $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function getAllClients(array $filters = []): Collection
    {
        return $this->clientRepository->getAll($filters);
    }

    public function getPaginatedClients(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        return $this->clientRepository->getPaginated($filters, $perPage);
    }

    public function getClientById(int $id): ?Client
    {
        return $this->clientRepository->findById($id);
    }

    public function getClientByEmail(string $email): ?Client
    {
        return $this->clientRepository->findByEmail($email);
    }

    public function getClientByCode(string $clientCode): ?Client
    {
        return $this->clientRepository->findByClientCode($clientCode);
    }

    public function createClient(array $data): Client
    {
        // Add any business logic before creation
        return $this->clientRepository->create($data);
    }

    public function updateClient(int $id, array $data): Client
    {

        return $this->clientRepository->update($id, $data);
    }

    public function deleteClient(int $id): bool
    {
        // Add any business logic before deletion (e.g., check if can delete)
        return $this->clientRepository->delete($id);
    }

    public function forceDeleteClient(int $id): bool
    {
        return $this->clientRepository->forceDelete($id);
    }

    public function restoreClient(int $id): bool
    {
        return $this->clientRepository->restore($id);
    }

    public function toggleClientStatus(int $id): Client
    {
        return $this->clientRepository->toggleStatus($id);
    }

    public function verifyClient(int $id): Client
    {
        return $this->clientRepository->verify($id);
    }

    public function getStatistics(): array
    {
        return $this->clientRepository->getStatistics();
    }

    public function getActiveClients(): Collection
    {
        return $this->clientRepository->getActiveClients();
    }

    public function getVerifiedClients(): Collection
    {
        return $this->clientRepository->getVerifiedClients();
    }

    public function getClientsByType(string $type): Collection
    {
        return $this->clientRepository->getByType($type);
    }

    public function getClientsByIndustry(string $industry): Collection
    {
        return $this->clientRepository->getByIndustry($industry);
    }

    public function getClientsByCountry(string $country): Collection
    {
        return $this->clientRepository->getByCountry($country);
    }

    public function getClientsWithHighCreditLimit(float $minCreditLimit = 10000): Collection
    {
        return $this->clientRepository->getClientsWithHighCreditLimit($minCreditLimit);
    }

    public function searchClients(string $searchTerm, ?int $limit = null): Collection
    {
        return $this->clientRepository->search($searchTerm, $limit);
    }

    public function bulkUpdateStatus(array $ids, bool $status): bool
    {
        return $this->clientRepository->bulkUpdateStatus($ids, $status);
    }

    public function bulkDeleteClients(array $ids): bool
    {
        return $this->clientRepository->bulkDelete($ids);
    }

    public function countClientsByStatus(bool $isActive = true): int
    {
        return $this->clientRepository->countByStatus($isActive);
    }

    public function getRecentClients(int $limit = 5): Collection
    {
        return $this->clientRepository->getRecentClients($limit);
    }

    public function getUnassignedClients(): Collection
    {
        return $this->clientRepository->getUnassignedClients();
    }
}
