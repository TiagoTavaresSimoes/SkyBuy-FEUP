<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('main_page', [
            'featuredProducts' => Product::featured()->get()
        ]);
    }
}
