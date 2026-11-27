<?php

namespace App\Enums;

enum RedemptionStatus: string
{
    case Pending = 'PENDING';
    case Completed = 'COMPLETED';
    case Cancelled = 'CANCELLED';
    case Expired = 'EXPIRED';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu Pengambilan',
            self::Completed => 'Selesai',
            self::Cancelled => 'Dibatalkan',
            self::Expired => 'Kedaluwarsa',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Pending => 'bg-amber-100 text-amber-800 border-amber-200',
            self::Completed => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            self::Cancelled => 'bg-rose-100 text-rose-800 border-rose-200',
            self::Expired => 'bg-slate-100 text-slate-700 border-slate-200',
        };
    }
}
