<?php

namespace Modules\Common\Services;

use Modules\Common\Repositories\BlogRepositoryInterface;
use Modules\Common\Entities\Blog;
use Illuminate\Support\Facades\Storage;

class BlogService
{
    protected $repository;

    public function __construct(BlogRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getPaginated(array $filters = [])
    {
        return $this->repository->paginate(15, $filters);
    }

    public function findById(int $id)
    {
        return $this->repository->findById($id);
    }

    public function findBySlug(string $slug)
    {
        $blog = $this->repository->findBySlug($slug);
        $this->repository->incrementViews($blog->id);
        return $blog;
    }

    public function create(array $data, $imageFile = null)
    {
        if (empty($data['slug'])) {
            $data['slug'] = Blog::generateSlug($data['title']);
        }

        if ($imageFile) {
            $data['featured_image'] = $imageFile->store('blogs', 'public');
        }

        if (!empty($data['tags']) && is_string($data['tags'])) {
            $data['tags'] = array_map('trim', explode(',', $data['tags']));
        }

        $data['is_featured']    = !empty($data['is_featured']);
        $data['allow_comments'] = !empty($data['allow_comments']);
        $data['author_id']      = session('admin_id');

        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        return $this->repository->create($data);
    }

    public function update(int $id, array $data, $imageFile = null)
    {
        $blog = $this->repository->findById($id);

        if (empty($data['slug'])) {
            $data['slug'] = Blog::generateSlug($data['title'], $id);
        }

        if ($imageFile) {
            if ($blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
            }
            $data['featured_image'] = $imageFile->store('blogs', 'public');
        }

        if (!empty($data['tags']) && is_string($data['tags'])) {
            $data['tags'] = array_map('trim', explode(',', $data['tags']));
        }

        $data['is_featured']    = !empty($data['is_featured']);
        $data['allow_comments'] = !empty($data['allow_comments']);

        if ($data['status'] === 'published' && !$blog->published_at) {
            $data['published_at'] = now();
        }

        return $this->repository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->repository->delete($id);
    }

    public function restore(int $id)
    {
        return $this->repository->restore($id);
    }

    public function forceDelete(int $id)
    {
        $blog = $this->repository->findById($id);
        if ($blog->featured_image) {
            Storage::disk('public')->delete($blog->featured_image);
        }
        return $this->repository->forceDelete($id);
    }

    public function getTrashed()
    {
        return $this->repository->getTrashed();
    }

    public function toggleStatus(int $id)
    {
        return $this->repository->toggleStatus($id);
    }

    public function toggleFeatured(int $id)
    {
        return $this->repository->toggleFeatured($id);
    }

    public function getPublished(int $limit = 10)
    {
        return $this->repository->getPublished($limit);
    }

    public function getFeatured(int $limit = 3)
    {
        return $this->repository->getFeatured($limit);
    }

    public function getRelated(int $blogId, int $categoryId, int $limit = 3)
    {
        return $this->repository->getRelated($blogId, $categoryId, $limit);
    }

    public function removeImage(int $id)
    {
        $blog = $this->repository->findById($id);
        if ($blog->featured_image) {
            Storage::disk('public')->delete($blog->featured_image);
        }
        return $this->repository->update($id, ['featured_image' => null]);
    }
    public function getRecentBlogs($limit)
    {
        return $this->repository->getRecent($limit);
    }

    public function getLatestPublished()
    {
        return $this->repository->getLatestPublished();
    }
    public function updateOrder(int $id, int $order): void
    {
        Blog::where('id', $id)->update(['order' => $order]);
    }
    public function getBlogListing(int $perPage = 6, array $filters = [])
    {
        return $this->repository->queryPublished($filters)
            ->latest('published_at')
            ->paginate($perPage);
    }
}
