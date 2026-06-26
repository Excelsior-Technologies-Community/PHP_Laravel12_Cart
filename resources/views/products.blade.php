<!DOCTYPE html>
<html>

<head>

    <title>Products</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>

        *{
            font-family: 'Poppins', sans-serif;
        }

        body{
            background: linear-gradient(to right, #eef2ff, #f8fafc);
            height: 100vh;
            overflow: hidden;
        }

        .main-wrapper{
            height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 18px;
        }

        .top-header{
            background: white;
            padding: 18px 25px;
            border-radius: 18px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.06);
            margin-bottom: 15px;
        }

        .title{
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 0;
        }

        .subtitle{
            font-size: 14px;
            color: #6b7280;
        }

        .btn-custom{
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 600;
        }

        .search-box{
            background: white;
            padding: 15px;
            border-radius: 18px;
            box-shadow: 0 5px 18px rgba(0,0,0,0.05);
            margin-bottom: 15px;
        }

        .search-input{
            height: 50px;
            border-radius: 12px;
        }

        .body-area{
            flex: 1;
            display: flex;
            gap: 15px;
            overflow: hidden;
        }

        .category-sidebar{
            width: 220px;
            flex-shrink: 0;
            background: white;
            border-radius: 18px;
            box-shadow: 0 5px 18px rgba(0,0,0,0.05);
            padding: 18px;
            overflow-y: auto;
        }

        .category-sidebar h6{
            font-weight: 700;
            margin-bottom: 12px;
        }

        .category-link{
            display: block;
            padding: 8px 12px;
            border-radius: 10px;
            color: #374151;
            text-decoration: none;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .category-link:hover{
            background: #f0fdf4;
        }

        .category-link.active{
            background: #16a34a;
            color: white;
            font-weight: 600;
        }

        .products-section{
            flex: 1;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .products-grid{
            flex: 1;
            overflow: auto;
        }

        .product-card{
            border: none;
            border-radius: 18px;
            overflow: hidden;
            height: 100%;
            box-shadow: 0 6px 20px rgba(0,0,0,0.07);
            transition: 0.3s;
        }

        .product-card:hover{
            transform: translateY(-4px);
        }

        .image-box{
            height: 150px;
            background: #f8fafc;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px;
        }

        .product-image{
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .card-body{
            padding: 15px;
        }

        .product-name{
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .category-tag{
            display: inline-block;
            background: #eef2ff;
            color: #4338ca;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
            margin-bottom: 8px;
        }

        .star-rating{
            color: #f59e0b;
            font-size: 14px;
            margin-bottom: 6px;
        }

        .star-rating .star-empty{
            color: #d1d5db;
        }

        .description{
            font-size: 13px;
            color: #6b7280;
            height: 40px;
            overflow: hidden;
        }

        .price{
            color: #16a34a;
            font-size: 22px;
            font-weight: 700;
        }

        .action-btn{
            border-radius: 10px;
            font-weight: 600;
            padding: 10px;
        }

        .pagination-wrapper{
            padding-top: 12px;
        }

        .pagination{
            gap: 8px;
        }

        .pagination .page-link{
            border: none;
            border-radius: 12px !important;
            padding: 10px 15px;
            font-weight: 600;
            color: #16a34a;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }

        .pagination .page-link:hover{
            background: #16a34a;
            color: white;
        }

        .pagination .active .page-link{
            background: #16a34a;
            color: white;
        }

        .alert{
            border-radius: 14px;
        }

    </style>

</head>

<body>

<div class="main-wrapper">

    <div class="top-header">

        <div class="d-flex justify-content-between align-items-center">

            <div>

                <h2 class="title">
                    🛍 Product Store
                </h2>

                <div class="subtitle">
                    Modern Laravel Shopping Cart
                </div>

            </div>

            <div class="d-flex gap-2">

                <a href="/create-product"
                   class="btn btn-success btn-custom">

                    + Create

                </a>

                <a href="/cart"
                   class="btn btn-primary btn-custom">

                    🛒 Cart

                </a>

            </div>

        </div>

    </div>

    <div class="search-box">

        <form method="GET">

            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif

            <div class="row g-2">

                <div class="col-md-10">

                    <input type="text"
                           name="search"
                           class="form-control search-input"
                           placeholder="Search products..."
                           value="{{ request('search') }}">

                </div>

                <div class="col-md-2">

                    <button class="btn btn-success w-100 h-100 btn-custom">

                        Search

                    </button>

                </div>

            </div>

        </form>

    </div>

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button class="btn-close"
                    data-bs-dismiss="alert"></button>

        </div>

    @endif

    <div class="body-area">

        <div class="category-sidebar">

            <h6>Categories</h6>

            <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}"
               class="category-link {{ !request('category') ? 'active' : '' }}">
                All Products
            </a>

            @foreach($categories as $category)

                <a href="{{ request()->fullUrlWithQuery(['category' => $category->id]) }}"
                   class="category-link {{ request('category') == $category->id ? 'active' : '' }}">
                    {{ $category->name }}
                </a>

            @endforeach

        </div>

        <div class="products-section">

            <div class="products-grid">

                <div class="row g-3">

                    @forelse($products as $product)

                    <div class="col-lg-3 col-md-4 col-sm-6">

                        <div class="card product-card">

                            <div class="image-box">

                                @if($product->image)

                                    <img src="{{ asset('storage/'.$product->image) }}"
                                         class="product-image">

                                @else

                                    <span class="text-muted">
                                        No Image
                                    </span>

                                @endif

                            </div>

                            <div class="card-body d-flex flex-column">

                                @if($product->category)
                                    <span class="category-tag">{{ $product->category->name }}</span>
                                @endif

                                <div class="product-name">

                                    {{ $product->name }}

                                </div>

                                <div class="star-rating">

                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= round($product->rating))
                                            ★
                                        @else
                                            <span class="star-empty">★</span>
                                        @endif
                                    @endfor

                                    <span class="text-muted">({{ number_format($product->rating, 1) }})</span>

                                </div>

                                <div class="description flex-grow-1">

                                    {{ $product->description }}

                                </div>

                                <div class="price mb-3 mt-2">

                                    ₹{{ number_format($product->price, 2) }}

                                </div>

                                <div class="d-grid gap-2">

                                    <a href="/add-to-cart/{{$product->id}}"
                                       class="btn btn-primary action-btn">

                                        Add To Cart

                                    </a>

                                    <a href="/edit-product/{{$product->id}}"
                                       class="btn btn-warning action-btn">

                                        Edit

                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                    @empty

                    <div class="col-12">

                        <div class="alert alert-warning text-center p-4">

                            No products found.

                        </div>

                    </div>

                    @endforelse

                </div>

            </div>

            <div class="pagination-wrapper d-flex justify-content-center">

                {{ $products->onEachSide(1)->links('pagination::bootstrap-5') }}

            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>