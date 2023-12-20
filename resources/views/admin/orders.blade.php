@extends('layouts.app')

@section('title', 'Histórico de Compras do Usuário')

@section('content')
<div class="container">
    <h1>Histórico de Compras</h1>
    @foreach ($orders as $order)
        <div>
            <p>Pedido ID: {{ $order->id_purchase }}</p>
            <p>Data do Pedido: {{ $order->order_date }}</p>
            <p>Status: {{ $order->order_status }}</p>
        </div>
    @endforeach

    @if ($orders->isEmpty())
        <p>Este usuário ainda não efetuou nenhuma compra.</p>
    @endif
</div>
@endsection