<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Coupon;

class CartController extends Controller
{
    public function products(Request $request)
    {
        $query = Product::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
            ->orwhere('description', 'like', '%' . $request->search . '%')
            ->orwhere('price', 'like', '%' . $request->search . '%');
        }

        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        $products = $query->latest()->paginate(4);
        $products->appends($request->all());

        $categories = Category::orderBy('name')->get();

        return view('products', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('create-product', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'image' => 'nullable|image',
            'category_id' => 'nullable|exists:categories,id',
            'rating' => 'nullable|numeric|min:0|max:5'
        ]);

        $imagePath = $request->file('image')
            ? $request->file('image')->store('products', 'public')
            : null;

        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imagePath,
            'category_id' => $request->category_id,
            'rating' => $request->rating ?? 0
        ]);

        return redirect('/')->with('success', 'Product Created Successfully');
    }

    public function delete($id)
    {
        Product::findOrFail($id)->delete();

        return back()->with('success', 'Product Deleted Successfully');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::orderBy('name')->get();
        return view('edit-product', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'rating' => 'nullable|numeric|min:0|max:5'
        ]);

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'rating' => $request->rating ?? $product->rating
        ]);

        return redirect('/')->with('success', 'Product Updated Successfully');
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

        return back()->with('success', 'Added to Cart');
    }

    public function cart()
    {
        $coupon = session()->get('coupon');
        return view('cart', compact('coupon'));
    }

    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);

        unset($cart[$request->id]);

        session()->put('cart', $cart);

        return back()->with('success', 'Removed from Cart');
    }

    public function updateQuantity(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'action' => 'required|in:increase,decrease'
        ]);

        $cart = session()->get('cart', []);
        $id = $request->id;

        if (!isset($cart[$id])) {
            return response()->json(['success' => false, 'message' => 'Item not found in cart'], 404);
        }

        if ($request->action === 'increase') {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id]['quantity']--;

            if ($cart[$id]['quantity'] <= 0) {
                unset($cart[$id]);
            }
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'cart' => $cart,
            'totals' => $this->calculateTotals($cart)
        ]);
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $coupon = Coupon::where('code', strtoupper(trim($request->code)))->first();

        if (!$coupon || !$coupon->isValid()) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired coupon code'], 422);
        }

        session()->put('coupon', [
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value
        ]);

        $cart = session()->get('cart', []);

        return response()->json([
            'success' => true,
            'message' => 'Coupon applied successfully',
            'totals' => $this->calculateTotals($cart)
        ]);
    }

    public function removeCoupon()
    {
        session()->forget('coupon');

        $cart = session()->get('cart', []);

        return response()->json([
            'success' => true,
            'totals' => $this->calculateTotals($cart)
        ]);
    }

    private function calculateTotals(array $cart): array
    {
        $subtotal = 0;

        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $discount = 0;
        $couponSession = session()->get('coupon');

        if ($couponSession) {
            if ($couponSession['type'] === 'percent') {
                $discount = round($subtotal * ($couponSession['value'] / 100), 2);
            } else {
                $discount = min($couponSession['value'], $subtotal);
            }
        }

        $finalTotal = max($subtotal - $discount, 0);

        return [
            'subtotal' => round($subtotal, 2),
            'discount' => round($discount, 2),
            'final_total' => round($finalTotal, 2)
        ];
    }
}