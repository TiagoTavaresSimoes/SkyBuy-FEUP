<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $cart = $request->session()->get('cart', []);
        return view('checkout', compact('cart'));
    }
    
    public function processOrder(Request $request)
    {
        $cart = $request->session()->get('cart', []);

        
        if (empty($cart)) {
            return redirect()->route('checkout.index')->with('error', 'Seu carrinho está vazio.');
        }

        $cartId = $request->session()->get('cart_id', null);
        if (!$cartId) {
            return redirect()->route('checkout.index')->with('error', 'Erro no carrinho de compras.');
        }
        $total = array_sum(array_column($cart, 'price'));
        

        $defaultAddressId = 1;
        $defaultPaymentMethodId = 1;
    
        $order = Order::create([
            'order_date' => now(),
            'delivery_date' => now()->addDays(5),
            'order_status' => 'Processing',
            'id_customer' => auth()->id(),
            'id_address' => $defaultAddressId,
            'id_payment_method' => $defaultPaymentMethodId, 
            'id_cart' => $cartId, 
        ]);


        $request->session()->forget('cart');


        return redirect()->route('account.ordersHistory')->with('success', 'Pedido realizado com sucesso!');
    }     
}

