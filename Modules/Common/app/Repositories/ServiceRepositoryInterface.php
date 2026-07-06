<?php

namespace Modules\Common\Repositories;
use Illuminate\Http\UploadedFile;

interface ServiceRepositoryInterface
{
    public function paginate(int $perPage = 15, array $filters = []);
    public function findById(int $id);
    public function findBySlug(string $slug);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function restore(int $id);
    public function forceDelete(int $id);
    public function getTrashed();
    public function updateOrder(array $orders);
    public function toggleStatus(int $id);
    public function updateImage(int $id, UploadedFile $image);
    public function removeImage(int $id);
}
