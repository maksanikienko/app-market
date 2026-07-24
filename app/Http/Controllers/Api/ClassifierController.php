<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ClassifierService;
use Illuminate\Http\JsonResponse;
use Spatie\RouteDiscovery\Attributes\Route;

class ClassifierController extends Controller
{
    public function __construct(private readonly ClassifierService $classifierService) {}

    #[Route(method: 'GET', name: 'api.products.classifiers.index')]
    public function index(): JsonResponse
    {
        try {
            return response()->json($this->classifierService->getGroupedClassifiers());
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }
}
