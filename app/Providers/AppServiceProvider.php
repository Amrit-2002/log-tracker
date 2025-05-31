<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;

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
        // VerifyEmail::createUrlUsing(function (object $notifiable, string $url) {
        //     return $url;
        // });
        // VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
        //     return (new MailMessage)
        //         // ->subject('Verify Email Address')
        //         // ->line('Click the button below to verify your email address.')
        //         ->action('Verify Email Address', $url);
        // }); 

        // ResetPassword::createUrlUsing(function (User $user, string $token) {
        //     return 'http://127.0.0.1:8000/reset-password/'.$token;
        // });
    }

}
