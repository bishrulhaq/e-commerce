<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Sale;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Ensure categories exist before seeding products
        if (Category::count() === 0) {
            $this->call(CategorySeeder::class);
        }

        // Fetch all categories
        $categories = Category::all();

        foreach ($categories as $category) {
            // Generate 10 products per category
            for ($i = 0; $i < 10; $i++) {
                $name = $this->generateProductName();
                $slug = $this->generateUniqueSlug($name);

                $product = Product::create([
                    'category_id' => $category->id,
                    'name' => $name,
                    'slug' => $slug,
                    'description' => fake()->sentence(10),
                    'cost_price' => rand(50, 200),
                    'regular_price' => rand(250, 500),
                    'stock' => rand(10, 100),
                ]);

                // Randomly assign sales to some products
                if (rand(0, 1)) {
                    Sale::create([
                        'saleable_id' => $product->id,
                        'saleable_type' => Product::class,
                        'title' => 'Limited Time Sale',
                        'discount_type' => 'percentage',
                        'discount_value' => rand(10, 30),
                        'start_date' => now(),
                        'end_date' => now()->addDays(rand(5, 20)),
                    ]);
                }
            }
        }
    }

    /**
     * Generate a random product name.
     */
    private function generateProductName(): string
    {
        $products = [
            'Wireless Headphones', 'Gaming Laptop', 'Smartphone', 'LED Monitor',
            'Bluetooth Speaker', 'Mechanical Keyboard', 'Gaming Mouse', 'External SSD',
            'Smartwatch', 'Portable Power Bank'
        ];

        return $products[array_rand($products)];
    }

    /**
     * Generate a unique slug for the product.
     */
    private function generateUniqueSlug(string $name): string
    {
        $slug = Str::slug($name);
        $count = Product::where('slug', 'LIKE', "$slug%")->count();

        return $count > 0 ? "{$slug}-" . ($count + 1) : $slug;
    }
}
