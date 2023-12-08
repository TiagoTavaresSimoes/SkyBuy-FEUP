@extends('layouts.app')

@section('content')
    <h1>Search Results for "{{ $searchTerm }}"</h1>
    <div class="products-container">
        @forelse ($products as $product)
            <div class="product">
                <h2>{{ $product->name }}</h2>
                <p>{{ $product->description }}</p>
                <p>Price: ${{ number_format($product->price, 2) }}</p>
            </div>
        @empty
            <p>No products found.</p>
        @endforelse
    </div>
@endsection