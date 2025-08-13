@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('categories.show', $product->category) }}">{{ $product->category->name }}</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Images -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body text-center">
                    @if($product->images && count($product->images) > 0)
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                            <i class="fas fa-image fa-5x text-muted"></i>
                        </div>
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                            <i class="fas fa-box fa-5x text-muted"></i>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-lg-6">
            <div class="mb-3">
                @if($product->isOnSale())
                    <span class="badge bg-danger mb-2">On Sale</span>
                @endif
                @if($product->created_at->diffInDays() < 30)
                    <span class="badge bg-success mb-2">New</span>
                @endif
            </div>

            <h1 class="h3 mb-3">{{ $product->name }}</h1>
            
            <div class="mb-3">
                <span class="text-muted">Brand: </span>
                <strong>{{ $product->brand ?: 'N/A' }}</strong>
            </div>

            <div class="mb-3">
                <span class="text-muted">SKU: </span>
                <code>{{ $product->sku }}</code>
            </div>

            <div class="mb-4">
                @if($product->isOnSale())
                    <span class="h4 text-danger">${{ number_format($product->sale_price, 2) }}</span>
                    <span class="h6 text-muted text-decoration-line-through ms-2">${{ number_format($product->price, 2) }}</span>
                    <span class="badge bg-danger ms-2">
                        Save ${{ number_format($product->price - $product->sale_price, 2) }}
                    </span>
                @else
                    <span class="h4 text-success">${{ number_format($product->price, 2) }}</span>
                @endif
            </div>

            <div class="mb-4">
                <p class="lead">{{ $product->short_description }}</p>
            </div>

            <!-- Stock Status -->
            <div class="mb-4">
                @if($product->isAvailable())
                    <span class="badge bg-success">
                        <i class="fas fa-check"></i> In Stock
                        @if($product->manage_stock)
                            ({{ $product->stock_quantity }} available)
                        @endif
                    </span>
                @else
                    <span class="badge bg-danger">
                        <i class="fas fa-times"></i> Out of Stock
                    </span>
                @endif
            </div>

            <!-- Add to Cart -->
            @auth
                @if($product->isAvailable())
                    <form method="POST" action="{{ route('cart.add') }}" class="mb-4">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <div class="row g-3 align-items-end">
                            <div class="col-auto">
                                <label for="quantity" class="form-label">Quantity</label>
                                <select name="quantity" id="quantity" class="form-select" style="width: 80px;">
                                    @for($i = 1; $i <= min(10, $product->manage_stock ? $product->stock_quantity : 10); $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-cart-plus"></i> Add to Cart
                                </button>
                            </div>
                        </div>
                    </form>
                @endif
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Please <a href="{{ route('login') }}">login</a> to add items to your cart.
                </div>
            @endauth

            <!-- Product Specifications -->
            @if($product->specifications && count($product->specifications) > 0)
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Specifications</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($product->specifications as $key => $value)
                                <div class="col-sm-6 mb-2">
                                    <strong>{{ $key }}:</strong> {{ $value }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Product Description -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Product Description</h5>
                </div>
                <div class="card-body">
                    <p>{{ $product->description }}</p>
                    
                    @if($product->weight)
                        <p><strong>Weight:</strong> {{ $product->weight }} kg</p>
                    @endif
                    
                    @if($product->dimensions)
                        <p><strong>Dimensions:</strong> {{ $product->dimensions }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="row mt-5">
            <div class="col-12">
                <h4 class="mb-4">Related Products</h4>
                <div class="row g-4">
                    @foreach($relatedProducts as $relatedProduct)
                        <div class="col-md-6 col-lg-3">
                            <div class="card product-card h-100">
                                <div class="position-relative">
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="fas fa-box fa-3x text-muted"></i>
                                    </div>
                                    @if($relatedProduct->isOnSale())
                                        <span class="badge bg-danger position-absolute top-0 start-0 m-2">Sale</span>
                                    @endif
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title">{{ $relatedProduct->name }}</h6>
                                    <p class="card-text text-muted small flex-grow-1">{{ Str::limit($relatedProduct->short_description, 60) }}</p>
                                    <div class="mt-auto">
                                        <div class="mb-2">
                                            @if($relatedProduct->isOnSale())
                                                <span class="price text-danger">${{ number_format($relatedProduct->sale_price, 2) }}</span>
                                                <small class="text-muted text-decoration-line-through ms-1">${{ number_format($relatedProduct->price, 2) }}</small>
                                            @else
                                                <span class="price text-success">${{ number_format($relatedProduct->price, 2) }}</span>
                                            @endif
                                        </div>
                                        <div class="d-grid">
                                            <a href="{{ route('products.show', $relatedProduct) }}" class="btn btn-outline-primary btn-sm">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
@endsection