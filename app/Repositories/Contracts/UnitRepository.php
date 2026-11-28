<?php

namespace App\Repositories\Contracts;

use App\Models\Unit;
use Illuminate\Pagination\LengthAwarePaginator;

interface UnitRepository
{
    /**
     * @return LengthAwarePaginator<int, Unit>
     */
    public function paginate(?string $search = null, int $perPage = 15): LengthAwarePaginator;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Unit;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Unit $unit, array $data): Unit;

    public function delete(Unit $unit): void;
}
