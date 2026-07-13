<?php

namespace App\Services;

use App\Models\Attribute;
use Illuminate\Support\Collection;

class AttributeService
{
    public function getAll(): Collection
    {
        return Attribute::with('values')->orderBy('sort_order')->get();
    }

    public function getOrCreate(string $nameAr, string $nameEn, string $type): Attribute
    {
        return Attribute::firstOrCreate(
            ['name_en' => $nameEn],
            [
                'name_ar' => $nameAr,
                'type' => $type,
            ]
        );
    }
}
