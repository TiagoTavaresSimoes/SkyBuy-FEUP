<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = (int)$request->input('quantity');
        $product = Product::find($productId);
    
        if (!$product) {
            return redirect()->back()->with('error', 'Invalid product!');
        }
    
        if ($quantity <= 0 || $quantity > 20) {
            return redirect()->back()->with('error', 'Invalid quantity selected!');
        }
    
        if ($product->stock < $quantity) {
            return redirect()->back()->with('error', 'Not enough stock available!');
        }
    
        $cart = $request->session()->get('cart', []);
    
        if (isset($cart[$productId])) {
            if (($cart[$productId]['quantity'] + $quantity) > $product->stock) {
                return redirect()->back()->with('error', 'Not enough stock available!');
            }
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