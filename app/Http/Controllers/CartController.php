<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;


class CartController extends Controller
{
    public function add(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        return redirect()->route('home')->with('success', 'Product added to cart!');
    }
}