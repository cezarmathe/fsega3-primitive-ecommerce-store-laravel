<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Service\CartSessionService;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = new CartSessionService($request->session());

        return view('cart')->with('cart', $cart->get());
    }

    public function addItem(Request $request)
    {
        if (!$request->id) {
            return redirect()->back()->with('error', 'Product not found');
        }

        $product = Product::findOrFail($request->id);

        $cart = new CartSessionService($request->session());
        $cart->addItem($product, 1);

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    public function update(Request $request)
    {
        if ($request->id && $request->quantity) {
            $product = Product::findOrFail($request->id);

            $cart = new CartSessionService($request->session());
            $cart->updateItem($product, $request->quantity);

            $request->session()->flash('success', 'Cart updated successfully');
        }
    }

    public function removeItem(Request $request)
    {
        if ($request->id) {
            $product = Product::findOrFail($request->id);

            $cart = new CartSessionService($request->session());
            $cart->removeItem($product);

            $request->session()->flash('success', 'Product removed successfully');
        }
    }
    public function checkout(Request $request)
    {
        $cart = new CartSessionService($request->session());

        if (!$cart->get()) {
            return redirect()->route('cart:index');
        }

        $orderID = DB::transaction(function () use ($cart) {
            $orderTotal = array_sum(array_map(function ($item) {
                return $item['quantity'] * $item['price'];
            }, $cart->get()));

            $order = Order::create([
                'user_id' => auth()->id(),
                'total' => $orderTotal,
            ]);

            foreach ($cart->get() as $id => $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            return $order->id;
        });

        $cart->clear();

        return redirect()->route("order:get", ["id" => $orderID]);
    }
}
