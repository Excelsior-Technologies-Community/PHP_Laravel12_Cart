<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background: #f5f7fb; }

        .cart-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }

        .cart-image {
            width: 70px;
            height: 70px;
            object-fit: contain;
            background: #f8f9fa;
            padding: 5px;
            border-radius: 6px;
        }

        .total-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            text-align: right;
        }

        .empty-cart {
            text-align: center;
            padding: 40px;
            color: #777;
        }
    </style>
</head>

<body>

<div class="container mt-5">

    <div class="cart-container">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <h2>Your Cart</h2>

            <a href="/" class="btn btn-primary">Continue Shopping</a>

        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('cart') && count(session('cart')) > 0)

        <table class="table align-middle">

            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

            @php $total = 0 @endphp

            @foreach(session('cart') as $id => $item)

                @php
                    $total += $item['price'] * $item['quantity'];
                @endphp

                <tr>

                    <td class="d-flex align-items-center gap-3">

                        @if($item['image'])
                            <img src="{{ asset('storage/'.$item['image']) }}" class="cart-image">
                        @endif

                        {{ $item['name'] }}

                    </td>

                    <td>₹{{ $item['price'] }}</td>

                    <td>
                        <span class="badge bg-secondary">
                            {{ $item['quantity'] }}
                        </span>
                    </td>

                    <td>₹{{ $item['price'] * $item['quantity'] }}</td>

                    <td>

                        <form action="/remove-cart" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $id }}">

                            <button class="btn btn-danger btn-sm">
                                Remove
                            </button>
                        </form>

                    </td>

                </tr>

            @endforeach

            </tbody>

        </table>

        <div class="total-box mt-3">
            <h4>Total: ₹{{ $total }}</h4>
        </div>

        @else

        <div class="empty-cart">
            <h4>Your cart is empty 🛒</h4>
            <a href="/" class="btn btn-primary mt-3">Browse Products</a>
        </div>

        @endif

    </div>

</div>

</body>
</html>