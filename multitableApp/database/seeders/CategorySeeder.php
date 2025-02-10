<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics'],
            ['name' => 'Fashion'],
            ['name' => 'Home & Garden'],
            ['name' => 'Sports'],
            ['name' => 'Books'],
            ['name' => 'Toys & Games'],
            ['name' => 'Vehicles'],
            ['name' => 'Music'],
            ['name' => 'Art & Collectibles'],
            ['name' => 'Others']
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}