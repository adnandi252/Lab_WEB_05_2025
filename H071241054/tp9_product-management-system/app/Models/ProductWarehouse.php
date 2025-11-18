<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductWarehouse extends Model
{
    use HasFactory;
    protected $table = 'product_warehouse';

    protected $fillable = [
        'product_id',
        'warehouse_id', 
        'quantity'
    ];

    // Relasi ke produk:
    // Setiap baris pivot ini berhubungan dengan satu produk.
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relasi ke warehouse:
    // Setiap baris pivot ini berhubungan dengan satu warehouse.
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}