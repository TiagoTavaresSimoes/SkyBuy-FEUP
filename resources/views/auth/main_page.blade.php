@extends('layouts.app')
@section('title', 'Main Page')
@section('content')
<form method="POST" action="{{ route('main_page') }}">
    {{ csrf_field() }}
    <nav>
        <a href="/">Home</a>
        <a href="/categories">Categories</a>
        <a href="/account">Your Account</a>
        <a href="/cart">Cart</a>
    </nav>
    <div class="search-bar">
        <input type="text" placeholder="Search products...">
        <button type="submit">Search</button>
    </div>

    <div id="product-grid">
        @for ($i = 0; $i < 10; $i++)
            <div class="product-card">
                <img src="placeholder-product.jpg" alt="Product Image">
                <h3>Product Name {{$i}}</h3>
                <p>Product Description</p>
                <span>Price: $XX.XX</span>
                <button>Add to Cart</button>
            </div>
        @endfor
    </div>

    <footer>
        <!-- Footer Content -->
        <p>© 2023 Your Website Name. All rights reserved.</p>
    </footer>
</form>
@endsection