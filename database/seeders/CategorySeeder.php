<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $electronics = Category::create([
            "name" => "Electronics",
            "slug" => Str::slug("Electronics"),
        ]);
        $phones = Category::create([
            "name" => "Phones",
            "slug" => Str::slug("Phones"),
            "parent_id" => $electronics->id,
        ]);
        Category::create([
            "name" => "Smartphones",
            "slug" => Str::slug("Smartphones"),
            "parent_id" => $phones->id,
        ]);
        Category::create([
            "name" => "Accessories",
            "slug" => Str::slug("Accessories"),
            "parent_id" => $phones->id,
        ]);

        $home = Category::create([
            "name" => "Home",
            "slug" => Str::slug("Home"),
        ]);
        $kitchen = Category::create([
            "name" => "Kitchen",
            "slug" => Str::slug("Kitchen"),
            "parent_id" => $home->id,
        ]);
        Category::create([
            "name" => "Small Appliances",
            "slug" => Str::slug("Small Appliances"),
            "parent_id" => $kitchen->id,
        ]);
    }
}
