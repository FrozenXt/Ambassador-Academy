<?php

namespace Modules\Common\Repositories;

use \Modules\Common\Entities\Product;

class ProductRepository implements ProductRepositoryInterface
{
    protected $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function getAll(array $filters = [])
    {
        $query = Product::with('categories');

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['category_id'])) {
            $query->whereHas('categories', function ($q) use ($filters) {
                $q->where('categories.id', $filters['category_id']);
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        $query->orderBy('sort_order', 'asc');

        return $query->get();
    }

    public function findById(int $id)
    {
        return $this->model->with('categories')->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $product = $this->findById($id);
        $product->update($data);
        return $product;
    }

    public function delete(int $id)
    {
        $product = $this->findById($id);
        $product->delete();
        return true;
    }

    public function paginate(int $perPage = 15, array $filters = [])
    {
        $query = $this->model->with('categories');

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['category_id'])) {
            $query->whereHas('categories', function ($q) use ($filters) {
                $q->where('categories.id', $filters['category_id']);
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        return $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();
    }

    public function updateOrder(array $orders)
    {
        foreach ($orders as $order) {
            Product::where('id', $order['id'])->update([
                'sort_order' => $order['sort_order']
            ]);
        }
        return true;
    }
}
