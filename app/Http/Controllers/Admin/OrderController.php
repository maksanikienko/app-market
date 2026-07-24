<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Spatie\RouteDiscovery\Attributes\Route;

#[Route(middleware: ['auth', 'role:admin'])]
class OrderController extends Controller
{
    private const RELATIONS = ['products', 'products.media'];

    #[Route(method: 'GET', fullUri: 'admin/orders', name: 'api.admin.orders.index')]
    public function index(): JsonResponse
    {
        try {
            $orders = Order::with(self::RELATIONS)
                ->where('status', 1)
                ->latest()
                ->get();

            return response()->json($orders);
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }

    #[Route(method: 'GET', fullUri: 'admin/orders/{order}', name: 'api.admin.orders.show')]
    public function show(Order $order): JsonResponse
    {
        try {
            return response()->json($order->load(self::RELATIONS));
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }
}
