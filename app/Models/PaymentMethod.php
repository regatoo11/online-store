<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
        'code',
        'icon',
        'description_ar',
        'description_en',
        'instructions_ar',
        'instructions_en',
        'requires_receipt',
        'is_active',
        'sort_order',
        'settings',
    ];

    protected $casts = [
        'requires_receipt' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'settings' => 'array',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function requiresReceipt(): bool
    {
        return $this->requires_receipt;
    }
}
