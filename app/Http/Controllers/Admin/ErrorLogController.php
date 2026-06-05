<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ErrorLogController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Activity::query()->where('log_name', 'error');

            if ($search = $request->input('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('description', 'like', "%{$search}%")
                      ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(properties, '$.exception')) LIKE ?", ["%{$search}%"])
                      ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(properties, '$.message')) LIKE ?", ["%{$search}%"])
                      ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(properties, '$.url')) LIKE ?", ["%{$search}%"]);
                });
            }

            if ($from = $request->input('date_from')) {
                $query->whereDate('created_at', '>=', $from);
            }

            if ($to = $request->input('date_to')) {
                $query->whereDate('created_at', '<=', $to);
            }

            $logs = $query->latest()->paginate(20);

            return response()->json($logs);
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }

    public function destroy(Activity $activity): JsonResponse
    {
        try {
            $activity->delete();
            return response()->json(null, 204);
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }
}
