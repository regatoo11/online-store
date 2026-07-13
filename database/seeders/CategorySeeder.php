<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $electronics = Category::create([
            'name_ar' => 'إلكترونيات',
            'name_en' => 'Electronics',
            'is_active' => true,
            'parent_id' => null,
        ]);

        Category::create([
            'name_ar' => 'تليفونات',
            'name_en' => 'Phones',
            'is_active' => true,
            'parent_id' => $electronics->id,
        ]);

        Category::create([
            'name_ar' => 'لابتوبات',
            'name_en' => 'Laptops',
            'is_active' => true,
            'parent_id' => $electronics->id,
        ]);

        Category::create([
            'name_ar' => 'إكسسوارات',
            'name_en' => 'Accessories',
            'is_active' => true,
            'parent_id' => $electronics->id,
        ]);

        $clothing = Category::create([
            'name_ar' => 'ملابس',
            'name_en' => 'Clothing',
            'is_active' => true,
            'parent_id' => null,
        ]);

        Category::create([
            'name_ar' => 'رجالي',
            'name_en' => 'Men',
            'is_active' => true,
            'parent_id' => $clothing->id,
        ]);

        Category::create([
            'name_ar' => 'نسائي',
            'name_en' => 'Women',
            'is_active' => true,
            'parent_id' => $clothing->id,
        ]);
    }
}
