@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Women's Products</h1>
    @foreach($womenProducts as $product)
        <div class="product">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
            <h2>{{ $product->name }}</h2>
            <p>{{ $product->description }}</p>
            <p>Price: ${{ $product->price }}</p>
        </div>
    @endforeach
</div>
@endsection