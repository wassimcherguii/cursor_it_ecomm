<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Computers & Laptops',
                'slug' => 'computers-laptops',
                'description' => 'Desktop computers, laptops, workstations, and computer accessories',
                'is_active' => true,
            ],
            [
                'name' => 'Smartphones & Tablets',
                'slug' => 'smartphones-tablets',
                'description' => 'Latest smartphones, tablets, and mobile accessories',
                'is_active' => true,
            ],
            [
                'name' => 'Computer Components',
                'slug' => 'computer-components',
                'description' => 'CPUs, GPUs, RAM, motherboards, and other PC components',
                'is_active' => true,
            ],
            [
                'name' => 'Accessories',
                'slug' => 'accessories',
                'description' => 'Keyboards, mice, headphones, speakers, and other accessories',
                'is_active' => true,
            ],
            [
                'name' => 'Cables & Adapters',
                'slug' => 'cables-adapters',
                'description' => 'USB cables, HDMI cables, adapters, and connectivity solutions',
                'is_active' => true,
            ],
            [
                'name' => 'Gaming',
                'slug' => 'gaming',
                'description' => 'Gaming consoles, controllers, and gaming accessories',
                'is_active' => true,
            ],
            [
                'name' => 'Audio & Video',
                'slug' => 'audio-video',
                'description' => 'Headphones, speakers, microphones, and video equipment',
                'is_active' => true,
            ],
            [
                'name' => 'Storage',
                'slug' => 'storage',
                'description' => 'Hard drives, SSDs, USB drives, and storage solutions',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
