<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\RouteDiscovery\Attributes\Route;

class ProfileController extends Controller
{
    public function __construct(public ProfileService $profileService) {}

    #[Route(method: 'GET', name: 'api.profile.orders', middleware: 'auth')]
    public function orders(Request $request): JsonResponse
    {
        try {
            return response()->json($this->profileService->getUserOrders($request->user()));
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }
}
