@foreach ($orders as $order)
    <div>
        <p>Pedido ID: {{ $order->id_purchase }}</p>
        <p>Data do Pedido: {{ $order->order_date }}</p>
        <p>Status: {{ $order->order_status }}</p>
    </div>
@endforeach

@if ($orders->isEmpty())
    <p>Você ainda não efetuou nenhuma compra.</p>
@else
@endif