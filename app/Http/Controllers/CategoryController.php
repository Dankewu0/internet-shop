<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function __construct(private readonly CategoryService $service) {}

    public function index()
    {
        return response()->json($this->service->getTree());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            "name" => ["required", "string", "max:255"],
            "slug" => ["nullable", "string", "max:255", "unique:categories,slug"],
            "parent_id" => ["nullable", "integer", "exists:categories,id"],
        ]);

        return response()->json($this->service->createCategory($data), 201);
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            "name" => ["sometimes", "string", "max:255"],
            "slug" => [
                "sometimes",
                "nullable",
                "string",
                "max:255",
                Rule::unique("categories", "slug")->ignore($category->id),
            ],
            "parent_id" => ["sometimes", "nullable", "integer", "exists:categories,id"],
        ]);

        return response()->json($this->service->updateCategory($category, $data));
    }

    public function destroy(Category $category)
    {
        $this->service->deleteCategory($category);

        return response()->json(null, 204);
    }
}
