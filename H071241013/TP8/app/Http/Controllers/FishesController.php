<?php

namespace App\Http\Controllers;

use App\Models\Fishes;
use Illuminate\Http\Request;

class FishesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Mulai query
        $query = Fishes::query();

        // Filter berdasarkan rarity
        if ($request->has('rarity') && $request->rarity != '') {
            $query->rarity($request->rarity);
        }

        // Search berdasarkan nama
        if ($request->has('search') && $request->search != '') {
            $query->search($request->search);
        }

        // Sorting
        $sortBy = $request->get('sort', 'name');
        $sortOrder = $request->get('order', 'asc');
        
        if (in_array($sortBy, ['name', 'sell_price_per_kg', 'catch_probability'])) {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Pagination 10 data per halaman
        $fishes = $query->paginate(10)->withQueryString();
        
        // List rarity untuk dropdown
        $rarities = ['Common', 'Uncommon', 'Rare', 'Epic', 'Legendary', 'Mythic', 'Secret'];

        return view('fishes.index', compact('fishes', 'rarities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rarities = ['Common', 'Uncommon', 'Rare', 'Epic', 'Legendary', 'Mythic', 'Secret'];
        return view('fishes.create', compact('rarities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|max:100',
            'rarity' => 'required|in:Common,Uncommon,Rare,Epic,Legendary,Mythic,Secret',
            'base_weight_min' => 'required|numeric|min:0',
            'base_weight_max' => 'required|numeric|gt:base_weight_min',
            'sell_price_per_kg' => 'required|integer|min:0',
            'catch_probability' => 'required|numeric|min:0.01|max:100',
            'description' => 'nullable|string'
        ], [
            'base_weight_max.gt' => 'Berat maksimum harus lebih besar dari berat minimum',
            'catch_probability.min' => 'Probabilitas minimal 0.01%',
            'catch_probability.max' => 'Probabilitas maksimal 100%'
        ]);

        // Simpan data
        Fishes::create($validated);

        // Redirect dengan pesan sukses
        return redirect()->route('fishes.index')->with('success', 'Ikan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Fishes $fish)
    {
        return view('fishes.show', compact('fish'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fishes $fish)
    {
        $rarities = ['Common', 'Uncommon', 'Rare', 'Epic', 'Legendary', 'Mythic', 'Secret'];
        return view('fishes.edit', compact('fish', 'rarities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fishes $fish)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|max:100',
            'rarity' => 'required|in:Common,Uncommon,Rare,Epic,Legendary,Mythic,Secret',
            'base_weight_min' => 'required|numeric|min:0',
            'base_weight_max' => 'required|numeric|gt:base_weight_min',
            'sell_price_per_kg' => 'required|integer|min:0',
            'catch_probability' => 'required|numeric|min:0.01|max:100',
            'description' => 'nullable|string'
        ], [
            'base_weight_max.gt' => 'Berat maksimum harus lebih besar dari berat minimum',
            'catch_probability.min' => 'Probabilitas minimal 0.01%',
            'catch_probability.max' => 'Probabilitas maksimal 100%'
        ]);

        // Update data
        $fish->update($validated);

        // Redirect dengan pesan sukses
        return redirect()->route('fishes.index')->with('success', 'Ikan berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fishes $fish)
    {
        // Hapus data
        $fish->delete();
        
        // Redirect dengan pesan sukses
        return redirect()->route('fishes.index')->with('success', 'Ikan berhasil dihapus!');
    }
}