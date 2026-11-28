<?php

namespace App\Repositories\Eloquent;

use App\Models\Type;
use App\Repositories\Contracts\TypeRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentTypeRepository implements TypeRepository
{
    public function paginate(?string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        return Type::query()
            ->when($search !== null && $search !== '', function ($query) use ($search): void {
                $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            })
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Type
    {
        return Type::query()->create($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Type $type, array $data): Type
    {
        $type->update($data);

        return $type->fresh() ?? $type;
    }

    public function delete(Type $type): void
    {
        $type->delete();
    }
}
