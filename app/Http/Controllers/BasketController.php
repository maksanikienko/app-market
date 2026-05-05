<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class BasketController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $orderId = session('orderId');
            $order = $orderId ? Order::with('products')->find($orderId) : null;

            return response()->json([
                'success' => true,
                'order' => $order,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get basket',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function add($productId): JsonResponse
    {
        try {
            $orderId = session('orderId');

            if (!$orderId) {
                $order = Order::create();
                session(['orderId' => $order->id]);
            } else {
                $order = Order::findOrFail($orderId);
            }

            if ($order->products->contains($productId)) {
                $pivotRow = $order->products()->where('product_id', $productId)->first()->pivot;
                $pivotRow->count++;
                $pivotRow->update();
            } else {
                $order->products()->attach($productId);
            }

            if (Auth::check()) {
                $order->user_id = Auth::id();
                $order->save();
            }

            $product = Product::findOrFail($productId);

            return response()->json([
                'success' => true,
                'message' => $product->name . ' added to basket',
                'order' => $order->load('products')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add product to basket',
                'error' => $e->getMessage(),
            ], 500);
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

            if ($order->products->contains($productId)) {
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
                    'order' => $order->load('products')
                ]);
            }

            return response()->json(['success' => false, 'message' => 'Product not in basket'], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove product from basket',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function place(Request $request): JsonResponse
    {
        $request->validate([
            'name'  => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
        ]);

        $orderId = session('orderId');

        // Guests must have an active session order; auth users can use their basket relation
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
    }

    public function update(Request $request): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false
            ], 401);
        }

        $order = Auth::user()->basket()->firstOrCreate([
            'status' => 0
        ]);

        $order->products()->updateExistingPivot(
            $request->product_id,
            ['count' => $request->quantity]
        );

        return response()->json([
            'success' => true
        ]);
    }

//    public function basketRemoveAll($productId): JsonResponse
//    {
//        try {
//            $orderId = session('orderId');
//            if (!$orderId) {
//                return response()->json(['success' => false, 'message' => 'Basket not found'], 404);
//            }
//
//            $order = Order::findOrFail($orderId);
//
//            if ($order->products->contains($productId)) {
//                $order->products()->detach($productId);
//                $product = Product::findOrFail($productId);
//
//                return response()->json([
//                    'success' => true,
//                    'message' => $product->name . ' completely removed from basket',
//                    'order' => $order->load('products')
//                ]);
//            }
//
//            return response()->json(['success' => false, 'message' => 'Product not in basket'], 404);
//        } catch (\Exception $e) {
//            return response()->json([
//                'success' => false,
//                'message' => 'Failed to remove product from basket',
//                'error' => $e->getMessage(),
//            ], 500);
//        }
//    }
//
//    public function basketConfirm(Request $request): JsonResponse
//    {
//        try {
//            $orderId = session('orderId');
//            if (!$orderId) {
//                return response()->json(['success' => false, 'message' => 'Basket not found'], 404);
//            }
//
//            $order = Order::findOrFail($orderId);
//
//            $success = $order->saveOrder($request->name, $request->phone);
//
//            if ($success) {
//                session()->forget('orderId');
//                return response()->json([
//                    'success' => true,
//                    'message' => 'Your order is confirmed!'
//                ]);
//            }
//
//            return response()->json([
//                'success' => false,
//                'message' => 'Order processing error'
//            ]);
//        } catch (\Exception $e) {
//            return response()->json([
//                'success' => false,
//                'message' => 'Failed to confirm order',
//                'error' => $e->getMessage(),
//            ], 500);
//        }
//    }
}