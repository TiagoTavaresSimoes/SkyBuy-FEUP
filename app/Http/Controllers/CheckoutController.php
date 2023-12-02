<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CheckoutController extends Controller
{
    public function index(Request $request)
{
    $cart = $request->session()->get('cart', []);
    return view('checkout', compact('cart'));
}  
}

