<?php

namespace App\Providers;

use App\Interface\RoleManagement\PermissionRepositoryInterface;
use App\Interface\RoleManagement\RoleRepositoryInterface;
use App\Repositories\RoleManagement\PermissionRepository;
use App\Repositories\RoleManagement\RoleRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // role
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);

        // permission
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
