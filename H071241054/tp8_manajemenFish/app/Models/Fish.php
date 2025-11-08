<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fish extends Model
{
    protected $fillable = [
        'name',
        'rarity', 
        'base_weight_min',
        'base_weight_max',
        'sell_price_per_kg',
        'catch_probability',
        'description'
    ];

    protected $casts = [
        'base_weight_min' => 'decimal:2',
        'base_weight_max' => 'decimal:2',
        'catch_probability' => 'decimal:2'
    ];

    // Accessor untuk format harga
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->sell_price_per_kg, 0, ',', '.');
    }

    // Accessor untuk format berat
    public function getWeightRangeAttribute()
    {
        return $this->base_weight_min . ' - ' . $this->base_weight_max . ' kg';
    }

    // Scope untuk filter rarity
    public function scopeByRarity($query, $rarity)
    {
        if ($rarity) {
            return $query->where('rarity', $rarity);
        }
        return $query;
    }
}