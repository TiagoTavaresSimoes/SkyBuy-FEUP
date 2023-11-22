@extends('layouts.app')
@section('title', 'SkyBuy - Your Online Shop')
@section('content')
<form method="POST" action="{{ route('home') }}">
    {{ csrf_field() }}
    <nav class="navbar">
        <a href="/" class="nav-logo">SkyBuy</a>
        <div class="nav-container">

            <div class="nav-items">
                <a href="/categories">Categories</a>
                <a href="/account">Your Account</a>
                <a href="/cart">Cart</a>
            </div>

            <div class="search-container">
                <input type="text" placeholder="Search for a product..." name="search">
                <button type="submit">Search</button>
            </div>
        </div>        
    </nav>


    <div id="product-grid">
        @foreach ($products as $product)
            <div class="product-card">
                <img src="{{ $product->image_url }}" alt="Product Image">
                <h3>{{ $product->name }}</h3>
                <p>{{ $product->description }}</p>
                <span>Price: ${{ $product->price }}</span>
                <button>Add to Cart</button>
            </div>
        @endforeach
    </div>

    <footer>
        <!-- Footer Content -->
        <p>© 2023 SkyBuy. All rights reserved.</p>
    </footer>
</form>
@endsection