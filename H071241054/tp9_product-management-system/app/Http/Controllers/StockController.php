<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Warehouse;
use App\Models\ProductWarehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $selectedWarehouseId = $request->get('warehouse_id');
        
        $warehouses = Warehouse::all();
        
        $stocksQuery = ProductWarehouse::with(['product', 'warehouse']);
        
        if ($selectedWarehouseId) {
            $stocksQuery->where('warehouse_id', $selectedWarehouseId);
        }
        
        $stocks = $stocksQuery->latest()->get();
        
        return view('stocks.index', compact(
            'stocks', 
            'warehouses', 
            'selectedWarehouseId'
        ));
    }

    public function createTransfer()
    {
        // Ambil data untuk dropdown
        $warehouses = Warehouse::all();
        $products = Product::all();
        
        return view('stocks.transfer', compact(
            'warehouses', 
            'products'
        ));
    }

    public function storeTransfer(Request $request)
    {
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
            'notes' => 'nullable|string|max:500'
        ]);

        $warehouseId = $request->warehouse_id;
        $productId = $request->product_id;
        $quantity = $request->quantity;

        try {
            DB::transaction(function () use ($warehouseId, $productId, $quantity) {
                
                $currentStock = ProductWarehouse::where('warehouse_id', $warehouseId)
                    ->where('product_id', $productId)
                    ->first();

                if (!$currentStock && $quantity < 0) {
                    throw new \Exception('Produk belum tersedia di gudang ini, tidak bisa mengurangi stok.');
                }

                if ($currentStock && ($currentStock->quantity + $quantity) < 0) {
                    throw new \Exception(
                        'Stok tidak mencukupi. Stok saat ini: ' . $currentStock->quantity . 
                        ', ingin mengurangi: ' . abs($quantity)
                    );
                }

                if ($currentStock) {
                    // Jika sudah ada, update quantity
                    $currentStock->update([
                        'quantity' => $currentStock->quantity + $quantity
                    ]);
                } else {
                    // Jika belum ada, buat record baru
                    ProductWarehouse::create([
                        'warehouse_id' => $warehouseId,
                        'product_id' => $productId,
                        'quantity' => $quantity
                    ]);
                }
            });

            return redirect()->route('stocks.index')
                ->with('success', 
                    'Stok berhasil diupdate! ' . 
                    ($quantity >= 0 ? 'Penambahan' : 'Pengurangan') . 
                    ' sebanyak ' . abs($quantity) . ' unit.'
                );

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
}