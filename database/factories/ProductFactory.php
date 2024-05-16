<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Brand;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->name();
        $slug = Str::slug($title);

        // Fetch valid IDs from the database
        $categories = Category::pluck('id')->toArray();
        $subCategories = SubCategory::pluck('id')->toArray();
        $brands = Brand::pluck('id')->toArray();

        // Ensure arrays are not empty before fetching a random key
        $categoryRandKey = !empty($categories) ? array_rand($categories) : null;
        $subCatRandKey = !empty($subCategories) ? array_rand($subCategories) : null;
        $brandRandKey = !empty($brands) ? array_rand($brands) : null;

        return [
            'title' => $title,
            'slug' => $slug,
            'category_id' => $categoryRandKey !== null ? $categories[$categoryRandKey] : null,
            'sub_category_id' => $subCatRandKey !== null ? $subCategories[$subCatRandKey] : null,
            'brand_id' => $brandRandKey !== null ? $brands[$brandRandKey] : null,
            'price' => rand(10, 1000),
            'sku' => rand(1000, 100000),
            'track_qty' => 'Yes',
            'qty' => 10,
            'is_featured' => 'Yes',
            'status' => 1,
        ];
    }
}
