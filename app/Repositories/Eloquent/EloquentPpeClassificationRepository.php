<?php

namespace App\Repositories\Eloquent;

use App\Models\PpeClassification;
use App\Repositories\Contracts\PpeClassificationRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentPpeClassificationRepository implements PpeClassificationRepository
{
    public function paginate(?string $search = null, int $perPage = 15): LengthAwarePaginator
    {
        return PpeClassification::query()
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
    public function create(array $data): PpeClassification
    {
        return PpeClassification::query()->create($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(PpeClassification $ppeClassification, array $data): PpeClassification
    {
        $ppeClassification->update($data);

        return $ppeClassification->fresh() ?? $ppeClassification;
    }

    public function delete(PpeClassification $ppeClassification): void
    {
        $ppeClassification->delete();
    }
}
