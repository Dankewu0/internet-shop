<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::whereNotNull("parent_id")->first();

        if (!$category) {
            return;
        }

        Product::create([
            "id" => 1,
            "name" => "Смартфон iPhone 15",
            "description" => "Новейшая модель смартфона от Apple.",
            "slug" => Str::slug("Смартфон iPhone 15"),
            "category_id" => $category->id,
            "price" => 99990.0,
            "attributes" => [
                "weight" => "171g",
                "width" => "71.6mm",
                "length" => "147.6mm",
            ],
        ]);

        Product::create([
            "id" => 2,
            "name" => "Беспроводная зарядка",
            "description" => "Быстрая зарядка для ваших устройств.",
            "slug" => Str::slug("Беспроводная зарядка"),
            "category_id" => $category->id,
            "price" => 2500.0,
            "attributes" => [
                "weight" => "50g",
                "power" => "15W",
            ],
        ]);
    }
}
