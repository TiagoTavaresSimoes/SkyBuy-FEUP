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
        $searchWords = explode(' ', $searchTerm);
    
        $products = Product::query();
    
        foreach ($searchWords as $word) {
            $products = $products->where(function($query) use ($word) {
                $query->where('name', 'LIKE', '%' . $word . '%')
                      ->orWhere('description', 'LIKE', '%' . $word . '%');
            });
        }
    
        $products = $products->get();
    
        return view('pages.searchResults', compact('products', 'searchTerm'));
    }

}
