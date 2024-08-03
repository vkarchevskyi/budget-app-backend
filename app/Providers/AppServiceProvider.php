<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // @phpstan-ignore-next-line
        ResetPassword::createUrlUsing(function (CanResetPassword $notifiable, string $token): string {
            return config('app.frontend_url')
                . "/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
    }
}
