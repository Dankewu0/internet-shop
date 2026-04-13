<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $electronics = Category::create(['name' => 'Electronics']);
        $phones = Category::create(['name' => 'Phones', 'parent_id' => $electronics->id]);
        Category::create(['name' => 'Smartphones', 'parent_id' => $phones->id]);
        Category::create(['name' => 'Accessories', 'parent_id' => $phones->id]);

        $home = Category::create(['name' => 'Home']);
        $kitchen = Category::create(['name' => 'Kitchen', 'parent_id' => $home->id]);
        Category::create(['name' => 'Small Appliances', 'parent_id' => $kitchen->id]);
    }
}
