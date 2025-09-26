<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Redirect;
use App\Models\RedirectLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RedirectController extends Controller
{
    public function index()
    {
        Log::info("RedirectController: index method called");
        return Redirect::all()->map(function ($redirect) {
            return [
                'code' => $redirect->code,
                'destination_url' => $redirect->destination_url,
                'is_active' => $redirect->is_active,
                'created_at' => $redirect->created_at,
                'updated_at' => $redirect->updated_at,
            ];
        });
    }

    public function store(Request $request)
    {
        Log::info("RedirectController: store method called with data: " . json_encode($request->all()));
        $redirect = Redirect::create($request->only('destination_url', 'is_active'));
        Log::info("Created redirect with code: {$redirect->code}");
        return response()->json($redirect, 201);
    }

    public function show($code)
    {
        Log::info("RedirectController: show method called for code: {$code}");
        $redirect = Redirect::findByCode($code);
        if (!$redirect) {
            return response()->json(['error' => 'Not found'], 404);
        }
        return response()->json($redirect);
    }

    public function update(Request $request, $code)
    {
        Log::info("RedirectController: update method called for code: {$code}");
        $redirect = Redirect::findByCode($code);
        if (!$redirect) {
            return response()->json(['error' => 'Not found'], 404);
        }
        $redirect->update($request->only('destination_url', 'is_active'));
        return response()->json($redirect);
    }

    public function destroy($code)
    {
        Log::info("RedirectController: destroy method called for code: {$code}");
        $redirect = Redirect::findByCode($code);
        if (!$redirect) {
            return response()->json(['error' => 'Not found'], 404);
        }
        $redirect->delete();
        return response()->json(null, 204);
    }

    public function stats($code)
    {
        Log::info("RedirectController: stats method called for code: {$code}");
        $redirect = Redirect::findByCode($code);
        if (!$redirect) {
            Log::warning("Stats: Redirect not found for code: {$code}");
            return response()->json(['error' => 'Not found'], 404);
        }
        Log::info("Stats: Found redirect with id: {$redirect->id}, code: {$redirect->code}");
        $logs = $redirect->logs();
        $totalAccesses = $logs->count();
        $lastAccess = $logs->max('accessed_at');
        $topReferers = RedirectLog::select('referer', DB::raw('count(*) as count'))
            ->where('redirect_id', $redirect->id)
            ->groupBy('referer')
            ->orderByDesc('count')
            ->limit(5)
            ->get();
        return response()->json([
            'code' => $redirect->code,
            'total_accesses' => $totalAccesses,
            'last_access' => $lastAccess,
            'top_referers' => $topReferers->map(function ($item) {
                return ['referer' => $item->referer, 'count' => $item->count];
            })->values(),
        ]);
    }
}
