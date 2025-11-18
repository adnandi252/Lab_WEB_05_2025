<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['productDetail'])
            ->latest()
            ->paginate(10);

        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'weight' => 'required|numeric|min:0',
            'size' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($validated) {
            $product = Product::create([
                'name' => $validated['name'],
                'price' => $validated['price'],
                'category_id' => $validated['category_id'] ?? null,
            ]);

            ProductDetail::create([
                'product_id' => $product->id,
                'description' => $validated['description'] ?? null,
                'weight' => $validated['weight'],
                'size' => $validated['size'] ?? null,
            ]);
        });

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully!');
    }

    public function show(string $id)
    {
        $product = Product::with(['category', 'productDetail'])->findOrFail($id);
        return view('products.show', compact('product'));
    }

    public function edit(string $id)
    {
        $product = Product::with('productDetail')->findOrFail($id);
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'weight' => 'required|numeric|min:0',
            'size' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($validated, $id) {
            $product = Product::findOrFail($id);

            $product->update([
                'name' => $validated['name'],
                'price' => $validated['price'],
                'category_id' => $validated['category_id'] ?? null,
            ]);

            ProductDetail::updateOrCreate(
                ['product_id' => $product->id],
                [
                    'description' => $validated['description'] ?? null,
                    'weight' => $validated['weight'],
                    'size' => $validated['size'] ?? null,
                ]
            );
        });

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        
        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully!');
    }
}