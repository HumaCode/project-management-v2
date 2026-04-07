<?php

namespace App\Providers;

use App\Interface\RoleManagement\PermissionRepositoryInterface;
use App\Interface\RoleManagement\RoleRepositoryInterface;
use App\Interface\RoleManagement\UserRepositoryInterface;
use App\Repositories\RoleManagement\PermissionRepository;
use App\Repositories\RoleManagement\RoleRepository;
use App\Repositories\RoleManagement\UserRepository;
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

        // users
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
