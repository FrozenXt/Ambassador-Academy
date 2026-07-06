<?php
// service service interface

namespace Modules\Common\Services;
use Illuminate\Http\UploadedFile;

interface ServiceServiceInterface
{
    public function getPaginatedServices(array $filters = []);
    public function getServiceById(int $id);
    public function getServiceBySlug(string $slug);
    public function createService(array $data, $imageFile = null);
    public function updateService(int $id, array $data, $imageFile = null);
    public function deleteService(int $id);
    public function restoreService(int $id);
    public function forceDeleteService(int $id);
    public function getTrashedServices();
    public function updateServiceOrder(array $orders);
    public function toggleServiceStatus(int $id);
    public function removeImage(int $id);
    public function updateServiceImage(int $id, UploadedFile $image);
}
