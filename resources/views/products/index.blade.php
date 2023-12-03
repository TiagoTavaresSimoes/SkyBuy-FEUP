@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Categories</h1>
    <div>
        <a href="{{ route('categories.men') }}">Men's Products</a>
        <a href="{{ route('categories.women') }}">Women's Products</a>
    </div>
</div>
@endsection