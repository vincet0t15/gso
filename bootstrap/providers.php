<?php

use App\Providers\AppServiceProvider;
use App\Providers\FortifyServiceProvider;
use App\Providers\RepositoryServiceProvider;
use Spatie\Permission\PermissionServiceProvider;

return [
    AppServiceProvider::class,
    FortifyServiceProvider::class,
    PermissionServiceProvider::class,
    RepositoryServiceProvider::class,
];
