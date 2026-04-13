<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class ProductService
{
    public function getProducts(array $filters = [], int $perPage = 10)
    {
        $query = Product::query()->with("category");
        $categoryIds = $this->resolveCategoryFilterIds($filters);

        if ($categoryIds->isNotEmpty()) {
            $query->whereIn("category_id", $categoryIds->all());
        }

        if (isset($filters["min_price"])) {
            $query->where("price", ">=", $filters["min_price"]);
        }

        if (isset($filters["max_price"])) {
            $query->where("price", "<=", $filters["max_price"]);
        }

        return $query->latest()->paginate($perPage);
    }

    public function getBySlug(string $slug): Product
    {
        return Product::query()
            ->with("category")
            ->where("slug", $slug)
            ->firstOrFail();
    }

    public function createProduct(array $data): Product
    {
        $this->guardCategoryLevel((int) $data["category_id"]);

        return Product::query()->create($data);
    }

    public function updateProduct(Product $product, array $data): Product
    {
        if (array_key_exists("category_id", $data)) {
            $this->guardCategoryLevel((int) $data["category_id"]);
        }

        $product->update($data);

        return $product->refresh()->load("category");
    }

    public function deleteProduct(Product $product): void
    {
        $product->delete();
    }

    private function guardCategoryLevel(int $categoryId): void
    {
        $category = Category::query()->findOrFail($categoryId);
        $level = $category->level();

        if (!in_array($level, [2, 3], true)) {
            throw ValidationException::withMessages([
                "category_id" => "Product must belong to category level 2 or 3.",
            ]);
        }
    }

    private function resolveCategoryFilterIds(array $filters): Collection
    {
        $rawIds = [];

        if (!empty($filters["category_id"])) {
            $rawIds[] = (int) $filters["category_id"];
        }

        if (!empty($filters["category_ids"]) && is_array($filters["category_ids"])) {
            foreach ($filters["category_ids"] as $id) {
                $rawIds[] = (int) $id;
            }
        }

        $ids = collect($rawIds)->filter()->unique()->values();

        if ($ids->isEmpty()) {
            return collect();
        }

        $all = Category::query()->select(["id", "parent_id"])->get();
        $descendants = collect();

        foreach ($ids as $id) {
            $descendants = $descendants->merge($this->collectDescendants($all, $id));
        }

        return $ids->merge($descendants)->unique()->values();
    }

    private function collectDescendants(Collection $all, int $parentId): Collection
    {
        $children = $all->where("parent_id", $parentId)->pluck("id")->values();

        if ($children->isEmpty()) {
            return collect();
        }

        $nested = collect();

        foreach ($children as $childId) {
            $nested = $nested->merge($this->collectDescendants($all, (int) $childId));
        }

        return $children->merge($nested);
    }
}
