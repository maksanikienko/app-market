<?php

namespace App\Http\Controllers;

use App\Http\Requests\BasketAddRequest;
use App\Http\Requests\BasketUpdateRequest;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\RouteDiscovery\Attributes\Route;

class BasketController extends Controller
{
    #[Route(method: 'get', name: 'api.basket.index')]
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

    #[Route(method: 'POST', uri: 'add/{id}', name: 'api.basket-add')]
    public function add(BasketAddRequest $request, $productId): JsonResponse
    {
        try {
            $orderId = session('orderId');

            if (!$orderId) {
                $order = Order::create();
                session(['orderId' => $order->id]);
            } else {
                $order = Order::findOrFail($orderId);
            }

            $variantData = $request->variantData();
            $quantity    = $request->quantity();

            if ($order->products->contains($productId)) {
                $pivotRow = $order->products()->where('product_id', $productId)->first()->pivot;
                $order->products()->updateExistingPivot($productId, array_merge(
                    ['count' => $pivotRow->count + $quantity],
                    $variantData
                ));
            } else {
                $order->products()->attach($productId, array_merge(['count' => $quantity], $variantData));
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

    #[Route(method: 'POST', uri: 'remove/{id}', name: 'api.basket-remove')]
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

    #[Route(method: 'POST', name: 'api.basket-place')]
    public function place(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name'  => ['nullable', 'string', 'max:255'],
                'phone' => ['nullable', 'string', 'max:50'],
            ]);

            $order = $this->currentBasket();

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

    #[Route(method: 'POST', uri: 'update', name: 'api.basket-update')]
    public function update(BasketUpdateRequest $request): JsonResponse
    {
        try {
            $order     = $this->currentBasket();
            $productId = $request->integer('product_id');

            if (!$order || !$order->products->contains($productId)) {
                return response()->json(['success' => false, 'message' => 'Product not in basket'], 404);
            }

            $order->products()->updateExistingPivot($productId, ['count' => $request->integer('quantity')]);

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            return $this->handleError($e);
        }
    }

    // Guest basket lives in the session; a logged-in user without one falls back to their saved basket.
    private function currentBasket(): ?Order
    {
        $orderId = session('orderId');

        if ($orderId) {
            return Order::with('products')->find($orderId);
        }

        return Auth::check() ? Auth::user()->basket()->with('products')->first() : null;
    }
}
