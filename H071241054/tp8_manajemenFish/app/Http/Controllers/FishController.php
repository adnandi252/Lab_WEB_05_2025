<?php

namespace App\Http\Controllers;

use App\Models\Fish;
use Illuminate\Http\Request;

class FishController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $fishes = Fish::byRarity($request->rarity)
                ->latest()
                ->paginate(10);

            $rarities = [
                'Common', 'Uncommon', 'Rare', 'Epic', 
                'Legendary', 'Mythic', 'Secret'
            ];

            return view('fishes.index', compact('fishes', 'rarities'));
                
        } catch (\Exception $e) {
            return redirect()->route('fishes.index')
                ->with('error', 'Terjadi kesalahan saat memuat data ikan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rarities = [
            'Common', 'Uncommon', 'Rare', 'Epic', 
            'Legendary', 'Mythic', 'Secret'
        ];
        return view('fishes.create', compact('rarities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'rarity' => 'required|in:Common,Uncommon,Rare,Epic,Legendary,Mythic,Secret',
                'base_weight_min' => 'required|numeric|min:0.01',
                'base_weight_max' => 'required|numeric|gt:base_weight_min',
                'sell_price_per_kg' => 'required|integer|min:0',
                'catch_probability' => 'required|numeric|between:0.01,100.00',
                'description' => 'nullable|string|max:500'
            ], [
                'base_weight_min.required' => 'Berat minimum harus diisi.',
                'base_weight_max.required' => 'Berat maksimum harus diisi.',
                'base_weight_max.gt' => 'Berat maksimum harus lebih besar dari berat minimum.',
                'catch_probability.between' => 'Peluang tangkap harus antara 0.01% hingga 100.00%.',
                'sell_price_per_kg.min' => 'Harga jual tidak boleh negatif.'
            ]);

            Fish::create($validated);

            return redirect()
                ->route('fishes.index')
                ->with('success', 'Ikan berhasil ditambahkan!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambah ikan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Fish $fish)
    {
        try {
            return view('fishes.show', compact('fish'));
        } catch (\Exception $e) {
            return redirect()->route('fishes.index')
                ->with('error', 'Terjadi kesalahan saat memuat detail ikan.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fish $fish)
    {
        try {
            $rarities = [
                'Common', 'Uncommon', 'Rare', 'Epic', 
                'Legendary', 'Mythic', 'Secret'
            ];
            return view('fishes.edit', compact('fish', 'rarities'));
        } catch (\Exception $e) {
            return redirect()->route('fishes.index')
                ->with('error', 'Terjadi kesalahan saat memuat form edit.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fish $fish)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'rarity' => 'required|in:Common,Uncommon,Rare,Epic,Legendary,Mythic,Secret',
                'base_weight_min' => 'required|numeric|min:0.01',
                'base_weight_max' => 'required|numeric|gt:base_weight_min',
                'sell_price_per_kg' => 'required|integer|min:0',
                'catch_probability' => 'required|numeric|between:0.01,100.00',
                'description' => 'nullable|string|max:500'
            ], [
                'base_weight_min.required' => 'Berat minimum harus diisi.',
                'base_weight_max.required' => 'Berat maksimum harus diisi.',
                'base_weight_max.gt' => 'Berat maksimum harus lebih besar dari berat minimum.',
                'catch_probability.between' => 'Peluang tangkap harus antara 0.01% hingga 100.00%.',
                'sell_price_per_kg.min' => 'Harga jual tidak boleh negatif.'
            ]);

            $fish->update($validated);

            return redirect()
                ->route('fishes.index')
                ->with('success', 'Ikan berhasil diupdate!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengupdate ikan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fish $fish)
    {
        try {
            $fish->delete();

            return redirect()
                ->route('fishes.index')
                ->with('success', 'Ikan berhasil dihapus!');
                
        } catch (\Exception $e) {
            return redirect()->route('fishes.index')
                ->with('error', 'Terjadi kesalahan saat menghapus ikan: ' . $e->getMessage());
        }
    }
}