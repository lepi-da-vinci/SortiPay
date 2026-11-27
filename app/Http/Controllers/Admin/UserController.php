<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PointLedgerType;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\PointLedger;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $roleFilter = $request->query('role');

        $users = User::query()
            ->when($roleFilter && UserRole::tryFrom((string) $roleFilter), fn ($q) => $q->where('role', $roleFilter))
            ->orderBy('role')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.users.index', [
            'users' => $users,
            'roles' => UserRole::cases(),
            'roleFilter' => $roleFilter,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone'],
            'role' => ['required', Rule::enum(UserRole::class)],
            'address' => ['nullable', 'string', 'max:500'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::query()->create([
            'member_code' => User::generateMemberCode(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'] ?? null,
            'role' => $validated['role'],
            'points_balance' => 0,
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(),
        ]);

        return back()->with('success', "Akun {$user->name} ({$user->role->label()}) berhasil dibuat dengan kode {$user->member_code}.");
    }

    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8'],
        ], [
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal 8 karakter.',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', "Kata sandi untuk {$user->name} ({$user->member_code}) berhasil direset.");
    }

    public function auditLogs(Request $request): View
    {
        $typeFilter = $request->query('type');

        $ledgers = PointLedger::query()
            ->with(['user:id,name,member_code', 'creator:id,name'])
            ->when($typeFilter && PointLedgerType::tryFrom((string) $typeFilter), fn ($q) => $q->where('type', $typeFilter))
            ->latest('id')
            ->paginate(20);

        return view('admin.audit-logs.index', [
            'ledgers' => $ledgers,
            'types' => PointLedgerType::cases(),
            'typeFilter' => $typeFilter,
        ]);
    }
}
