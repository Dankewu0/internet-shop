<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Validation\ValidationException;

class CategoryService
{
    public function getTree()
    {
        return Category::query()
            ->with(["children.children"])
            ->whereNull("parent_id")
            ->get();
    }

    public function createCategory(array $data): Category
    {
        $this->guardDepth($data["parent_id"] ?? null);

        return Category::query()->create($data);
    }

    public function updateCategory(Category $category, array $data): Category
    {
        if (array_key_exists("parent_id", $data)) {
            $this->guardDepth($data["parent_id"]);
        }

        $category->update($data);

        return $category->refresh();
    }

    public function deleteCategory(Category $category): void
    {
        $category->delete();
    }

    private function guardDepth(?int $parentId): void
    {
        if ($parentId === null) {
            return;
        }

        $parent = Category::query()->findOrFail($parentId);
        $level = $parent->level() + 1;

        if ($level > 3) {
            throw ValidationException::withMessages([
                "parent_id" => "Category nesting level cannot be greater than 3.",
            ]);
        }
    }
}
