# PHP_Laravel12_Cart

## Introduction

PHP_Laravel12_Cart is a beginner-friendly Laravel 12 demonstration project that implements a simple shopping cart system using Laravel sessions.

The project shows how to build a small eCommerce-style product and cart system using core Laravel features such as models, migrations, controllers, Blade views, and file storage.

Products can be created with an image, displayed on the product page, and added to a shopping cart. The cart is stored in the Laravel session, allowing users to manage items without requiring authentication or a database cart table.

This project is designed to help developers understand the basic structure and workflow of a Laravel application while implementing a practical feature commonly used in real-world web applications.

---

## Project Overview

This project demonstrates a basic product management and shopping cart workflow.

The application allows users to:

- Create products with name, description, price, and image

- Upload and store product images using Laravel Storage

- Display products in a modern product listing page

- Add products to a shopping cart

- Store cart items using Laravel session

- View cart items with quantity and total price

- Remove items from the cart


The project uses:

- Laravel 12

- MySQL Database

- Bootstrap 5 UI

- Laravel Blade Templates

- Session-based Cart System

- Laravel File Storage for Image Upload


This project is useful for beginners who want to learn how Laravel handles:

- MVC architecture

- Database migrations

- File uploads

- Session management

- Blade templating

- Routing and controllers

---

## Step 1: Create Laravel 12 Project

Install Laravel 12 using Composer.

```
composer create-project laravel/laravel PHP_Laravel12_Cart "12.*"
```

Move to the project folder.

```
cd PHP_Laravel12_Cart
```

---

## Step 2: Configure Database

Open `.env` file.

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_cart
DB_USERNAME=root
DB_PASSWORD=
```

Run migration command:

```bash
php artisan migrate
```

---


## Step 3: Create Product Model and Migration

Run the command:

```
php artisan make:model Product -m
```

This creates:

```
app/Models/Product.php
database/migrations/create_products_table.php
```

---

## Step 4: Products Migration

Open the migration file.

```
database/migrations/xxxx_create_products_table.php
```

Update:

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 8, 2);
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
```

Run migration.

```
php artisan migrate
```

---

## Step 5: Product Model

File:

```
app/Models/Product.php
```

```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'image'
    ];
}
```

---

## Step 6: Create Controller

Create controller.

```
php artisan make:controller CartController
```

File:

```
app/Http/Controllers/CartController.php
```

Add:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{

    public function products()
    {
        $products = Product::all();
        return view('products', compact('products'));
    }

    public function create()
    {
        return view('create-product');
    }

    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {

            // store image in storage/app/public/products
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imagePath
        ]);

        return redirect('/')->with('success', 'Product Created Successfully');
    }

    public function addToCart($id)
    {
        $product = Product::findOrFail($id);

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {

            $cart[$id]['quantity']++;

        } else {

            $cart[$id] = [
                "name" => $product->name,
                "price" => $product->price,
                "image" => $product->image,
                "quantity" => 1
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Product added to cart');
    }

    public function cart()
    {
        return view('cart');
    }

    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$request->id])) {

            unset($cart[$request->id]);

            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Product removed from cart');
    }
}
```

Controller handles:

* product listing
* create product
* image upload
* add to cart
* remove from cart

---

## Step 7: Storage Configuration

Since product images are uploaded, run:

```
php artisan storage:link
```

This links:

```
storage/app/public → public/storage
```

Images will be accessible via:

```
asset('storage/products/filename.jpg')
```

---

## Step 8: Web Routes

Open:

```
routes/web.php
```

Add routes:

```
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;

Route::get('/',[CartController::class,'products']);

Route::get('/create-product',[CartController::class,'create']);

Route::post('/store-product',[CartController::class,'store']);

Route::get('/add-to-cart/{id}',[CartController::class,'addToCart']);

Route::get('/cart',[CartController::class,'cart']);

Route::post('/remove-cart',[CartController::class,'remove']);
```

---

## Step 9: Create Product Page

File:

```
resources/views/create-product.blade.php
```

Add:

```blade
<!DOCTYPE html>
<html>

<head>

    <title>Create Product</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f7fb;
        }

        .form-card {
            background: white;
            padding: 35px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        .form-title {
            font-weight: 600;
        }

        .image-preview {
            width: 120px;
            height: 120px;
            border: 1px dashed #ccc;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: #fafafa;
        }

        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
    </style>

</head>

<body>

    <div class="container mt-5">

        <div class="row justify-content-center">

            <div class="col-md-7">

                <div class="form-card">

                    <div class="d-flex justify-content-between align-items-center mb-4">

                        <h3 class="form-title">Create Product</h3>

                        <a href="/" class="btn btn-outline-primary">
                            Back
                        </a>

                    </div>

                    <form action="/store-product" method="POST" enctype="multipart/form-data">

                        @csrf

                        <div class="mb-3">

                            <label class="form-label">Product Name</label>

                            <input type="text" name="name" class="form-control" placeholder="Enter product name">

                        </div>

                        <div class="mb-3">

                            <label class="form-label">Description</label>

                            <textarea name="description" rows="3" class="form-control" placeholder="Enter product description"></textarea>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">Price</label>

                            <input type="number" name="price" class="form-control" placeholder="Enter price">

                        </div>

                        <div class="mb-4">

                            <label class="form-label">Product Image</label>

                            <input type="file" name="image" class="form-control" onchange="previewImage(event)">

                            <div class="mt-3 image-preview" id="previewBox">
                                <span class="text-muted small">Preview</span>
                            </div>

                        </div>

                        <button class="btn btn-success w-100">
                            Create Product
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

    <script>
        function previewImage(event) {

            const previewBox = document.getElementById('previewBox');

            previewBox.innerHTML = '';

            const img = document.createElement('img');

            img.src = URL.createObjectURL(event.target.files[0]);

            previewBox.appendChild(img);

        }
    </script>

</body>

</html>
```

This page allows users to:

* Enter product name
* Enter description
* Enter price
* Upload product image

---

## Step 10: Products Page

File:

```
resources/views/products.blade.php
```

Add:

```blade
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
```

Features:

* Display all products
* Show product image
* Show price
* Add to cart button
* Create product button
* View cart button
* Success message alerts

---

## Step 11: Cart Page

File:

```
resources/views/cart.blade.php
```

Add:

```blade
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
```

Features:

* Display cart products
* Show product image
* Show quantity
* Show item total
* Show cart total
* Remove item from cart

Cart data is stored in **Laravel session**.

---

## Step 12: Run the Project

Start server:

```
php artisan serve
```

Open in browser:

```
http://127.0.0.1:8000
```

---

## Output

<img width="1918" height="1028" alt="Screenshot 2026-03-13 141033" src="https://github.com/user-attachments/assets/53d3318a-e4db-4806-9490-29d9ce904f00" />

<img width="1919" height="1025" alt="Screenshot 2026-03-13 141802" src="https://github.com/user-attachments/assets/8936afbe-0f3e-46bd-a6ba-8bda2bc41bb7" />

<img width="1919" height="1028" alt="Screenshot 2026-03-13 141819" src="https://github.com/user-attachments/assets/85c23111-129b-4902-842a-ab106ea555fc" />

---

## Project Structure

```
PHP_Laravel12_Cart
│
├── app
│   ├── Http
│   │   └── Controllers
│   │       └── CartController.php
│   │
│   ├── Models
│   │   └── Product.php
│   │
│   └── Providers
│
├── bootstrap
│   └── app.php
│
├── config
│   ├── app.php
│   ├── database.php
│   ├── filesystems.php
│   └── session.php
│
├── database
│   ├── factories
│   ├── migrations
│   │   └── create_products_table.php
│   └── seeders
│
├── public
│   ├── index.php
│   └── storage → symlink (created after running php artisan storage:link)
│
├── resources
│   ├── views
│   │   ├── products.blade.php
│   │   ├── cart.blade.php
│   │   └── create-product.blade.php
│   │
│   ├── css
│   └── js
│
├── routes
│   ├── web.php
│   └── console.php
│
├── storage
│   ├── app
│   │   └── public
│   │       └── products (uploaded product images)
│   │
│   ├── framework
│   └── logs
│
├── tests
│
├── vendor
│
├── .env
├── .env.example
├── .gitignore
├── artisan
├── composer.json
├── composer.lock
├── package.json
├── phpunit.xml
└── README.md
```

---

Your PHP_Laravel12_Cart Project is now ready!

