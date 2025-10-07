<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\SettingGeneral;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
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
        // register
        Fortify::registerView(function () {
            return redirect('/');
        });

        //login
        Fortify::loginView(function () {
            // $setting = SettingGeneral::where('key', 'size_logo_login')->first();
            // if ($setting) {
            //     $logoSize = json_decode($setting->value)->value;
            // } else {
            //     $logoSize = 100;
            // }

            return view('auth.login', [
                'logoSize' => $logoSize ?? 100
            ]);
        });

        Fortify::authenticateUsing(function () {
            return null;
        });
    }
}
