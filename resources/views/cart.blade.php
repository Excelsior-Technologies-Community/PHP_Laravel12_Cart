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

        .qty-btn {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            border: 1px solid #ddd;
            background: white;
            font-weight: 600;
            cursor: pointer;
        }

        .qty-btn:hover {
            background: #f0f0f0;
        }

        .coupon-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 15px;
        }

        .applied-coupon {
            background: #d4edda;
            color: #155724;
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        <div id="alertBox">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>

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

            <tbody id="cartBody">

            @foreach(session('cart') as $id => $item)

                <tr data-id="{{ $id }}">

                    <td class="d-flex align-items-center gap-3">

                        @if($item['image'])
                            <img src="{{ asset('storage/'.$item['image']) }}" class="cart-image">
                        @endif

                        {{ $item['name'] }}

                    </td>

                    <td class="item-price" data-price="{{ $item['price'] }}">₹{{ $item['price'] }}</td>

                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <button class="qty-btn" onclick="updateQty('{{ $id }}', 'decrease')">-</button>
                            <span class="badge bg-secondary item-qty">
                                {{ $item['quantity'] }}
                            </span>
                            <button class="qty-btn" onclick="updateQty('{{ $id }}', 'increase')">+</button>
                        </div>
                    </td>

                    <td class="item-total">₹{{ $item['price'] * $item['quantity'] }}</td>

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

        <div class="coupon-box">

            <label class="form-label fw-semibold">Promo Code</label>

            <div id="couponInputArea" class="d-flex gap-2" @if($coupon ?? null) style="display:none;" @endif>
                <input type="text" id="couponCode" class="form-control" placeholder="Enter promo code">
                <button class="btn btn-success" onclick="applyCoupon()">Apply</button>
            </div>

            <div id="appliedCouponArea" @if(!($coupon ?? null)) style="display:none;" @endif>
                <div class="applied-coupon">
                    <span>Coupon <strong id="appliedCouponCode">{{ ($coupon['code'] ?? '') }}</strong> applied</span>
                    <button class="btn btn-sm btn-outline-danger" onclick="removeCoupon()">Remove</button>
                </div>
            </div>

            <div id="couponMessage" class="small mt-2"></div>

        </div>

        <div class="total-box mt-3">
            <div>Subtotal: <span id="subtotalValue">₹0</span></div>
            <div class="text-success">Discount: <span id="discountValue">-₹0</span></div>
            <h4 class="mt-2">Total: <span id="finalTotalValue">₹0</span></h4>
        </div>

        @else

        <div class="empty-cart">
            <h4>Your cart is empty 🛒</h4>
            <a href="/" class="btn btn-primary mt-3">Browse Products</a>
        </div>

        @endif

    </div>

</div>

<script>
    const csrfToken = '{{ csrf_token() }}';

    function renderTotals(totals) {
        document.getElementById('subtotalValue').innerText = '₹' + totals.subtotal;
        document.getElementById('discountValue').innerText = '-₹' + totals.discount;
        document.getElementById('finalTotalValue').innerText = '₹' + totals.final_total;
    }

    function recalcRows(cart) {
        document.querySelectorAll('#cartBody tr').forEach(row => {
            const id = row.dataset.id;
            const item = cart[id];

            if (!item) {
                row.remove();
                return;
            }

            row.querySelector('.item-qty').innerText = item.quantity;
            row.querySelector('.item-total').innerText = '₹' + (item.price * item.quantity);
        });

        if (Object.keys(cart).length === 0) {
            location.reload();
        }
    }

    function updateQty(id, action) {
        fetch('/cart/update-quantity', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ id: id, action: action })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                recalcRows(data.cart);
                renderTotals(data.totals);
            }
        })
        .catch(() => {
            document.getElementById('alertBox').innerHTML =
                '<div class="alert alert-danger">Could not update quantity. Please try again.</div>';
        });
    }

    function applyCoupon() {
        const code = document.getElementById('couponCode').value.trim();
        const msgBox = document.getElementById('couponMessage');

        if (!code) {
            msgBox.innerHTML = '<span class="text-danger">Please enter a code</span>';
            return;
        }

        fetch('/cart/apply-coupon', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ code: code })
        })
        .then(res => res.json().then(data => ({ status: res.status, data })))
        .then(({ status, data }) => {
            if (status === 200 && data.success) {
                msgBox.innerHTML = '<span class="text-success">' + data.message + '</span>';
                document.getElementById('couponInputArea').style.display = 'none';
                document.getElementById('appliedCouponArea').style.display = 'block';
                document.getElementById('appliedCouponCode').innerText = code.toUpperCase();
                renderTotals(data.totals);
            } else {
                msgBox.innerHTML = '<span class="text-danger">' + (data.message || 'Invalid coupon') + '</span>';
            }
        })
        .catch(() => {
            msgBox.innerHTML = '<span class="text-danger">Something went wrong</span>';
        });
    }

    function removeCoupon() {
        fetch('/cart/remove-coupon', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById('couponInputArea').style.display = 'flex';
                document.getElementById('appliedCouponArea').style.display = 'none';
                document.getElementById('couponCode').value = '';
                document.getElementById('couponMessage').innerHTML = '';
                renderTotals(data.totals);
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        let subtotal = 0;

        document.querySelectorAll('#cartBody tr').forEach(row => {
            const price = parseFloat(row.querySelector('.item-price').dataset.price);
            const qty = parseInt(row.querySelector('.item-qty').innerText);
            subtotal += price * qty;
        });

        @if($coupon ?? null)
            let discount = 0;
            @if(($coupon['type'] ?? '') === 'percent')
                discount = subtotal * ({{ $coupon['value'] }} / 100);
            @else
                discount = Math.min({{ $coupon['value'] ?? 0 }}, subtotal);
            @endif
            renderTotals({
                subtotal: subtotal.toFixed(2),
                discount: discount.toFixed(2),
                final_total: Math.max(subtotal - discount, 0).toFixed(2)
            });
        @else
            renderTotals({
                subtotal: subtotal.toFixed(2),
                discount: '0.00',
                final_total: subtotal.toFixed(2)
            });
        @endif
    });
</script>

</body>
</html>