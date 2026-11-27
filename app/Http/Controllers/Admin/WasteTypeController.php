<?php

namespace App\Http\Controllers\Admin;

use App\Enums\WasteCategory;
use App\Enums\WasteUnit;
use App\Http\Controllers\Controller;
use App\Models\WasteType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class WasteTypeController extends Controller
{
    public function index(): View
    {
        $wasteTypes = WasteType::query()
            ->orderByDesc('is_active')
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        return view('admin.waste-types.index', [
            'wasteTypes' => $wasteTypes,
            'categories' => WasteCategory::cases(),
            'units' => WasteUnit::cases(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category' => ['required', Rule::enum(WasteCategory::class)],
            'name' => ['required', 'string', 'max:100'],
            'unit' => ['required', Rule::enum(WasteUnit::class)],
            'points_per_unit' => ['required', 'integer', 'min:1', 'max:100000'],
            'is_recyclable' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        WasteType::query()->create([
            'category' => $validated['category'],
            'name' => $validated['name'],
            'unit' => $validated['unit'],
            'points_per_unit' => $validated['points_per_unit'],
            'is_recyclable' => $request->boolean('is_recyclable', true),
            'description' => $validated['description'] ?? null,
            'is_active' => true,
        ]);

        return back()->with('success', "Jenis sampah \"{$validated['name']}\" berhasil ditambahkan.");
    }

    public function update(Request $request, WasteType $wasteType): RedirectResponse
    {
        $validated = $request->validate([
            'category' => ['required', Rule::enum(WasteCategory::class)],
            'name' => ['required', 'string', 'max:100'],
            'unit' => ['required', Rule::enum(WasteUnit::class)],
            'points_per_unit' => ['required', 'integer', 'min:1', 'max:100000'],
            'is_recyclable' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $wasteType->update([
            'category' => $validated['category'],
            'name' => $validated['name'],
            'unit' => $validated['unit'],
            'points_per_unit' => $validated['points_per_unit'],
            'is_recyclable' => $request->boolean('is_recyclable'),
            'is_active' => $request->boolean('is_active'),
            'description' => $validated['description'] ?? null,
        ]);

        return back()->with('success', "Master sampah \"{$wasteType->name}\" berhasil diperbarui. Histori transaksi lama tetap terjaga menggunakan snapshot.");
    }
}
