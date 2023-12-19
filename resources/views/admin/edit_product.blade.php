@extends('layouts.app')

@section('title', 'Editar Produto')

@section('content')
<div class="container">
    <h1>Editar Produto</h1>
    <form method="POST" action="{{ route('admin.updateProduct', $product->id_product) }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Nome do Produto</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $product->name }}" required>
        </div>
        <div class="form-group">
            <label for="price">Preço</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ $product->price }}" required>
        </div>
        <div class="form-group">
            <label for="stock">Stock</label>
            <input type="number" class="form-control" id="stock" name="stock" value="{{ $product->stock }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    </form>
</div>
@endsection