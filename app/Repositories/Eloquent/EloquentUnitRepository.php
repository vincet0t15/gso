<?php

namespace App\Repositories\Eloquent;

use App\Models\Unit;
use App\Repositories\Contracts\UnitRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentUnitRepository implements UnitRepository
{
    public function paginate(?string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        return Unit::query()
            ->when($search !== null && $search !== '', function ($query) use ($search): void {
                $query->where('code', 'like', '%'.$search.'%')
                    ->orWhere('name', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            })
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Unit
    {
        return Unit::query()->create($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Unit $unit, array $data): Unit
    {
        $unit->update($data);

        return $unit->fresh() ?? $unit;
    }

    public function delete(Unit $unit): void
    {
        $unit->delete();
    }
}
