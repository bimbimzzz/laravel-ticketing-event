<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    protected $fillable = [
        'event_id',
        'code',
        'discount_type',
        'discount_value',
        'max_usage',
        'used_count',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function isValid(): bool
    {
        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        if ($this->max_usage > 0 && $this->used_count >= $this->max_usage) {
            return false;
        }

        return true;
    }

    public function calculateDiscount(int $totalPrice): int
    {
        if ($this->discount_type === 'percentage') {
            $discount = (int) ($totalPrice * $this->discount_value / 100);
        } else {
            $discount = $this->discount_value;
        }

        return min($discount, $totalPrice);
    }
}
