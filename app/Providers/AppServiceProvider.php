<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Redirect;
use Illuminate\Routing\Router;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        $router = $this->app->make(Router::class);
        $router->bind('redirect', function ($value) {  // Bind 'redirect' param para buscar por code
            $redirect = Redirect::findByCode($value);
            if (!$redirect || $redirect->trashed()) {  // Considera soft delete
                abort(404);
            }
            return $redirect;
        });
    }
}
