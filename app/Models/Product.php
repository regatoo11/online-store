<?php

namespace App\Models;

use App\Concerns\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes, Sluggable;

    protected $fillable = [
        'name_ar',
        'name_en',
        'slug',
        'description_ar',
        'description_en',
        'sku',
        'type',
        'price',
        'sale_price',
        'cost_price',
        'category_id',
        'is_active',
        'is_featured',
        'track_stock',
        'stock',
        'reserved_stock',
        'weight',
        'length',
        'width',
        'height',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical',
        'og_image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'track_stock' => 'boolean',
        'stock' => 'integer',
        'reserved_stock' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function primaryMedia(): MorphOne
    {
        return $this->morphOne(Media::class, 'mediable')->where('is_primary', true);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeSimple(Builder $query): Builder
    {
        return $query->where('type', 'simple');
    }

    public function scopeVariable(Builder $query): Builder
    {
        return $query->where('type', 'variable');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock(Builder $query): Builder
    {
        return $query->where('stock', '>', 0);
    }

    public function getAvailableStock(): int
    {
        return $this->stock - $this->reserved_stock;
    }

    public function isAvailable(): bool
    {
        return $this->getAvailableStock() > 0;
    }

    public function getDisplayPrice(): string
    {
        return $this->sale_price ?? $this->price;
    }

    public function hasVariants(): bool
    {
        return $this->type === 'variable';
    }

    public function getSlugSource(): string
    {
        return 'name_en';
    }

    protected static function boot(): void
    {
        parent::boot();
        static::bootSluggable();
    }
}
