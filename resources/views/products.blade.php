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

        /* HEADER */

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

        /* SEARCH */

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

        /* PRODUCTS AREA */

        .products-section{
            flex: 1;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .products-grid{
            flex: 1;
            overflow: hidden;
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
            margin-bottom: 8px;
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

        /* PAGINATION */

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

        /* ALERT */

        .alert{
            border-radius: 14px;
        }

    </style>

</head>

<body>

<div class="main-wrapper">

    <!-- HEADER -->
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

    <!-- SEARCH -->
    <div class="search-box">

        <form method="GET">

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

    <!-- SUCCESS MESSAGE -->
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button class="btn-close"
                    data-bs-dismiss="alert"></button>

        </div>

    @endif

    <!-- PRODUCTS -->
    <div class="products-section">

        <div class="products-grid">

            <div class="row g-3 h-100">

                @forelse($products as $product)

                <div class="col-lg-3 col-md-4 col-sm-6">

                    <div class="card product-card">

                        <!-- IMAGE -->
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

                        <!-- BODY -->
                        <div class="card-body d-flex flex-column">

                            <div class="product-name">

                                {{ $product->name }}

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

        <!-- PAGINATION -->
        <div class="pagination-wrapper d-flex justify-content-center">

            {{ $products->onEachSide(1)->links('pagination::bootstrap-5') }}

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>