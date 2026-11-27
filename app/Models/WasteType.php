<?php

namespace App\Models;

use App\Enums\WasteCategory;
use App\Enums\WasteUnit;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'category',
    'name',
    'unit',
    'points_per_unit',
    'is_recyclable',
    'description',
    'is_active',
])]
class WasteType extends Model
{
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'category' => WasteCategory::class,
            'unit' => WasteUnit::class,
            'points_per_unit' => 'integer',
            'is_recyclable' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function depositItems(): HasMany
    {
        return $this->hasMany(DepositItem::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
