<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'deposit_id',
    'waste_type_id',
    'quantity',
    'quality_multiplier',
    'points_per_unit_snapshot',
    'subtotal_points',
])]
class DepositItem extends Model
{
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'quality_multiplier' => 'decimal:2',
            'points_per_unit_snapshot' => 'integer',
            'subtotal_points' => 'integer',
        ];
    }

    public function deposit(): BelongsTo
    {
        return $this->belongsTo(DepositTransaction::class, 'deposit_id');
    }

    public function wasteType(): BelongsTo
    {
        return $this->belongsTo(WasteType::class);
    }
}
