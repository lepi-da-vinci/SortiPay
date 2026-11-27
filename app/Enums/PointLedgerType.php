<?php

namespace App\Enums;

enum PointLedgerType: string
{
    case DepositCredit = 'DEPOSIT_CREDIT';
    case RedemptionDebit = 'REDEMPTION_DEBIT';
    case RedemptionRefund = 'REDEMPTION_REFUND';
    case AdminAdjustment = 'ADMIN_ADJUSTMENT';

    public function label(): string
    {
        return match ($this) {
            self::DepositCredit => 'Setor Sampah',
            self::RedemptionDebit => 'Tukar Barang',
            self::RedemptionRefund => 'Pengembalian Poin',
            self::AdminAdjustment => 'Penyesuaian Admin',
        };
    }
}
