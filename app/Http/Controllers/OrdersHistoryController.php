<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrdersHistoryController extends Controller
{
    public function index()
    {
        $orders = Order::where('id_customer', auth()->id())->get();

        return view('orders_history', compact('orders'));
    }
}