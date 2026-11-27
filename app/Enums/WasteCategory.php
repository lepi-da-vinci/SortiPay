<?php

namespace App\Enums;

enum WasteCategory: string
{
    case Organik = 'ORGANIK';
    case Anorganik = 'ANORGANIK';
    case DaurUlang = 'DAUR_ULANG';
    case KhususB3 = 'KHUSUS_B3';

    public function label(): string
    {
        return match ($this) {
            self::Organik => 'Organik',
            self::Anorganik => 'Anorganik',
            self::DaurUlang => 'Daur Ulang',
            self::KhususB3 => 'Khusus / B3',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Organik => 'bg-lime-100 text-lime-800 border-lime-200',
            self::Anorganik => 'bg-sky-100 text-sky-800 border-sky-200',
            self::DaurUlang => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            self::KhususB3 => 'bg-amber-100 text-amber-800 border-amber-200',
        };
    }
}
