@extends('layouts.app')
@section('title', 'SkyBuy - Your Online Shop')
@section('content')
<form method="POST" action="{{ route('home') }}">
    {{ csrf_field() }}
    


    <div id="product-grid">
        @foreach ($products as $product)
        <div class="product-card">
            <a href="/product/{{$product->id_product}}/{{$product->name}}/{{$product->price}}/{{$product->size}}/{{$product->stock}}/{{$product->brand}}/{{$product->rating}}/{{$product->description}}">
                <img src="{{ $product->image_url }}" alt="Product Image">
                <h3>{{ $product->name }}</h3>
                <p>{{ $product->description }}</p>
            </a>
            <span>Price: ${{ $product->price }}</span>
            <!-- 'Add to Cart' form -->
            <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id_product }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="add-to-cart-button">Add to Cart</button>
            </form>
        </div>
        @endforeach
    </div>

    <footer>
        <!-- Footer Content -->
        <p>© 2023 SkyBuy. All rights reserved.</p>
    </footer>
</form>
@endsection