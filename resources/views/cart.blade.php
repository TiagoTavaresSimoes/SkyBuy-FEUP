@extends('layouts.app')

@section('title', 'SkyBuy - Cart')

@section('content')
<div class="container">
    <h1>Carrinho de Compras</h1>
    
    @if(session('cart'))
        <div class="cart-container">
            @php $total = 0; @endphp
            @foreach(session('cart') as $id => $details)
                @php
                $subtotal = isset($details['price']) ? $details['price'] * $details['quantity'] : 0;
                $total += $subtotal;
                @endphp
                <div class="cart-item">
                    <img src="{{ $details['image_url'] ?? 'default-product-image.png' }}" alt="{{ $details['name'] ?? 'Product Image' }}" class="cart-item-image">
                    <div class="cart-item-details">
                        <h3>{{ $details['name'] ?? 'Product Name Not Available' }}</h3>
                        <p>{{ $details['description'] ?? 'No description available.' }}</p>
                        <p>Preço: ${{ $details['price'] ?? 'N/A' }}</p>
                        <p>Quantidade: {{ $details['quantity'] ?? 'N/A' }}</p>
                        <p>Subtotal: ${{ $subtotal }}</p>
                        <form action="{{ route('cart.remove') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $id }}">
                            <button type="submit" class="btn btn-danger">Remover</button>
                        </form>
                    </div>
                </div>
            @endforeach
            <div class="cart-total">
                <strong>Total Geral: ${{ $total }}</strong>
            </div>
        </div>
    @else
        <div class="alert alert-info">Seu carrinho está vazio.</div>
    @endif
</div>
@endsection