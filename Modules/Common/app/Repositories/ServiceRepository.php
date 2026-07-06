<?php


namespace Modules\Common\Repositories;

use Modules\Common\Entities\Service;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ServiceRepository implements ServiceRepositoryInterface
{
    protected $model;

    public function __construct(Service $model)
    {
        $this->model = $model;
    }

    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->query();

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('description', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('content', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function findById($id): ?Service
    {
        return $this->model->find($id);
    }

    public function findBySlug(string $slug): ?Service
    {
        return $this->model->where('slug', $slug)->first();
    }

    public function create(array $data): Service
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $service = $this->model->find($id);
        if (!$service) {
            return false;
        }
        return $service->update($data);
    }

    public function delete(int $id): bool
    {
        $service = $this->model->find($id);
        if (!$service) {
            return false;
        }
        return $service->delete();
    }

    public function restore(int $id): bool
    {
        $service = $this->model->onlyTrashed()->find($id);
        if (!$service) {
            return false;
        }
        return $service->restore();
    }

    public function forceDelete(int $id): bool
    {
        $service = $this->model->onlyTrashed()->find($id);
        if (!$service) {
            return false;
        }

        // Delete image if exists
        if ($service->image && Storage::disk('public')->exists($service->image)) {
            Storage::disk('public')->delete($service->image);
        }

        return $service->forceDelete();
    }

    public function getTrashed(): LengthAwarePaginator
    {
        return $this->model->onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->paginate(15);
    }

    public function updateOrder(array $orders): bool
    {
        foreach ($orders as $order) {
            $this->model->where('id', $order['id'])
                ->update(['order' => $order['order']]);
        }
        return true;
    }

    public function toggleStatus(int $id): bool
    {
        $service = $this->model->find($id);
        if (!$service) {
            return false;
        }

        $service->status = $service->status === 'active' ? 'inactive' : 'active';
        return $service->save();
    }

    public function updateImage(int $id, UploadedFile $image): ?Service
    {
        $service = $this->model->find($id);
        if (!$service) {
            return null;
        }

        // Delete old image if exists
        if ($service->image && Storage::disk('public')->exists($service->image)) {
            Storage::disk('public')->delete($service->image);
        }

        // Store new image
        $path = $image->store('services', 'public');
        $service->image = $path;
        $service->save();

        return $service;
    }

    // for removing image
    public function removeImage(int $id): bool
    {
        $service = $this->model->find($id);
        if (!$service) {
            return false;
        }

        // Delete image file
        if ($service->image && Storage::disk('public')->exists($service->image)) {
            Storage::disk('public')->delete($service->image);
        }

        // Remove image reference from database
        $service->image = null;
        return $service->save();
    }
}
