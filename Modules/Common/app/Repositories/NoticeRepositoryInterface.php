<?php

namespace Modules\Common\Repositories;

interface NoticeRepositoryInterface
{
    public function all();
    public function paginate($perPage = 15);
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function findBySlug($slug);
    public function toggleStatus($id);
    public function toggleFeatured($id);
    public function search($keyword);
    public function getStatistics();
}
