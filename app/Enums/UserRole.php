<?php

namespace App\Enums;

enum UserRole: string
{
    case User = 'USER';
    case Officer = 'OFFICER';
    case Admin = 'ADMIN';

    public function label(): string
    {
        return match ($this) {
            self::User => 'Nasabah',
            self::Officer => 'Petugas Posko',
            self::Admin => 'Administrator',
        };
    }

    public function dashboardRoute(): string
    {
        return match ($this) {
            self::User => 'customer.dashboard',
            self::Officer => 'officer.deposit.index',
            self::Admin => 'admin.dashboard',
        };
    }
}
