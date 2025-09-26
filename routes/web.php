<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome'); // Ou redirecione para uma página padrão
});

Route::get('/r/{code}', function ($code) {
    \Log::info("Web route: Attempting to redirect for code {$code}");
    $redirect = \App\Models\Redirect::findByCode($code);
    if ($redirect && $redirect->is_active) {
        \Log::info("Redirect found: ID {$redirect->id}, URL {$redirect->destination_url}");
        $redirect->updateLastAccessed();
        $url = $redirect->destination_url;
        if (request()->query()) {
            $url .= (parse_url($url, PHP_URL_QUERY) ? '&' : '?') . http_build_query(request()->query());
        }
        return redirect()->to($url);
    }
    \Log::warning("Redirect not found or inactive for code {$code}");
    abort(404);
});
