<?php

namespace Modules\Common\Services;

use Modules\Common\Repositories\CategoryRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getPaginatedCategories(array $filters = [])
    {
        return $this->categoryRepository->paginate(15, $filters);
    }

    public function getCategoryById(int $id)
    {
        return $this->categoryRepository->findById($id);
    }

    public function createCategory(array $data, $imageFile = null)
    {
        // Handle image
        if ($imageFile) {
            $data['image'] = $imageFile->store('categories', 'public');
        }

        // Generate slug from name
        if (isset($data['name'])) {
            $slug = Str::slug($data['name']);

            // Check for duplicates
            $count = $this->categoryRepository->countBySlugLike($slug);

            $data['slug'] = $count ? $slug . '-' . ($count + 1) : $slug;
        }

        return $this->categoryRepository->create($data);
    }

    public function updateCategory(int $id, array $data, $imageFile = null)
    {
        $category = $this->categoryRepository->findById($id);

        if ($imageFile) {
            // Delete old image
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $imageFile->store('categories', 'public');
        }

        // Update slug if name changed
        if (isset($data['name']) && $data['name'] !== $category->name) {
            $slug = Str::slug($data['name']);
            $count = $this->categoryRepository->countBySlugLikeExceptId($slug, $id);
            $data['slug'] = $count ? $slug . '-' . ($count + 1) : $slug;
        }

        return $this->categoryRepository->update($id, $data);
    }

    public function deleteCategory(int $id)
    {
        $category = $this->categoryRepository->findById($id);

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        return $this->categoryRepository->delete($id);
    }

    public function getAllActive()
    {
        return \Modules\Common\Entities\Category::where('status', 'active')
            ->orderBy('name')
            ->get();
    }
}
