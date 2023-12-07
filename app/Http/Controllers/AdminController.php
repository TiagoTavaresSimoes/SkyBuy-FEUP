<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Review;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('is_admin');
    }

    public function dashboard() {
        $userCount = User::count();
        $productCount = Product::count();
        $orderCount = Order::count();
        $reviewCount = Review::count();

        return view('admin.dashboard', [
            'userCount' => $userCount,
            'productCount' => $productCount,
            'orderCount' => $orderCount,
            'reviewCount' => $reviewCount
        ]);
    }

    public function blockUser(Request $request) {
        $userId = $request->input('user_id');
        $user = User::find($userId);
        if ($user) {
            $user->is_blocked = true;
            $user->save();
            return back()->with('success', 'Usuário bloqueado com sucesso.');
        }
        return back()->with('error', 'Usuário não encontrado.');
    }

    public function unblockUser(Request $request) {
        $userId = $request->input('user_id');
        $user = User::find($userId);
        if ($user) {
            $user->is_blocked = false;
            $user->save();
            return back()->with('success', 'Usuário desbloqueado com sucesso.');
        }
        return back()->with('error', 'Usuário não encontrado.');
    }

}