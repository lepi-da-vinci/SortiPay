<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RewardController extends Controller
{
    public function index(): View
    {
        $rewards = Reward::query()
            ->orderByDesc('is_active')
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        return view('admin.rewards.index', [
            'rewards' => $rewards,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'category' => ['required', 'string', 'max:50'],
            'points_required' => ['required', 'integer', 'min:1', 'max:1000000'],
            'stock' => ['required', 'integer', 'min:0', 'max:100000'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        Reward::query()->create([
            ...$validated,
            'is_active' => true,
        ]);

        return back()->with('success', "Barang hadiah \"{$validated['name']}\" berhasil ditambahkan.");
    }

    public function update(Request $request, Reward $reward): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'category' => ['required', 'string', 'max:50'],
            'points_required' => ['required', 'integer', 'min:1', 'max:1000000'],
            'stock' => ['required', 'integer', 'min:0', 'max:100000'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $reward->update([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'points_required' => $validated['points_required'],
            'stock' => $validated['stock'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', "Barang hadiah \"{$reward->name}\" berhasil diperbarui.");
    }
}
