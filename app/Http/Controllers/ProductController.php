<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function __construct(private readonly ProductService $service) {}

    public function index(Request $request)
    {
        $filters = $request->validate([
            "category_id" => ["nullable", "integer", "exists:categories,id"],
            "category_ids" => ["nullable", "array"],
            "category_ids.*" => ["integer", "exists:categories,id"],
            "min_price" => ["nullable", "numeric", "min:0"],
            "max_price" => ["nullable", "numeric", "min:0", "gte:min_price"],
            "per_page" => ["nullable", "integer", "min:1", "max:100"],
        ]);

        $perPage = (int) ($filters["per_page"] ?? 10);

        return response()->json($this->service->getProducts($filters, $perPage));
    }

    public function show(string $slug)
    {
        return response()->json($this->service->getBySlug($slug));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            "name" => ["required", "string", "max:255"],
            "description" => ["nullable", "string"],
            "slug" => ["nullable", "string", "max:255", "unique:products,slug"],
            "category_id" => ["required", "integer", "exists:categories,id"],
            "price" => ["required", "numeric", "min:0"],
            "attributes" => ["nullable", "array"],
        ]);

        return response()->json($this->service->createProduct($data), 201);
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            "name" => ["sometimes", "string", "max:255"],
            "description" => ["sometimes", "nullable", "string"],
            "slug" => [
                "sometimes",
                "nullable",
                "string",
                "max:255",
                Rule::unique("products", "slug")->ignore($product->id),
            ],
            "category_id" => ["sometimes", "integer", "exists:categories,id"],
            "price" => ["sometimes", "numeric", "min:0"],
            "attributes" => ["sometimes", "nullable", "array"],
        ]);

        return response()->json($this->service->updateProduct($product, $data));
    }

    public function destroy(Product $product)
    {
        $this->service->deleteProduct($product);

        return response()->json(null, 204);
    }
}
