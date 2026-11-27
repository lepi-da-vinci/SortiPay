<?php

namespace App\Enums;

enum WasteUnit: string
{
    case Kg = 'kg';
    case Liter = 'liter';
    case Pcs = 'pcs';

    public function label(): string
    {
        return match ($this) {
            self::Kg => 'kg',
            self::Liter => 'liter',
            self::Pcs => 'pcs',
        };
    }
}
