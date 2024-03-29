@extends('layouts.app')

@section('content')
<div class="container categories-container">
    <h1>Categories</h1>
    <div class="row">
        <div class="column">
            <h2 class="category-title">Men's Products</h2>
            @foreach($maleProducts as $product)
                <div class="product">
                    
                    <p>{{ $product->name }}</p>
                    
                </div>
            @endforeach
        </div>
        <div class="column">
            <h2 class="category-title">Women's Products</h2>
            @foreach($femaleProducts as $product)
                <div class="product">
                    
                    <p>{{ $product->name }}</p>
                    
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection