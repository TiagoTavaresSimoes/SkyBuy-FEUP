<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function show()
    {
        $products = Product::all(); 
        return view('pages.home', ['products' => $products]);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $products = Product::where('name', 'like', '%' . $query . '%')->get();

        return view('pages.searchResults', compact('products', 'query'));
    }
}
