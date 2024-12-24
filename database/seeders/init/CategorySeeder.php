<?php

namespace Database\Seeders\init;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['name' => 'Кирувчи Хат', 'deadline' => 1, 'score' => 150, 'additional_time' => 3],
            ['name' => 'Чиқувчи хат', 'deadline' => 1, 'score' => 80, 'additional_time' => 3],
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }
    }
}
