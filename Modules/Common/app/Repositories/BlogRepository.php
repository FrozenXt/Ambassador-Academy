<?php

namespace Modules\Common\Repositories;

use Modules\Common\Entities\Blog;

class BlogRepository implements BlogRepositoryInterface
{
    protected $model;

    public function __construct(Blog $model)
    {
        $this->model = $model;
    }

    public function paginate(int $perPage = 15, array $filters = [])
    {
        $query = $this->model->with(['category', 'author'])->latest();

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title',   'like', '%' . $filters['search'] . '%')
                    ->orWhere('excerpt', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (isset($filters['is_featured']) && $filters['is_featured'] !== '') {
            $query->where('is_featured', $filters['is_featured']);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function findById(int $id)
    {
        return $this->model->with(['category', 'author'])->withTrashed()->findOrFail($id);
    }

    public function findBySlug(string $slug)
    {
        return $this->model->with(['category', 'author'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $blog = $this->findById($id);
        $blog->update($data);
        return $blog;
    }

    public function delete(int $id)
    {
        $blog = $this->findById($id);
        $blog->delete();
        return true;
    }

    public function restore(int $id)
    {
        $blog = $this->model->withTrashed()->findOrFail($id);
        $blog->restore();
        return true;
    }

    public function forceDelete(int $id)
    {
        $blog = $this->model->withTrashed()->findOrFail($id);
        $blog->forceDelete();
        return true;
    }

    public function getTrashed()
    {
        return $this->model->onlyTrashed()->latest()->get();
    }

    public function toggleStatus(int $id)
    {
        $blog = $this->findById($id);
        $blog->update([
            'status'       => $blog->status === 'published' ? 'draft' : 'published',
            'published_at' => $blog->status === 'draft' ? now() : $blog->published_at,
        ]);
        return $blog;
    }

    public function toggleFeatured(int $id)
    {
        $blog = $this->findById($id);
        $blog->update(['is_featured' => !$blog->is_featured]);
        return $blog;
    }

    public function getPublished(int $limit = 10)
    {
        return $this->model->with(['category', 'author'])
            ->published()
            ->latest('published_at')
            ->take($limit)
            ->get();
    }

    public function getFeatured(int $limit = 3)
    {
        return $this->model->with(['category', 'author'])
            ->published()
            ->featured()
            ->latest('published_at')
            ->take($limit)
            ->get();
    }

    public function getRelated(int $blogId, int $categoryId, int $limit = 3)
    {
        return $this->model->with(['category', 'author'])
            ->published()
            ->where('category_id', $categoryId)
            ->where('id', '!=', $blogId)
            ->latest('published_at')
            ->take($limit)
            ->get();
    }

    public function incrementViews(int $id)
    {
        $this->model->where('id', $id)->increment('views');
    }

    public function getRecent($limit)
    {
        return Blog::with(['category'])
            ->published()
            ->latest('published_at')
            ->take($limit)
            ->get();
    }

    public function getLatestPublished()
    {
        return Blog::where('status', 'published')
            ->latest('published_at')
            ->first();
    }

    public function queryPublished(array $filters = [])
    {
        $query = Blog::with(['author', 'category'])
            ->where('status', 'published');

        // Search filter
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('content', 'like', '%' . $filters['search'] . '%');
            });
        }

        // Category filter
        if (!empty($filters['category'])) {
            $query->whereHas('category', function ($q) use ($filters) {
                $q->where('slug', $filters['category']);
            });
        }

        return $query;
    }
}
