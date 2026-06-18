<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // hanya paksa https jika bukan localhost
        if (!app()->environment('local')) {
            URL::forceScheme('https');
        }

        RedirectIfAuthenticated::redirectUsing(function ($request) {

            if (auth()->check()) {

                if (!session('otp_verified')) {
                    return route('otp.form');
                }

                $user = auth()->user();

                if ($user->role === 1) {
                    return route('admin.dashboard');
                }

                if ($user->role === 2) {
                    return route('petugas.dashboard');
                }

                if ($user->role === 3) {
                    return route('user.dashboard');
                }

                if ($user->role === 4) {
                    return route('vendor.dashboard');
                }

                return route('user.dashboard');
            }

            return '/';
        });
    }

    /**
     * Bootstrap any application services.
     */
    // public function boot(): void
    // {
    //     URL::forceScheme('https');

    //     // Redirect already-authenticated visitors away from login/register pages.
    //     // If user has completed OTP, send to their dashboard; otherwise send to OTP form.
    //     RedirectIfAuthenticated::redirectUsing(function ($request) {
    //         if (auth()->check()) {
    //             if (!session('otp_verified')) {
    //                 return route('otp.form');
    //             }

    //             // choose dashboard based on role
    //             $user = auth()->user();

    //             if ($user->role === 1) {
    //                 return route('admin.dashboard');
    //             }

    //             if ($user->role === 2) {
    //                 return route('petugas.dashboard');
    //             }

    //             if ($user->role === 3) {
    //                 return route('user.dashboard');
    //             }

    //             if ($user->role === 4) {
    //                 return route('vendor.dashboard');
    //             }
    //             return route('user.dashboard');
    //         }

    //         return '/';
    //     });
    // }
}
