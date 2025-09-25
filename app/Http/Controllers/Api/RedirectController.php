<?php  // Controller API com injeção (recebe Redirect via binding)

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRedirectRequest;
use App\Http\Requests\UpdateRedirectRequest;
use App\Models\Redirect;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class RedirectController extends Controller {

    // Listagem
    public function index(): JsonResponse {
        $redirects = Redirect::withTrashed(false)->get()->map(function ($redirect) {  // Sem soft deleted
            return [
                'code' => $redirect->code,
                'status' => $redirect->is_active ? 'active' : 'inactive',
                'destination_url' => $redirect->destination_url,
                'last_accessed_at' => $redirect->last_accessed_at,
                'created_at' => $redirect->created_at,
                'updated_at' => $redirect->updated_at,
            ];
        });
        return response()->json($redirects);
    }

    // Criação
    public function store(StoreRedirectRequest $request): JsonResponse {
        $redirect = Redirect::create($request->validated());
        return response()->json($redirect, 201);
    }

    // Show (injetado por code)
    public function show(Redirect $redirect): JsonResponse {
        return response()->json($redirect);
    }

    // Update
    public function update(UpdateRedirectRequest $request, Redirect $redirect): JsonResponse {
        $redirect->update($request->validated());
        return response()->json($redirect);
    }

    // Delete: soft delete + inativar
    public function destroy(Redirect $redirect): JsonResponse {
        $redirect->update(['is_active' => false]);
        $redirect->delete();
        return response()->json(null, 204);
    }

    // Stats
    public function stats(Redirect $redirect): JsonResponse {
        $logs = $redirect->logs();

        $totalAccesses = $logs->count();
        $uniqueAccesses = $logs->distinct('ip_address')->count('ip_address');
        $topReferers = $logs->select('referer', DB::raw('count(*) as count'))
                            ->groupBy('referer')
                            ->orderByDesc('count')
                            ->limit(5)  // Top 5 para performance
                            ->get();

        $last10Days = [];

        // Últimos 10 dias
        for ($i = 0; $i < 10; $i++) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dayLogs = $logs->whereDate('accessed_at', $date);
            $last10Days[] = [
                'date' => $date,
                'total' => $dayLogs->count(),
                'unique' => $dayLogs->distinct('ip_address')->count('ip_address'),
            ];
        }

        return response()->json([
            'total_accesses' => $totalAccesses,
            'unique_accesses' => $uniqueAccesses,
            'top_referers' => $topReferers,
            'last_10_days' => array_reverse($last10Days),  // Mais recente primeiro
        ]);
    }

    // Lista logs
    public function logs(Redirect $redirect): JsonResponse {
        return response()->json($redirect->logs()->paginate(20));  // Paginação para performance
    }
}
