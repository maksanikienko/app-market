<?php

namespace App\Services;

use App\Models\User;
use App\Repository\OrderRepository;
use Illuminate\Database\Eloquent\Collection;

class ProfileService
{
    public function __construct(public OrderRepository $orderRepository) {}

    public function getUserOrders(User $user): Collection
    {
        return $this->orderRepository->getUserOrders($user->id);
    }
}
