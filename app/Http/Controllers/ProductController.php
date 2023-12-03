<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index()
    {   
    return view('products.index');
    }
    public function men()
    {
        $menProducts = Product::where('name', 'like', 'Mens%')->get();
        return view('products.men', compact('menProducts'));
    }

    public function women()
    {
        $womenProducts = Product::where('name', 'like', 'Womens%')->get();
        return view('products.women', compact('womenProducts'));
    }


}