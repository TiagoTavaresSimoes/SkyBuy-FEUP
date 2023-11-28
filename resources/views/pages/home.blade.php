@extends('layouts.app')
@section('title', 'SkyBuy - Your Online Shop')
@section('content')
<form method="POST" action="{{ route('home') }}">
    {{ csrf_field() }}
    


    <div id="product-grid">
        @foreach ($products as $product)
        <a href="/product/{{$product->id_product}}/{{$product->name}}/{{$product->price}}/{{$product->size}}/{{$product->stock}}/{{$product->brand}}/{{$product->rating}}/{{$product->description}}">
            <div class="product-card">
                    <img src="{{ $product->image_url }}" alt="Product Image">
                <h3>{{ $product->name }}</h3>
                <p>{{ $product->description }}</p>
                <span>Price: ${{ $product->price }}</span>
                <button>Add to Cart</button>
            </div>
            </a>
        @endforeach
    </div>

    <footer>
        <!-- Footer Content -->
        <p>© 2023 SkyBuy. All rights reserved.</p>
    </footer>
</form>
@endsection