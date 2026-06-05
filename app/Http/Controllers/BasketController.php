<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BasketController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $orderId = session('orderId');
            $order   = $orderId ? Order::with('products')->find($orderId) : null;

            return response()->json(['success' => true, 'order' => $order]);
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }

    public function add(Request $request, $productId): JsonResponse
    {
        try {
            $orderId = session('orderId');

            if (!$orderId) {
                $order = Order::create();
                session(['orderId' => $order->id]);
            } else {
                $order = Order::findOrFail($orderId);
            }

            $variantData = array_filter(
                $request->only(['variant_id', 'color', 'color_hex', 'size']),
                fn($v) => $v !== null && $v !== ''
            );

            if ($order->products->contains($productId)) {
                $pivotRow = $order->products()->where('product_id', $productId)->first()->pivot;
                $order->products()->updateExistingPivot($productId, array_merge(
                    ['count' => $pivotRow->count + 1],
                    $variantData
                ));
            } else {
                $order->products()->attach($productId, array_merge(['count' => 1], $variantData));
            }

            if (Auth::check()) {
                $order->user_id = Auth::id();
                $order->save();
            }

            $product = Product::findOrFail($productId);

            return response()->json([
                'success' => true,
                'message' => $product->name . ' added to basket',
                'order'   => $order->load('products'),
            ]);
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }

    public function remove($productId): JsonResponse
    {
        try {
            $orderId = session('orderId');
            if (!$orderId) {
                return response()->json(['success' => false, 'message' => 'Basket not found'], 404);
            }

            $order = Order::findOrFail($orderId);

            if (!$order->products->contains($productId)) {
                return response()->json(['success' => false, 'message' => 'Product not in basket'], 404);
            }

            $pivotRow = $order->products()->where('product_id', $productId)->first()->pivot;

            if ($pivotRow->count < 2) {
                $order->products()->detach($productId);
            } else {
                $pivotRow->count--;
                $pivotRow->update();
            }

            $product = Product::findOrFail($productId);

            return response()->json([
                'success' => true,
                'message' => $product->name . ' removed from basket',
                'order'   => $order->load('products'),
            ]);
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }

    public function place(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name'  => ['nullable', 'string', 'max:255'],
                'phone' => ['nullable', 'string', 'max:50'],
            ]);

            $orderId = session('orderId');

            if ($orderId) {
                $order = Order::with('products')->find($orderId);
            } elseif (Auth::check()) {
                $order = Auth::user()->basket()->with('products')->first();
            } else {
                $order = null;
            }

            if (!$order || $order->products->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'Cart is empty'], 422);
            }

            if ($order->status !== 0) {
                return response()->json(['success' => false, 'message' => 'Order already placed'], 422);
            }

            $order->saveOrder(
                $request->input('name', ''),
                $request->input('phone', '')
            );

            return response()->json([
                'success'  => true,
                'message'  => 'Order placed successfully!',
                'order_id' => $order->id,
            ]);
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }

    public function update(Request $request): JsonResponse
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false], 401);
            }

            $order = Auth::user()->basket()->firstOrCreate(['status' => 0]);
            $order->products()->updateExistingPivot($request->product_id, ['count' => $request->quantity]);

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }
}
