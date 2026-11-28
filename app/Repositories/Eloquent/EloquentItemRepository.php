<?php

namespace App\Repositories\Eloquent;

use App\Models\Item;
use App\Repositories\Contracts\ItemRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentItemRepository implements ItemRepository
{
    public function paginate(?string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        return Item::query()
            ->with(['type', 'ppeClassification', 'unit'])
            ->when($search !== null && $search !== '', function ($query) use ($search): void {
                $query->where('item_code', 'like', '%'.$search.'%')
                    ->orWhere('item_name', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            })
            ->orderBy('item_name')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Item
    {
        return Item::query()->create($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Item $item, array $data): Item
    {
        $item->update($data);

        return $item->fresh(['type', 'ppeClassification', 'unit']) ?? $item;
    }

    public function delete(Item $item): void
    {
        $item->delete();
    }
}
