<?php

namespace App\Providers;

use App\Interface\RoleManagement\PermissionRepositoryInterface;
use App\Interface\RoleManagement\RoleRepositoryInterface;
use App\Interface\RoleManagement\UserRepositoryInterface;
use App\Repositories\RoleManagement\PermissionRepository;
use App\Repositories\RoleManagement\RoleRepository;
use App\Repositories\RoleManagement\UserRepository;
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
