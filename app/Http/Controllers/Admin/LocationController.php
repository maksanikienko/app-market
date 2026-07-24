<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Services\LocationService;
use Illuminate\Http\JsonResponse;
use Spatie\RouteDiscovery\Attributes\Route;

#[Route(middleware: ['auth', 'role:admin'])]
class LocationController extends Controller
{
    public function __construct(private readonly LocationService $locationService) {}

    #[Route(method: 'GET', fullUri: 'admin/locations', name: 'api.admin.locations.index')]
    public function index(): JsonResponse
    {
        try {
            return response()->json(Location::where('is_active', true)->orderBy('type')->orderBy('name')->get());
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }

    #[Route(method: 'GET', fullUri: 'admin/stock', name: 'api.admin.stock')]
    public function stock(): JsonResponse
    {
        try {
            return response()->json($this->locationService->getStockReport());
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }
}
