<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'coupon_id',
        'discount',
    ];

    protected $casts = [
        'discount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function getTotal(): float
    {
        return $this->getSubtotal() - $this->discount;
    }

    public function getItemCount(): int
    {
        return $this->items->sum('quantity');
    }

    public function getSubtotal(): float
    {
        return (float) $this->items->sum(function (CartItem $item) {
            return $item->getSubtotal();
        });
    }
}
