@extends('layouts.app')

@section('title', 'Home')

@section('content')
<!-- Hero Section -->
<section class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-3">Your Electronics Destination</h1>
                <p class="lead mb-4">Discover the latest computers, phones, accessories, and cables at unbeatable prices. Quality products, fast shipping, and excellent customer service.</p>
                <a href="{{ route('products.index') }}" class="btn btn-light btn-lg">
                    <i class="fas fa-shopping-bag"></i> Shop Now
                </a>
            </div>
            <div class="col-lg-6">
                <div class="text-center">
                    <i class="fas fa-laptop fa-10x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Shop by Category</h2>
        <div class="row g-4">
            @foreach($categories->take(6) as $category)
            <div class="col-md-4 col-lg-2">
                <div class="text-center">
                    <a href="{{ route('categories.show', $category) }}" class="text-decoration-none">
                        <div class="bg-white p-4 rounded-3 shadow-sm h-100 d-flex flex-column justify-content-center category-card">
                            @switch($category->slug)
                                @case('computers-laptops')
                                    <i class="fas fa-laptop fa-3x text-primary mb-3"></i>
                                    @break
                                @case('smartphones-tablets')
                                    <i class="fas fa-mobile-alt fa-3x text-primary mb-3"></i>
                                    @break
                                @case('computer-components')
                                    <i class="fas fa-microchip fa-3x text-primary mb-3"></i>
                                    @break
                                @case('accessories')
                                    <i class="fas fa-headphones fa-3x text-primary mb-3"></i>
                                    @break
                                @case('cables-adapters')
                                    <i class="fas fa-plug fa-3x text-primary mb-3"></i>
                                    @break
                                @case('gaming')
                                    <i class="fas fa-gamepad fa-3x text-primary mb-3"></i>
                                    @break
                                @case('audio-video')
                                    <i class="fas fa-volume-up fa-3x text-primary mb-3"></i>
                                    @break
                                @case('storage')
                                    <i class="fas fa-hdd fa-3x text-primary mb-3"></i>
                                    @break
                                @default
                                    <i class="fas fa-box fa-3x text-primary mb-3"></i>
                            @endswitch
                            <h6 class="text-dark">{{ $category->name }}</h6>
                            <small class="text-muted">{{ $category->active_products_count }} products</small>
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('categories.index') }}" class="btn btn-outline-primary">View All Categories</a>
        </div>
    </div>
</section>

<!-- Featured Products Section -->
@if($featuredProducts->count() > 0)
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Featured Products</h2>
            <small class="text-muted">Special offers and deals</small>
        </div>
        <div class="row g-4">
            @foreach($featuredProducts->take(4) as $product)
            <div class="col-md-6 col-lg-3">
                <div class="card product-card h-100">
                    <div class="position-relative">
                        @if($product->images && count($product->images) > 0)
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-box fa-3x text-muted"></i>
                            </div>
                        @endif
                        @if($product->isOnSale())
                            <span class="badge bg-danger position-absolute top-0 start-0 m-2">Sale</span>
                        @endif
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title">{{ $product->name }}</h6>
                        <p class="card-text text-muted small flex-grow-1">{{ Str::limit($product->short_description, 80) }}</p>
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    @if($product->isOnSale())
                                        <span class="price sale-price">${{ number_format($product->sale_price, 2) }}</span>
                                        <small class="original-price ms-1">${{ number_format($product->price, 2) }}</small>
                                    @else
                                        <span class="price">${{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                                <small class="text-muted">{{ $product->category->name }}</small>
                            </div>
                            <div class="d-grid gap-2">
                                <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary btn-sm">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('products.index', ['sort' => 'created_at', 'order' => 'desc']) }}" class="btn btn-primary">View All Products</a>
        </div>
    </div>
</section>
@endif

<!-- Latest Products Section -->
@if($latestProducts->count() > 0)
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Latest Products</h2>
            <small class="text-muted">New arrivals</small>
        </div>
        <div class="row g-4">
            @foreach($latestProducts->take(4) as $product)
            <div class="col-md-6 col-lg-3">
                <div class="card product-card h-100">
                    <div class="position-relative">
                        @if($product->images && count($product->images) > 0)
                            <div class="bg-white d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                        @else
                            <div class="bg-white d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-box fa-3x text-muted"></i>
                            </div>
                        @endif
                        <span class="badge bg-success position-absolute top-0 start-0 m-2">New</span>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title">{{ $product->name }}</h6>
                        <p class="card-text text-muted small flex-grow-1">{{ Str::limit($product->short_description, 80) }}</p>
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    @if($product->isOnSale())
                                        <span class="price sale-price">${{ number_format($product->sale_price, 2) }}</span>
                                        <small class="original-price ms-1">${{ number_format($product->price, 2) }}</small>
                                    @else
                                        <span class="price">${{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                                <small class="text-muted">{{ $product->category->name }}</small>
                            </div>
                            <div class="d-grid gap-2">
                                <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary btn-sm">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3 text-center">
                <i class="fas fa-shipping-fast fa-3x text-primary mb-3"></i>
                <h5>Fast Shipping</h5>
                <p class="text-muted">Free shipping on orders over $100</p>
            </div>
            <div class="col-md-3 text-center">
                <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                <h5>Secure Payment</h5>
                <p class="text-muted">Your payment information is safe</p>
            </div>
            <div class="col-md-3 text-center">
                <i class="fas fa-undo fa-3x text-primary mb-3"></i>
                <h5>Easy Returns</h5>
                <p class="text-muted">30-day return policy</p>
            </div>
            <div class="col-md-3 text-center">
                <i class="fas fa-headset fa-3x text-primary mb-3"></i>
                <h5>24/7 Support</h5>
                <p class="text-muted">Customer support anytime</p>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    .category-card {
        transition: transform 0.2s;
    }
    .category-card:hover {
        transform: translateY(-5px);
    }
</style>
@endpush
@endsection