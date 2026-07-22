<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Common\Services\ProductService;
use Modules\Common\Services\CategoryService;
use Modules\Admin\Http\Requests\ProductRequest;
use Modules\Common\Entities\Product;
use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    protected $productService;
    protected $categoryService;

    public function __construct(
        ProductService $productService,
        CategoryService $categoryService
    ) {
        $this->productService  = $productService;
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        $filters  = $request->only([
            'search',
            'category_id',
            'status',
            'min_price',
            'max_price'
        ]);
        $products   = $this->productService->getPaginatedProducts($filters);
        $categories = $this->categoryService->getAllActive();

        return view('admin::products.index', compact('products', 'categories', 'filters'));
    }

    public function create()
    {
        $categories = $this->categoryService->getAllActive();
        return view('admin::products.create', compact('categories'));
    }

    public function store(ProductRequest $request)
    {
        $this->productService->createProduct(
            $request->only(['name', 'subtitle', 'description', 'price', 'stock', 'status', 'url', 'features', 'base', 'style', 'served']),
            $request->file('image'),
            $request->input('category_ids', [])
        );

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function update(ProductRequest $request, Product $product)
    {
        $this->productService->updateProduct(
            $product->id,
            $request->only(['name', 'subtitle', 'description', 'price', 'stock', 'status', 'url', 'features', 'base', 'style', 'served']),
            $request->file('image'),
            $request->input('category_ids', [])
        );

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function importForm()
    {
        $existingImages = $this->productService->getPendingImportImages();

        return view('admin::products.import', compact('existingImages'));
    }

    public function bulkImagesUpload(Request $request)
    {
        $request->validate([
            'images' => 'required|array|min:1',
            'images.*' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        $uploaded = $this->productService->uploadImportImages($request->file('images'));

        return redirect()
            ->route('admin.products.import')
            ->with('success', $uploaded . ' image(s) uploaded successfully.');
    }

    public function bulkImageDelete(Request $request)
    {
        $request->validate([
            'filename' => 'required|string',
        ]);

        $this->productService->deleteImportImage($request->filename);

        return redirect()
            ->route('admin.products.import')
            ->with('success', 'Image removed successfully.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        $result = $this->productService->importProducts($request->file('file'));

        $message = $result['imported'] . ' products imported successfully.';

        if (!empty($result['missing_images'])) {
            $message .= ' Missing images: ' . implode(', ', $result['missing_images']);
        }

        if ($result['failures'] > 0) {
            $message .= ' (' . $result['failures'] . ' row(s) skipped due to errors.)';
        }

        return redirect()
            ->route('admin.products.index')
            ->with('warning', $message);
    }


    public function destroy(Product $product)
    {
        $this->productService->deleteProduct($product->id);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'integer|exists:products,id']);

        $count = $this->productService->deleteMultipleProducts($request->ids);

        return redirect()->route('admin.products.index')
            ->with('success', $count . ' product(s) deleted successfully.');
    }

    public function edit(Product $product)
    {
        $categories = $this->categoryService->getAllActive();
        return view('admin::products.edit', compact('product', 'categories'));
    }
}
