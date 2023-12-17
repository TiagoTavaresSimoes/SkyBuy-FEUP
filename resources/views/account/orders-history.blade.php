@foreach ($orders as $order)
    <div>
        <p>Pedido ID: {{ $order->id_purchase }}</p>
        <p>Data do Pedido: {{ $order->order_date }}</p>
        <p>Status: {{ $order->order_status }}</p>
        {{-- Adicione mais informações do pedido conforme necessário --}}
    </div>
@endforeach

@if ($orders->isEmpty())
    <p>Você ainda não efetuou nenhuma compra.</p>
@else
    {{-- Exiba os pedidos aqui --}}
@endif