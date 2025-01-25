<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Ensure there are categories to associate products with
        if (Category::count() === 0) {
            $this->call(CategorySeeder::class); // Run the CategorySeeder if no categories exist
        }

        // Fetch all categories
        $categories = Category::all();

        // Generate products for each category
        foreach ($categories as $category) {
            Product::factory()
                ->count(10) // Create 10 products per category
                ->create([
                    'category_id' => $category->id, // Associate products with this category
                ]);
        }
    }
}
