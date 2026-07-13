<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    public function run(): void
    {
        $color = Attribute::create([
            'name_ar' => 'اللون',
            'name_en' => 'Color',
        ]);

        AttributeValue::create([
            'attribute_id' => $color->id,
            'value_ar' => 'أحمر',
            'value_en' => 'Red',
        ]);

        AttributeValue::create([
            'attribute_id' => $color->id,
            'value_ar' => 'أزرق',
            'value_en' => 'Blue',
        ]);

        AttributeValue::create([
            'attribute_id' => $color->id,
            'value_ar' => 'أسود',
            'value_en' => 'Black',
        ]);

        AttributeValue::create([
            'attribute_id' => $color->id,
            'value_ar' => 'أبيض',
            'value_en' => 'White',
        ]);

        $size = Attribute::create([
            'name_ar' => 'المقاس',
            'name_en' => 'Size',
        ]);

        AttributeValue::create([
            'attribute_id' => $size->id,
            'value_ar' => 'S',
            'value_en' => 'S',
        ]);

        AttributeValue::create([
            'attribute_id' => $size->id,
            'value_ar' => 'M',
            'value_en' => 'M',
        ]);

        AttributeValue::create([
            'attribute_id' => $size->id,
            'value_ar' => 'L',
            'value_en' => 'L',
        ]);

        AttributeValue::create([
            'attribute_id' => $size->id,
            'value_ar' => 'XL',
            'value_en' => 'XL',
        ]);

        AttributeValue::create([
            'attribute_id' => $size->id,
            'value_ar' => 'XXL',
            'value_en' => 'XXL',
        ]);
    }
}
