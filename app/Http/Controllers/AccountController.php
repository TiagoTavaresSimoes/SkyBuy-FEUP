<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AccountController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('account', ['user' => $user]);
    }
}

