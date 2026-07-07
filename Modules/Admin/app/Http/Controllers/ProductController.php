<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Common\Services\ProductService;
use Modules\Common\Services\CategoryService;
use Modules\Admin\Http\Requests\ProductRequest;
use Modules\Common\Entities\Product;

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
            $request->only(['name', 'description', 'price', 'stock', 'status', 'url', 'features']),
            $request->file('image'),
            $request->input('category_ids', [])
        );

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        return view('admin::products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = $this->categoryService->getAllActive();
        return view('admin::products.edit', compact('product', 'categories'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $this->productService->updateProduct(
            $product->id,
            $request->only(['name', 'description', 'price', 'stock', 'status', 'url', 'features']),
            $request->file('image'),
            $request->input('category_ids', [])
        );

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $this->productService->deleteProduct($product->id);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function updateOrder(Request $request)
    {
        foreach ($request->orders as $order) {
            Product::where('id', $order['id'])
                ->update(['sort_order' => $order['sort_order']]);
        }

        return response()->json(['success' => true]);
    }
}
