<?php

namespace App\Repositories\Contracts;

use App\Models\Item;
use Illuminate\Pagination\LengthAwarePaginator;

interface ItemRepository
{
    /**
     * @return LengthAwarePaginator<int, Item>
     */
    public function paginate(?string $search = null, int $perPage = 15): LengthAwarePaginator;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Item;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Item $item, array $data): Item;

    public function delete(Item $item): void;
}
