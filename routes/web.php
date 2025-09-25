<?php

use Illuminate\Support\Facades\Route;
use App\Models\Redirect;
use App\Models\RedirectLog;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Redirecionamento por code
Route::get('/r/{redirect}', function (Request $request, string $redirect) {
    $redirectModel = Redirect::findByCode($redirect);
    if (!$redirectModel || !$redirectModel->is_active || $redirectModel->trashed()) {
        abort(404);
    }

    // Log acesso
    RedirectLog::create([
        'redirect_id' => $redirectModel->id,
        'ip_address' => $request->ip(),
        'user_agent' => $request->userAgent(),
        'referer' => $request->header('referer'),
        'query_params' => json_encode($request->query()),  // Query params como JSON
        'accessed_at' => now(),
    ]);

    // Atualiza último acesso
    $redirectModel->updateLastAccessed();

    // Merge query params
    $parsedUrl = parse_url($redirectModel->destination_url);
    $baseUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . ($parsedUrl['path'] ?? '');
    parse_str($parsedUrl['query'] ?? '', $redirectParams);  // Params do redirect
    $requestParams = $request->query();  // Params da request

    // Ignora chaves vazias na request
    $requestParams = array_filter($requestParams, fn($v) => $v !== '');

    // Merge: request prioriza
    $mergedParams = array_merge($redirectParams, $requestParams);

    // Constroi URL final
    $finalUrl = $baseUrl;
    if (!empty($mergedParams)) {
        $finalUrl .= '?' . http_build_query($mergedParams);
    }

    return redirect($finalUrl);
})->where('redirect', '[A-Za-z0-9]+');  // Regex para code
