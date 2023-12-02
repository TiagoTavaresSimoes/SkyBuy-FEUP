<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');
        $product = Product::find($productId);

        if (!$product) {
            return redirect()->back()->with('error', 'Invalid product!');
        }

        $cart = $request->session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                "name" => $product->name,
                "quantity" => $quantity,
                "price" => $product->price,
                "image_url" => $product->image_url,
                "description" => $product->description
            ];
        }

        $request->session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Product added to cart!');
    }
    public function index(Request $request)
    {
        $cart = $request->session()->get('cart', []);
        return view('cart', ['cart' => $cart]);
    }

    public function remove(Request $request)
    {
        $productId = $request->input('product_id');
        $cart = $request->session()->get('cart');
    
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            $request->session()->put('cart', $cart);
        }
    
        return redirect()->route('cart.index')->with('success', 'Product removed from cart!');
    }    
}