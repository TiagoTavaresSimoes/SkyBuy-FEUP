<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AccountController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('account', ['user' => $user]);
    }
    public function edit()
    {
        $user = Auth::user();
        return view('account.edit', ['user' => $user]);
    }
    public function update(Request $request)
    {
        $user = Auth::user();
        $validatedData = $request->validate([
            'username' => 'required|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|max:255'
        ]);
    
        $user->update($validatedData);
        return redirect()->route('account')->with('success', 'Profile updated successfully.');
    }
    public function ordersHistory()
{
    $user = auth()->user();
    $orders = $user->orders;

    return view('account.orders-history', compact('orders'));
}
}

