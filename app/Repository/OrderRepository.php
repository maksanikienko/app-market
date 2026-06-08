<?php

namespace App\Repository;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

class OrderRepository
{
    public function getUserOrders(int $userId): Collection
    {
        return Order::with(['products.category', 'products.media'])
            ->where('user_id', $userId)
            ->where('status', 1)
            ->latest()
            ->get();
    }
}
