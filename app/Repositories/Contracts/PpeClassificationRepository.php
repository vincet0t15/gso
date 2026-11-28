<?php

namespace App\Repositories\Contracts;

use App\Models\PpeClassification;
use Illuminate\Pagination\LengthAwarePaginator;

interface PpeClassificationRepository
{
    /**
     * @return LengthAwarePaginator<int, PpeClassification>
     */
    public function paginate(?string $search = null, int $perPage = 15): LengthAwarePaginator;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): PpeClassification;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(PpeClassification $ppeClassification, array $data): PpeClassification;

    public function delete(PpeClassification $ppeClassification): void;
}
