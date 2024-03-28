<?php

namespace App\Http\Middleware;

use App\Models\Order;
use Closure;

class BasketIsNotEmpty
{
    /**
     * Handle an incoming request.
     * * @return mixed     */
    //Method check if the basket empty and redirect to index page
    public function handle($request, Closure $next): mixed
    {
        $orderId = session('orderId');

        if (!is_null($orderId)) {
            $order = Order::findOrFail($orderId);
            if ($order->products->count() > 0) {
                return $next($request);
            }
        }
            session()->flash('warning', 'Your shopping cart is empty!');
            return redirect()->route('index');

    }
}
