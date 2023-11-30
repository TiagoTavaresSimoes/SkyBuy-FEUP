<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - SkyBuy</title>
    <link rel="stylesheet" href="checkout.css">
    
</head>
<body>

    <header class="navbar">
        <a href="/" class="nav-logo">SkyBuy</a>
        <div class="nav-container">
            <div class="nav-items">
                <a href="/categories">Categories</a>
                <a href="/account">Your Account</a>
                <a href="/cart">Cart</a>
            </div>
        </div>
    </header>

    <div class="checkout-container">
        <section class="cart-section">
            <h2>Your Cart</h2>
            <!-- Cart content goes here -->
            <!-- Example product -->
            <div class="cart-item">
                <img src="placeholder-product.jpg" alt="Product Image">
                <div class="item-details">
                    <h3>Product Name</h3>
                    <p>Product Description</p>
                    <span>Price: $XX.XX</span>
                </div>
                <button>Remove</button>
            </div>
            <!-- End of example product -->
        </section>

        <section class="checkout-section">
            <h2>Checkout</h2>
            <!-- Checkout form goes here -->
            <form action="/process_checkout" method="POST">
                <!-- Form fields for user details, shipping address, payment, etc. -->
                <!-- ... -->
                <button type="submit">Place Order</button>
            </form>
        </section>
    </div>

    <footer>
        <p>© 2023 SkyBuy. All rights reserved.</p>
    </footer>

</body>
</html>

