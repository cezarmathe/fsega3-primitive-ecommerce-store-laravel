<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $cart = Cart::where('user_id', $user->id)
            ->with('cart_items', 'cart_items.product')
            ->first();

        if (!$cart) {
            $cart = new Cart();
            $cart->user_id = $user->id;
            $cart->save();

            $cart->cart_items = [];
        }

        return view('cart')->with('cart', $cart);
    }

    public function addItem(Request $request)
    {
        if (!$request->id) {
            return redirect()->back()->with('error', 'Product not found');
        }

        $user = auth()->user();
        $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart) {
            $cart = new Cart();
            $cart->user_id = $user->id;
            $cart->save();
        }

        $product = Product::findOrFail($request->id);

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->get();

        if ($cartItem->count() > 0) {
            $cartItem = $cartItem->first();
            $cartItem->quantity += 1;
            $cartItem->save();
        } else {
            $cartItem = new CartItem();
            $cartItem->cart_id = $cart->id;
            $cartItem->product_id = $product->id;
            $cartItem->quantity = 1;
            $cartItem->save();
        }

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    public function update(Request $request)
    {
        if ($request->id && $request->quantity) {
            $user = auth()->user();
            $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart) {
            $cart = new Cart();
            $cart->user_id = $user->id;
            $cart->save();
        }

            $product = Product::findOrFail($request->id);

            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $product->id)
                ->get();

            if ($cartItem->count() > 0) {
                $cartItem = $cartItem->first();
                $cartItem->quantity = $request->quantity;
                $cartItem->save();
            }

            $request->session()->flash('success', 'Cart updated successfully');
        }
    }

    public function removeItem(Request $request)
    {
        if ($request->id) {
            $user = auth()->user();
            $cart = Cart::where('user_id', $user->id)->first();

        if (!$cart) {
            $cart = new Cart();
            $cart->user_id = $user->id;
            $cart->save();
        }

            $product = Product::findOrFail($request->id);

            CartItem::where('cart_id', $cart->id)
                ->where('product_id', $product->id)
                ->delete();

            $request->session()->flash('success', 'Product removed successfully');
        }
    }
    public function checkout()
    {
        $user = auth()->user();
        $cart = Cart::where('user_id', $user->id)
            ->get();

        $items = [];

        if (!$cart) {
            $cart = new Cart();
            $cart->user_id = $user->id;
            $cart->save();

            $items = [];
        } else {
            $items = CartItem::where('cart_id', $cart->id)
                ->with('product')
                ->get();
        }

        $orderID = DB::transaction(function () use ($cart, $items) {
            $orderTotal = array_sum(array_map(function ($item) {
                return $item['quantity'] * $item['product']['price'];
            }, $items));

            $order = Order::create([
                'user_id' => $cart->user_id,
                'total' => $orderTotal,
            ]);

            foreach ($items as $id => $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);
            }

            CartItem::where('cart_id', $cart->id)->delete();

            return $order->id;
        });

        return redirect()->route("order:get", ["id" => $orderID]);
    }
}
