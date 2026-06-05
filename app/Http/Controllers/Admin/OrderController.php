<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $orders = Order::with(['products', 'products.media'])
                ->where('status', 1)
                ->latest()
                ->get();

            return response()->json($orders);
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }

    public function show(Order $order): JsonResponse
    {
        try {
            return response()->json($order->load(['products', 'products.media']));
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }
}
