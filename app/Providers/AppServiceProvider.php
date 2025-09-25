<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Redirect;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Validator;

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
    public function boot()
    {

        Validator::extend('https', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^https:\/\//', $value);
        });

        Validator::replacer('https', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':attribute', $attribute, 'The :attribute must be an HTTPS URL.');
        });

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
