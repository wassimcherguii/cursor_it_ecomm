<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Display the checkout form.
     */
    public function index()
    {
        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $cartItems = collect();
        $cartTotal = 0;
        $productIds = array_keys($cart);
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        foreach ($cart as $productId => $item) {
            if ($products->has($productId)) {
                $product = $products[$productId];
                
                // Check if product is still available
                if (!$product->isAvailable()) {
                    Session::forget("cart.{$productId}");
                    continue;
                }
                
                // Check stock availability
                if ($product->manage_stock && $item['quantity'] > $product->stock_quantity) {
                    Session::put("cart.{$productId}.quantity", $product->stock_quantity);
                    $item['quantity'] = $product->stock_quantity;
                }

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

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty or contains unavailable items.');
        }

        $user = Auth::user();
        $taxRate = 0.08; // 8% tax rate
        $taxAmount = $cartTotal * $taxRate;
        $shippingAmount = $cartTotal > 100 ? 0 : 15.99; // Free shipping over $100
        $totalAmount = $cartTotal + $taxAmount + $shippingAmount;

        return view('checkout.index', compact(
            'cartItems', 
            'cartTotal', 
            'taxAmount', 
            'shippingAmount', 
            'totalAmount', 
            'user'
        ));
    }

    /**
     * Process the checkout and create order.
     */
    public function store(Request $request)
    {
        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $request->validate([
            'shipping_first_name' => 'required|string|max:255',
            'shipping_last_name' => 'required|string|max:255',
            'shipping_email' => 'required|email|max:255',
            'shipping_phone' => 'nullable|string|max:20',
            'shipping_address_line_1' => 'required|string|max:255',
            'shipping_address_line_2' => 'nullable|string|max:255',
            'shipping_city' => 'required|string|max:100',
            'shipping_state' => 'required|string|max:100',
            'shipping_postal_code' => 'required|string|max:20',
            'shipping_country' => 'required|string|max:100',
            'billing_same_as_shipping' => 'boolean',
            'billing_first_name' => 'required_if:billing_same_as_shipping,false|string|max:255',
            'billing_last_name' => 'required_if:billing_same_as_shipping,false|string|max:255',
            'billing_email' => 'required_if:billing_same_as_shipping,false|email|max:255',
            'billing_phone' => 'nullable|string|max:20',
            'billing_address_line_1' => 'required_if:billing_same_as_shipping,false|string|max:255',
            'billing_address_line_2' => 'nullable|string|max:255',
            'billing_city' => 'required_if:billing_same_as_shipping,false|string|max:100',
            'billing_state' => 'required_if:billing_same_as_shipping,false|string|max:100',
            'billing_postal_code' => 'required_if:billing_same_as_shipping,false|string|max:20',
            'billing_country' => 'required_if:billing_same_as_shipping,false|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            // Calculate totals
            $cartTotal = 0;
            $productIds = array_keys($cart);
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

            foreach ($cart as $productId => $item) {
                if ($products->has($productId)) {
                    $product = $products[$productId];
                    $cartTotal += $product->effective_price * $item['quantity'];
                }
            }

            $taxRate = 0.08;
            $taxAmount = $cartTotal * $taxRate;
            $shippingAmount = $cartTotal > 100 ? 0 : 15.99;
            $totalAmount = $cartTotal + $taxAmount + $shippingAmount;

            // Set billing address same as shipping if requested
            if ($request->billing_same_as_shipping) {
                $billingData = [
                    'billing_first_name' => $request->shipping_first_name,
                    'billing_last_name' => $request->shipping_last_name,
                    'billing_email' => $request->shipping_email,
                    'billing_phone' => $request->shipping_phone,
                    'billing_address_line_1' => $request->shipping_address_line_1,
                    'billing_address_line_2' => $request->shipping_address_line_2,
                    'billing_city' => $request->shipping_city,
                    'billing_state' => $request->shipping_state,
                    'billing_postal_code' => $request->shipping_postal_code,
                    'billing_country' => $request->shipping_country,
                ];
            } else {
                $billingData = $request->only([
                    'billing_first_name', 'billing_last_name', 'billing_email', 'billing_phone',
                    'billing_address_line_1', 'billing_address_line_2', 'billing_city',
                    'billing_state', 'billing_postal_code', 'billing_country'
                ]);
            }

            // Create order
            $order = Order::create(array_merge([
                'user_id' => Auth::id(),
                'subtotal' => $cartTotal,
                'tax_amount' => $taxAmount,
                'shipping_amount' => $shippingAmount,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'notes' => $request->notes,
            ], $request->only([
                'shipping_first_name', 'shipping_last_name', 'shipping_email', 'shipping_phone',
                'shipping_address_line_1', 'shipping_address_line_2', 'shipping_city',
                'shipping_state', 'shipping_postal_code', 'shipping_country'
            ]), $billingData));

            // Create order items and update stock
            foreach ($cart as $productId => $item) {
                if ($products->has($productId)) {
                    $product = $products[$productId];
                    
                    // Check stock availability one more time
                    if ($product->manage_stock && $item['quantity'] > $product->stock_quantity) {
                        throw new \Exception("Insufficient stock for {$product->name}");
                    }

                    // Create order item
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'unit_price' => $product->effective_price,
                        'total_price' => $product->effective_price * $item['quantity'],
                        'product_name' => $product->name,
                        'product_sku' => $product->sku,
                        'product_details' => [
                            'brand' => $product->brand,
                            'specifications' => $product->specifications,
                        ]
                    ]);

                    // Update stock if managed
                    if ($product->manage_stock) {
                        $product->decrement('stock_quantity', $item['quantity']);
                        
                        // Mark as out of stock if needed
                        if ($product->stock_quantity <= 0) {
                            $product->update(['in_stock' => false]);
                        }
                    }
                }
            }

            DB::commit();

            // Clear cart
            Session::forget('cart');

            return redirect()->route('checkout.success', $order)->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Order could not be processed: ' . $e->getMessage());
        }
    }

    /**
     * Display order success page.
     */
    public function success(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('orderItems.product');

        return view('checkout.success', compact('order'));
    }

    /**
     * Display user orders.
     */
    public function orders()
    {
        $orders = Auth::user()->orders()
            ->with('orderItems')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Display a specific order.
     */
    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('orderItems.product');

        return view('orders.show', compact('order'));
    }
}
