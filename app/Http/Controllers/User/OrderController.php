<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Auth::user()->orders()->where('status', 1)->get();
        return view('auth.orders.index', compact('orders'));
    }
    public function show(Order $order)
    {
        if (!Auth::user()->orders->contains($order)) {
            return back();
        }
        return view('auth.orders.show', compact('order'));
    }
}
