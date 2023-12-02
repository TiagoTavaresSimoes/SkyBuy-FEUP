@extends('layouts.app')

@section('title', 'SkyBuy - Your Online Shop')

@section('content')
<div class="container">
    <div id="product-grid">
    @foreach ($products as $product)
    <div class="product-card">
        <a href="/product/{{ $product->id_product }}/{{ $product->name }}/{{ $product->price }}/{{ $product->size }}/{{ $product->stock }}/{{ $product->brand }}/{{ $product->rating }}/{{ $product->description }}">
            <img src="{{ $product->image_url }}" alt="Product Image">
            <h3>{{ $product->name }}</h3>
            <p>{{ $product->description }}</p>
        </a>
        <span>Price: ${{ $product->price }}</span>
        <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id_product }}">
            <label for="quantity{{ $product->id_product }}">Quantity:</label>
            <input type="number" id="quantity{{ $product->id_product }}" name="quantity" value="1" min="1" max="{{ $product->stock }}" required>
            <button type="submit" class="add-to-cart-button">Add to Cart</button>
        </form>
    </div>
    @endforeach
    </div>

</div>    
    <footer>
        <p>© 2023 SkyBuy. All rights reserved.</p>
    </footer>

@endsection