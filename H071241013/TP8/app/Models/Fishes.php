<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fishes extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'rarity',
        'base_weight_min',
        'base_weight_max',
        'sell_price_per_kg',
        'catch_probability',
        'description'
    ];

    // Scope untuk filter berdasarkan rarity
    public function scopeRarity($query, $rarity)
    {
        if ($rarity) {
            return $query->where('rarity', $rarity);
        }
        return $query;
    }

    // Scope untuk search berdasarkan nama
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('name', 'like', '%' . $search . '%');
        }
        return $query;
    }

    // Accessor untuk format harga
    public function getFormattedPriceAttribute()
    {
        return number_format($this->sell_price_per_kg, 0, ',', '.') . ' Coins';
    }

    // Accessor untuk format berat
    public function getFormattedWeightRangeAttribute()
    {
        return $this->base_weight_min . ' - ' . $this->base_weight_max . ' kg';
    }

    // Method untuk warna badge rarity
    public function getRarityColorAttribute()
    {
        return match($this->rarity) {
            'Common' => 'bg-gray-500',
            'Uncommon' => 'bg-green-500',
            'Rare' => 'bg-blue-500',
            'Epic' => 'bg-purple-500',
            'Legendary' => 'bg-yellow-500',
            'Mythic' => 'bg-pink-500',
            'Secret' => 'bg-red-500',
            default => 'bg-gray-500'
        };
    }
}