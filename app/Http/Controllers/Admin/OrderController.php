<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(\Illuminate\Http\Request $request): \Illuminate\Http\JsonResponse
    {
        $orders = Order::with('products')->where('status', 1)->latest()->get();

        return response()->json($orders);
    }
    public function show(\Illuminate\Http\Request $request, Order $order)
    {
        return response()->json($order);
    }
}
