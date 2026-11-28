<?php

namespace App\Providers;

use App\Repositories\Contracts\ItemRepository;
use App\Repositories\Contracts\OfficeRepository;
use App\Repositories\Contracts\PpeClassificationRepository;
use App\Repositories\Contracts\TypeRepository;
use App\Repositories\Contracts\UnitRepository;
use App\Repositories\Eloquent\EloquentItemRepository;
use App\Repositories\Eloquent\EloquentOfficeRepository;
use App\Repositories\Eloquent\EloquentPpeClassificationRepository;
use App\Repositories\Eloquent\EloquentTypeRepository;
use App\Repositories\Eloquent\EloquentUnitRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the application's repository bindings.
     */
    public function register(): void
    {
        $this->app->bind(OfficeRepository::class, EloquentOfficeRepository::class);
        $this->app->bind(TypeRepository::class, EloquentTypeRepository::class);
        $this->app->bind(PpeClassificationRepository::class, EloquentPpeClassificationRepository::class);
        $this->app->bind(UnitRepository::class, EloquentUnitRepository::class);
        $this->app->bind(ItemRepository::class, EloquentItemRepository::class);
    }
}
