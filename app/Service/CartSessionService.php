<?php

namespace App\Service;

use App\Models\Product;
use Illuminate\Session\Store;

class CartSessionService
{
    const MINIMUM_QUANTITY = 1;
    const DEFAULT_INSTANCE = 'cart';

    private Store $store;

    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    private function loadCart()
    {
        return $this->store->get(self::DEFAULT_INSTANCE, []);
    }

    private function saveCart(array $cart)
    {
        $this->store->put(self::DEFAULT_INSTANCE, $cart);
    }

    public function get()
    {
        return $this->loadCart();
    }

    public function clear()
    {
        $this->saveCart([]);
    }

    public function addItem(Product $product, int $quantity = 1)
    {
        if ($quantity < self::MINIMUM_QUANTITY) {
            throw new \Exception("Quantity must be greater than or equal to " . self::MINIMUM_QUANTITY);
        }

        $cart = $this->loadCart();

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            $cart[$product->id] = [
                "name" => $product->name,
                "quantity" => $quantity,
                "price" => $product->price,
                "image" => $product->image
            ];
        }

        $this->saveCart($cart);
    }

    public function removeItem(Product $product)
    {
        $cart = $this->loadCart();

        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);
        }

        $this->saveCart($cart);
    }

    public function updateItem(Product $product, int $quantity) {
        $cart = $this->loadCart();

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] = $quantity;
        }

        $this->saveCart($cart);
    }
}
