<?php

namespace App\Models;

use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'member_code',
    'name',
    'email',
    'phone',
    'address',
    'role',
    'points_balance',
    'password',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'points_balance' => 'integer',
        ];
    }

    public function deposits(): HasMany
    {
        return $this->hasMany(DepositTransaction::class, 'user_id');
    }

    public function processedDeposits(): HasMany
    {
        return $this->hasMany(DepositTransaction::class, 'officer_id');
    }

    public function redemptions(): HasMany
    {
        return $this->hasMany(Redemption::class, 'user_id');
    }

    public function pointLedgers(): HasMany
    {
        return $this->hasMany(PointLedger::class, 'user_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isOfficer(): bool
    {
        return $this->role === UserRole::Officer;
    }

    public function isCustomer(): bool
    {
        return $this->role === UserRole::User;
    }

    public static function generateMemberCode(): string
    {
        $year = now()->format('Y');
        $prefix = "SRT-{$year}-";

        $lastCode = static::query()
            ->where('member_code', 'like', "{$prefix}%")
            ->orderByDesc('id')
            ->value('member_code');

        $nextNumber = 1;
        if (is_string($lastCode) && preg_match('/(\d+)$/', $lastCode, $matches)) {
            $nextNumber = ((int) $matches[1]) + 1;
        }

        do {
            $candidate = $prefix.str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
            $nextNumber++;
        } while (static::query()->where('member_code', $candidate)->exists());

        return $candidate;
    }
}
