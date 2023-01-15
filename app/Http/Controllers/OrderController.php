<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    public function index($id)
    {
        $order = Order::findOrFail($id);

        $orderItems = OrderItem::where('order_id', $id)->get()->fresh('product');

        return view('order')
            ->with('order', $order)
            ->with('orderItems', $orderItems);
    }

    public function list()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $order = Order::where('user_id', $user->id)->get();

        return view('orders')
            ->with('orders', $order);
    }
}
