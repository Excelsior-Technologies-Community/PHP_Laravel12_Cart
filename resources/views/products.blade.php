<!DOCTYPE html>

<html>

<head>

    <title>Products</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f7fb;
        }

        .product-card {
            transition: 0.3s;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .product-image-box {
            height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            padding: 10px;
        }

        .product-image {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
        }

        .card-body {
            text-align: center;
        }

        .price {
            font-size: 18px;
            font-weight: 600;
            color: #198754;
        }
    </style>


</head>

<body>

    <div class="container mt-5">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <h2>Products</h2>

            <div>
                <a href="/create-product" class="btn btn-success">Create Product</a>
                <a href="/cart" class="btn btn-primary">View Cart</a>
            </div>

        </div>

        {{-- Success Message --}}
        @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>

        @endif

        <div class="row g-4">

            @foreach($products as $product)

            <div class="col-md-4">

                <div class="card product-card h-100">

                    <div class="product-image-box">

                        @if($product->image)
                        <img src="{{ asset('storage/'.$product->image) }}" class="product-image">
                        @endif

                    </div>

                    <div class="card-body">

                        <h5 class="mb-2">{{ $product->name }}</h5>

                        <p class="text-muted small">
                            {{ $product->description }}
                        </p>

                        <p class="price mb-3">
                            ₹{{ $product->price }}
                        </p>

                        <a href="/add-to-cart/{{$product->id}}" class="btn btn-primary w-100">
                            Add to Cart
                        </a>

                    </div>

                </div>

            </div>

            @endforeach

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>