<?php


namespace Modules\Common\Services;

use Modules\Common\Repositories\PricingRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Modules\Common\Entities\Pricing;

class PricingService
{
    protected $pricingRepository;

    public function __construct(PricingRepositoryInterface $pricingRepository)
    {
        $this->pricingRepository = $pricingRepository;
    }

    public function getAllPricings(array $filters = []): Collection
    {
        return $this->pricingRepository->getAll($filters);
    }

    public function getPaginatedPricings(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        return $this->pricingRepository->getPaginated($filters, $perPage);
    }

    public function getPricingById(int $id): ?Pricing
    {
        return $this->pricingRepository->findById($id);
    }

    public function createPricing(array $data): Pricing
    {
        return $this->pricingRepository->create($data);
    }

    public function updatePricing(int $id, array $data): Pricing
    {
        return $this->pricingRepository->update($id, $data);
    }

    public function deletePricing(int $id): bool
    {
        return $this->pricingRepository->delete($id);
    }

    public function getActivePricings(): Collection
    {
        return $this->pricingRepository->getActivePricings();
    }

    public function togglePricingStatus(int $id): Pricing
    {
        return $this->pricingRepository->toggleStatus($id);
    }

    public function updateSortOrder(array $orders): bool
    {
        return $this->pricingRepository->updateSortOrder($orders);
    }
}
