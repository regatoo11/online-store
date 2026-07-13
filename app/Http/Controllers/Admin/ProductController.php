<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService
    ) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Product::class);

        $products = $this->productService->getPaginated($request->only('search', 'category_id', 'is_active'));

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $this->authorize('create', Product::class);

        $categories = Category::where('is_active', true)->get();
        $attributes = Attribute::with('values')->get();

        return view('admin.products._form', [
            'product' => null,
            'categories' => $categories,
            'attributes' => $attributes,
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        $this->authorize('create', Product::class);

        $product = $this->productService->create($request->validated());

        if ($request->has('variants')) {
            $this->productService->syncVariants($product, $request->validated()['variants']);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);

        $product->load('variants.attributeValues');
        $categories = Category::where('is_active', true)->get();
        $attributes = Attribute::with('values')->get();

        return view('admin.products._form', compact('product', 'categories', 'attributes'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->authorize('update', $product);

        $this->productService->update($product, $request->validated());

        if ($request->has('variants')) {
            $this->productService->syncVariants($product, $request->validated()['variants']);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
