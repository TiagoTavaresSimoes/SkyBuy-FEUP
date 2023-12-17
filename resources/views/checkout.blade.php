<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - SkyBuy</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<header class="navbar">
    <a href="{{ route('home') }}" class="nav-logo">SkyBuy</a>
    <div class="nav-container">
        <div class="nav-items">
            <a href="{{ route('categories.index') }}">Categories</a>
            <a href="{{ route('account') }}">Your Account</a>
            <a href="{{ route('cart.index') }}">Cart</a>
        </div>
    </div>
</header>

<div class="checkout-container">
    <section class="cart-section">
        <h2>Your Cart</h2>
        @php $total = 0; @endphp
        @if(session('cart'))
            @foreach(session('cart') as $id => $details)
                @php $total += $details['price'] * $details['quantity']; @endphp
                <div class="cart-item">
                    <img src="{{ $details['photo'] ?? 'default-image-path.jpg' }}" alt="{{ $details['name'] }}">
                    <div class="item-details">
                        <h3>{{ $details['name'] }}</h3>
                        <p>{{ $details['description'] }}</p>
                        <span>Price: ${{ $details['price'] }}</span>
                        <span>Quantity: {{ $details['quantity'] }}</span>
                        <form action="{{ route('cart.remove', $id) }}" method="POST">
                            @csrf
                            <button type="submit">Remove</button>
                        </form>
                    </div>
                </div>
            @endforeach
            <div class="cart-total">
                <h3>Total: ${{ number_format($total, 2) }}</h3>
            </div>
        @else
            <p>Your cart is empty!</p>
        @endif
    </section>

    <section class="checkout-section">
        <h2>Checkout</h2>
        <form action="{{ route('checkout.processOrder') }}" method="POST">
            @csrf
            <button type="submit">Place Order</button>
        </form>
    </section>
</div>

<footer>
    <p>© 2023 SkyBuy. All rights reserved.</p>
</footer>

</body>
</html>

