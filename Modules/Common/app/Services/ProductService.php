<?php

namespace Modules\Common\Services;

use Modules\Common\Repositories\ProductRepositoryInterface;
use Modules\Common\Entities\Product;
use Illuminate\Support\Facades\Storage;
use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getPaginatedProducts(array $filters = [])
    {
        return $this->productRepository->paginate(15, $filters);
    }

    public function getProductById(int $id)
    {
        return $this->productRepository->findById($id);
    }

    public function getAllProducts()
    {
        return $this->productRepository->getAll();
    }

    public function createProduct(array $data, $imageFile = null, array $categoryIds = [])
    {
        // Filter empty feature lines
        if (isset($data['features']) && is_array($data['features'])) {
            $data['features'] = array_values(
                array_filter($data['features'], fn($f) => trim($f) !== '')
            );
            if (empty($data['features'])) {
                $data['features'] = null;
            }
        }

        if ($imageFile) {
            $data['image'] = $imageFile->store('products', 'public');
        }

        $product = $this->productRepository->create($data);

        if (!empty($categoryIds)) {
            $product->categories()->attach($categoryIds);
        }

        return $product;
    }

    public function updateProduct(int $id, array $data, $imageFile = null, array $categoryIds = [])
    {
        $product = $this->productRepository->findById($id);

        // Filter empty feature lines
        if (isset($data['features']) && is_array($data['features'])) {
            $data['features'] = array_values(
                array_filter($data['features'], fn($f) => trim($f) !== '')
            );
            if (empty($data['features'])) {
                $data['features'] = null;
            }
        }

        if ($imageFile) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $imageFile->store('products', 'public');
        }

        $updatedProduct = $this->productRepository->update($id, $data);

        // sync() replaces old category set with the new one
        $product->categories()->sync($categoryIds);

        return $updatedProduct;
    }

    public function deleteProduct(int $id)
    {
        $product = $this->productRepository->findById($id);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }


        $product->categories()->detach();

        return $this->productRepository->delete($id);
    }

    public function getTotalProducts()
    {
        return Product::count();
    }

    public function getActiveProducts()
    {
        return Product::where('status', 'active')->count();
    }

    public function getLowStockProducts(int $threshold = 5)
    {
        return Product::where('stock', '<=', $threshold)
            ->where('status', 'active')
            ->with('categories')
            ->get();
    }
    public function importProducts($file): array
    {
        $import = new ProductsImport();
        Excel::import($import, $file);

        return [
            'imported'       => $import->getRowCount(),
            'failures'       => $import->failures()->count(),
            'missing_images' => $import->getMissingImages(),
        ];
    }
    public function getPendingImportImages()
    {
        return collect(Storage::disk('public')->files('imports/temp'))
            ->map(fn($path) => basename($path));
    }

    public function uploadImportImages(array $files): int
    {
        foreach ($files as $file) {
            $filename = $file->getClientOriginalName();
            $file->storeAs('imports/temp', $filename, 'public');
        }

        return count($files);
    }

    public function deleteImportImage(string $filename): bool
    {
        return Storage::disk('public')->delete('imports/temp/' . $filename);
    }
    public function deleteMultipleProducts(array $ids): int
    {
        $count = 0;
        foreach ($ids as $id) {
            $this->deleteProduct($id);
            $count++;
        }
        return $count;
    }
}
