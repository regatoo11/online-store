<?php

namespace App\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait Sluggable
{
    public static function bootSluggable(): void
    {
        static::creating(function (Model $model) {
            $source = $model->getSlugSource();
            $value = $model->{$source} ?? '';
            if (empty($model->slug)) {
                $model->slug = $model->generateSlug($value);
            }
        });
    }

    public function generateSlug(string $value): string
    {
        $value = $this->transliterateArabic($value);
        $slug = Str::slug($value);

        if (empty($slug)) {
            $slug = Str::random(8);
        }

        $originalSlug = $slug;
        $count = 1;

        while (static::withoutGlobalScopes()
            ->where('slug', $slug)
            ->where('id', '!=', $this->id ?? null)
            ->exists()
        ) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    protected function transliterateArabic(string $value): string
    {
        $map = [
            'أ' => 'a', 'إ' => 'i', 'آ' => 'a', 'ا' => 'a',
            'ب' => 'b', 'ت' => 't', 'ث' => 'th', 'ج' => 'j',
            'ح' => 'h', 'خ' => 'kh', 'د' => 'd', 'ذ' => 'dh',
            'ر' => 'r', 'ز' => 'z', 'س' => 's', 'ش' => 'sh',
            'ص' => 's', 'ض' => 'd', 'ط' => 't', 'ظ' => 'z',
            'ع' => 'a', 'غ' => 'gh', 'ف' => 'f', 'ق' => 'q',
            'ك' => 'k', 'ل' => 'l', 'م' => 'm', 'ن' => 'n',
            'ه' => 'h', 'و' => 'w', 'ي' => 'y', 'ة' => 'a',
            'ى' => 'a', 'ء' => '', 'ؤ' => '', 'ئ' => '',
            'ـ' => '',
        ];

        return strtr($value, $map);
    }

    public function getSlugSource(): string
    {
        return 'name_en';
    }
}
