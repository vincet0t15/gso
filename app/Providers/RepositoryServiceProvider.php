<?php

namespace App\Providers;

use App\Repositories\Contracts\OfficeRepository;
use App\Repositories\Contracts\PpeClassificationRepository;
use App\Repositories\Contracts\TypeRepository;
use App\Repositories\Eloquent\EloquentOfficeRepository;
use App\Repositories\Eloquent\EloquentPpeClassificationRepository;
use App\Repositories\Eloquent\EloquentTypeRepository;
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
    }
}
