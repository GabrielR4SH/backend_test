<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Redirect;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Validator::extend('https', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^https:\/\//', $value);
        });

        Validator::replacer('https', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':attribute', $attribute, 'The :attribute must be an HTTPS URL.');
        });

        // $router = $this->app->make(Router::class);
        // $router->bind('redirect', function ($value) {
        //     $redirect = Redirect::findByCode($value);
        //     if (!$redirect) {
        //         abort(404);
        //     }
        //     return $redirect;
        // });
    }
}
