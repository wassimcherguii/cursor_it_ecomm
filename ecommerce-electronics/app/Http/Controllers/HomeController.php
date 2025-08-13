<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    /**
     * Display the homepage with featured products and categories.
     */
    public function index()
    {
        // Get featured products (products on sale)
        $featuredProducts = Product::active()
            ->inStock()
            ->whereNotNull('sale_price')
            ->with('category')
            ->limit(8)
            ->get();

        // Get latest products
        $latestProducts = Product::active()
            ->inStock()
            ->with('category')
            ->latest()
            ->limit(8)
            ->get();

        // Get all active categories
        $categories = Category::active()
            ->withCount('activeProducts')
            ->having('active_products_count', '>', 0)
            ->get();

        return view('home', compact('featuredProducts', 'latestProducts', 'categories'));
    }
}
