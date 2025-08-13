<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index()
    {
        $categories = Category::active()
            ->withCount('activeProducts')
            ->having('active_products_count', '>', 0)
            ->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Display the specified category and its products.
     */
    public function show(Request $request, Category $category)
    {
        if (!$category->is_active) {
            abort(404, 'Category not found');
        }

        $query = $category->activeProducts();

        // Search within category
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('brand', 'like', "%{$searchTerm}%");
            });
        }

        // Brand filter
        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        // Price range filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sorting
        $sortBy = $request->get('sort', 'name');
        $sortOrder = $request->get('order', 'asc');
        
        switch ($sortBy) {
            case 'price':
                $query->orderBy('price', $sortOrder);
                break;
            case 'name':
                $query->orderBy('name', $sortOrder);
                break;
            case 'created_at':
                $query->orderBy('created_at', $sortOrder);
                break;
            default:
                $query->orderBy('name', 'asc');
        }

        $products = $query->paginate(12)->withQueryString();

        // Get brands available in this category
        $brands = Product::active()
            ->where('category_id', $category->id)
            ->distinct()
            ->pluck('brand')
            ->filter()
            ->sort();

        return view('categories.show', compact('category', 'products', 'brands'));
    }
}
