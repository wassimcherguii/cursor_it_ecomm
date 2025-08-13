<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Display the shopping cart.
     */
    public function index()
    {
        $cart = $this->getCart();
        $cartItems = collect();
        $cartTotal = 0;

        if (!empty($cart)) {
            $productIds = array_keys($cart);
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

            foreach ($cart as $productId => $item) {
                if ($products->has($productId)) {
                    $product = $products[$productId];
                    $cartItem = (object) [
                        'id' => $productId,
                        'product' => $product,
                        'quantity' => $item['quantity'],
                        'price' => $product->effective_price,
                        'total' => $product->effective_price * $item['quantity']
                    ];
                    $cartItems->push($cartItem);
                    $cartTotal += $cartItem->total;
                }
            }
        }

        return view('cart.index', compact('cartItems', 'cartTotal'));
    }

    /**
     * Add a product to the cart.
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        $product = Product::findOrFail($request->product_id);

        if (!$product->isAvailable()) {
            return back()->with('error', 'Product is not available for purchase.');
        }

        if ($product->manage_stock && $request->quantity > $product->stock_quantity) {
            return back()->with('error', 'Requested quantity exceeds available stock.');
        }

        $cart = $this->getCart();
        $productId = $product->id;

        if (isset($cart[$productId])) {
            $newQuantity = $cart[$productId]['quantity'] + $request->quantity;
            
            if ($product->manage_stock && $newQuantity > $product->stock_quantity) {
                return back()->with('error', 'Cannot add more items. Stock limit exceeded.');
            }
            
            $cart[$productId]['quantity'] = $newQuantity;
        } else {
            $cart[$productId] = [
                'quantity' => $request->quantity,
                'added_at' => now()->timestamp
            ];
        }

        $this->saveCart($cart);

        return back()->with('success', 'Product added to cart successfully!');
    }

    /**
     * Update the quantity of a cart item.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        $product = Product::findOrFail($id);
        
        if ($product->manage_stock && $request->quantity > $product->stock_quantity) {
            return back()->with('error', 'Requested quantity exceeds available stock.');
        }

        $cart = $this->getCart();

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $request->quantity;
            $this->saveCart($cart);
            return back()->with('success', 'Cart updated successfully!');
        }

        return back()->with('error', 'Item not found in cart.');
    }

    /**
     * Remove a product from the cart.
     */
    public function remove($id)
    {
        $cart = $this->getCart();

        if (isset($cart[$id])) {
            unset($cart[$id]);
            $this->saveCart($cart);
            return back()->with('success', 'Item removed from cart.');
        }

        return back()->with('error', 'Item not found in cart.');
    }

    /**
     * Clear the entire cart.
     */
    public function clear()
    {
        Session::forget('cart');
        return back()->with('success', 'Cart cleared successfully.');
    }

    /**
     * Get the cart from session.
     */
    private function getCart()
    {
        return Session::get('cart', []);
    }

    /**
     * Save the cart to session.
     */
    private function saveCart($cart)
    {
        Session::put('cart', $cart);
    }

    /**
     * Get cart count for display in header.
     */
    public static function getCartCount()
    {
        $cart = Session::get('cart', []);
        return array_sum(array_column($cart, 'quantity'));
    }

    /**
     * Get cart total for display.
     */
    public static function getCartTotal()
    {
        $cart = Session::get('cart', []);
        $total = 0;

        if (!empty($cart)) {
            $productIds = array_keys($cart);
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

            foreach ($cart as $productId => $item) {
                if ($products->has($productId)) {
                    $product = $products[$productId];
                    $total += $product->effective_price * $item['quantity'];
                }
            }
        }

        return $total;
    }
}
