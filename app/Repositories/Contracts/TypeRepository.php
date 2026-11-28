<?php

namespace App\Repositories\Contracts;

use App\Models\Type;
use Illuminate\Pagination\LengthAwarePaginator;

interface TypeRepository
{
    /**
     * @return LengthAwarePaginator<int, Type>
     */
    public function paginate(?string $search = null, int $perPage = 15): LengthAwarePaginator;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Type;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Type $type, array $data): Type;

    public function delete(Type $type): void;
}
