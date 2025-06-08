<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Kreait\Firebase\Factory;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('firebase', function ($app) {
            $credentialsPath = storage_path('app/firebase_credentials.json');
            return (new Factory)
                ->withServiceAccount($credentialsPath)
                ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));
        });

        $this->app->singleton('firebase.auth', function ($app) {
            return $app->make('firebase')->createAuth();
        });

        $this->app->singleton('firebase.database', function ($app) {
            return $app->make('firebase')->createDatabase();
        });
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (session()->has('firebase_uid') && !View::shared('user')) {
                $uid = session('firebase_uid');
                try {
                    $user = (object) app('firebase.database')->getReference('users/' . $uid)->getValue();
                    View::share('user', $user);
                } catch (\Exception $e) {
                    View::share('user', null);
                }
            }
        });
    }
}