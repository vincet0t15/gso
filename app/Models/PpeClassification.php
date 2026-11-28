<?php

namespace App\Models;

use Database\Factories\PpeClassificationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['code', 'name', 'description', 'is_active'])]
class PpeClassification extends Model
{
    /** @use HasFactory<PpeClassificationFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    /**
     * @param  Builder<PpeClassification>  $query
     * @return Builder<PpeClassification>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
