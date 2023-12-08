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
        $searchTerm = $request->input('search');

        $products = Product::where('name', 'LIKE', "%{$searchTerm}%")
        ->orWhere('description', 'LIKE', "%{$searchTerm}%")
        ->get();



        return view('pages.searchResults', compact('products', 'searchTerm'));
    }

}
