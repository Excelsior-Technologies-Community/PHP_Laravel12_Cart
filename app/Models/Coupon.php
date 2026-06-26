<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = ['code', 'type', 'value', 'active', 'expires_at'];

    protected $casts = [
        'expires_at' => 'datetime',
        'active' => 'boolean',
    ];

    public function isValid(): bool
    {
        if (!$this->active) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    public function calculateDiscount(float $total): float
    {
        if ($this->type === 'percent') {
            return round($total * ($this->value / 100), 2);
        }

        return min($this->value, $total);
    }
}