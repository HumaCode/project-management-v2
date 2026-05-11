<?php

namespace App\Providers;

use App\Interface\RoleManagement\PermissionRepositoryInterface;
use App\Interface\RoleManagement\PermissionServiceInterface;
use App\Interface\RoleManagement\RoleRepositoryInterface;
use App\Interface\RoleManagement\RoleServiceInterface;
use App\Interface\RoleManagement\UserRepositoryInterface;
use App\Interface\RoleManagement\UserServiceInterface;
use App\Interface\Project\ProjectServiceInterface;
use App\Interface\Setting\ProfileRepositoryInterface;
use App\Repositories\RoleManagement\PermissionRepository;
use App\Repositories\RoleManagement\RoleRepository;
use App\Repositories\RoleManagement\UserRepository;
use App\Repositories\Setting\ProfileRepository;
use App\Services\Project\ProjectService;
use App\Services\RoleManagement\PermissionService;
use App\Services\RoleManagement\RoleService;
use App\Services\RoleManagement\UserService;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
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
        $this->app->bind(RoleServiceInterface::class, RoleService::class);

        // permission
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
        $this->app->bind(PermissionServiceInterface::class, PermissionService::class);

        // users
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);

        // profile
        $this->app->bind(ProfileRepositoryInterface::class, ProfileRepository::class);

        // project
        $this->app->bind(ProjectServiceInterface::class, ProjectService::class);

        // dokumen
        $this->app->bind(\App\Interface\Dokumen\DokumenRepositoryInterface::class, \App\Repositories\Dokumen\DokumenRepository::class);
        $this->app->bind(\App\Interface\Dokumen\DokumenServiceInterface::class, \App\Services\Dokumen\DokumenService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Memodifikasi tampilan email Reset Password
        ResetPassword::toMailUsing(function (object $notifiable, string $token) {

            // 1. Generate URL link reset bawaan Laravel
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            // 2. Arahkan ke custom view yang sudah kita buat tadi
            return (new MailMessage)
                ->subject('Permintaan Reset Password') // Ubah subjek email sesuai selera
                ->view('emails.custom-reset-password', [
                    'url' => $url,
                    'notifiable' => $notifiable,
                ]);
        });
    }
}
