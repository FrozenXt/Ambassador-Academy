<?php

namespace Modules\Common\Services;

use Modules\Common\Repositories\PageRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Modules\Common\Entities\Page;
use Illuminate\Support\Str;

class PageService
{
    protected $repository;

    public function __construct(PageRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getPaginatedPages(array $filters = [])
    {
        return $this->repository->paginate(15, $filters);
    }

    public function getPageById(int $id)
    {
        return $this->repository->findById($id);
    }

    public function getPageBySlug(string $slug)
    {
        return $this->repository->findBySlug($slug);
    }

    public function getTrashedPages()
    {
        return $this->repository->getTrashed();
    }

    public function createPage(array $data, $imageFile = null)
    {
        // Auto generate slug
        if (empty($data['slug'])) {
            $data['slug'] = Page::generateSlug($data['title']);
        }

        // Handle image
        if ($imageFile) {
            $data['featured_image'] = $imageFile->store('pages', 'public');
        }

        // Set published_at
        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        // Set creator
        $data['created_by'] = session('admin_id');

        return $this->repository->create($data);
    }

    public function updatePage(int $id, array $data, $imageFile = null)
    {
        $page = $this->repository->findById($id);

        // Auto generate slug if changed
        if (empty($data['slug'])) {
            $data['slug'] = Page::generateSlug($data['title'], $id);
        }

        // Handle image
        if ($imageFile) {
            // Delete old image if exists
            if ($page->featured_image && Storage::disk('public')->exists($page->featured_image)) {
                Storage::disk('public')->delete($page->featured_image);
            }
            $data['featured_image'] = $imageFile->store('pages', 'public');
        }

        // Set published_at
        if ($data['status'] === 'published' && !$page->published_at) {
            $data['published_at'] = now();
        }

        return $this->repository->update($id, $data);
    }

    public function deletePage(int $id)
    {
        return $this->repository->delete($id);
    }

    public function restorePage(int $id)
    {
        return $this->repository->restore($id);
    }

    public function forceDeletePage(int $id)
    {
        $page = $this->repository->findById($id);
        if ($page->featured_image && Storage::disk('public')->exists($page->featured_image)) {
            Storage::disk('public')->delete($page->featured_image);
        }
        return $this->repository->forceDelete($id);
    }

    /**
     * Remove featured image from a page
     * 
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function removeImage(int $id)
    {
        try {
            $page = $this->repository->findById($id);

            if (!$page) {
                throw new \Exception('Page not found.');
            }

            // Check if page has a featured image
            if (!$page->featured_image) {
                throw new \Exception('This page does not have a featured image.');
            }

            // Delete the image file from storage
            if (Storage::disk('public')->exists($page->featured_image)) {
                Storage::disk('public')->delete($page->featured_image);
            }

            // Update the page record to remove the image reference
            $updated = $this->repository->update($id, ['featured_image' => null]);

            if (!$updated) {
                throw new \Exception('Failed to update page record.');
            }

            return true;
        } catch (\Exception $e) {
            throw new \Exception('Error removing image: ' . $e->getMessage());
        }
    }
}
