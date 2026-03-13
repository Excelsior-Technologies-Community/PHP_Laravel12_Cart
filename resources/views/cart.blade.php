<!DOCTYPE html>
<html>

<head>

    <title>Your Cart</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f7fb;
        }

        .cart-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        .cart-image {
            width: 70px;
            height: 70px;
            object-fit: contain;
            background: #f8f9fa;
            padding: 5px;
            border-radius: 6px;
        }

        .table th {
            background: #f1f3f5;
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

                <a href="/" class="btn btn-primary">
                    Continue Shopping
                </a>

            </div>

            @if(session('cart') && count(session('cart')) > 0)

            <table class="table align-middle">

                <thead>

                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th class="text-center">Quantity</th>
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

                            @if(isset($item['image']) && $item['image'])
                            <img src="{{ asset('storage/'.$item['image']) }}" class="cart-image">
                            @endif

                            <strong>{{ $item['name'] }}</strong>

                        </td>

                        <td>₹{{ $item['price'] }}</td>

                        <td class="text-center">
                            <span class="badge bg-secondary p-2">
                                {{ $item['quantity'] }}
                            </span>
                        </td>

                        <td>
                            <strong>₹{{ $item['price'] * $item['quantity'] }}</strong>
                        </td>

                        <td>

                            <form action="/remove-cart" method="POST">

                                @csrf

                                <input type="hidden" name="id" value="{{ $id }}">

                                <button class="btn btn-sm btn-danger">
                                    Remove
                                </button>

                            </form>

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

            <div class="total-box mt-4">

                <h4>Total Amount: <span class="text-success">₹{{ $total }}</span></h4>

            </div>

            @else

            <div class="empty-cart">

                <h4>Your cart is empty 🛒</h4>

                <p>Add some products to your cart.</p>

                <a href="/" class="btn btn-primary mt-3">
                    Browse Products
                </a>

            </div>

            @endif

        </div>

    </div>

</body>

</html>