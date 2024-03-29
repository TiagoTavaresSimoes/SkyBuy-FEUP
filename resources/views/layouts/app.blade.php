<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        <link href="{{ url('css/milligram.min.css') }}" rel="stylesheet">
        <link href="{{ url('css/app.css') }}" rel="stylesheet">
        <script type="text/javascript">
            // Fix for Firefox autofocus CSS bug
            // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
        </script>
        <script type="text/javascript" src={{ url('js/app.js') }} defer>
        </script>
    </head>
    <body>
        <main>
            <header class="navbar">
                <a href="{{ route('home') }}" class="nav-logo">SkyBuy</a>
                <div class="nav-container">
                    <a href="{{ route('home') }}" class="nav-items">Home</a>
                    <a href="/categories" class="nav-items">Categories</a>
                    <a href="/cart" class="nav-items">Cart</a>
                    <a href="/faq" class="nav-faq">FAQ</a>
                    @auth
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="nav-items">Admin Page</a>
                        @endif
                        <a href="{{ route('checkout.index') }}" class="nav-items checkout-button">Go to Checkout</a>
                        <a href="{{ route('account') }}" class="nav-items">Profile</a>
                        <form action="{{ route('logout') }}" method="POST" class="nav-items" style="display: inline;">
                            @csrf
                            <button type="submit" class="login-button">Logout</button>
                        </form>
                        <span class="nav-items">{{ Auth::user()->name }}</span>
                    @else
                        <a href="{{ route('login') }}" class="login-button">Login</a>
                    @endauth
                </div>
                <div class="nav-search-container">
                    <form action="{{ route('search') }}" method="POST">
                        @csrf
                        <input type="text" name="search" placeholder="Search for products..." required>
                        <button type="submit">Search</button>
                    </form>
                </div>
            </header>
            <section id="content">
                @yield('content')
            </section>
            <footer>
                <!-- Footer Content -->
                <p>© 2023 SkyBuy. All rights reserved.</p>
            </footer>
        </main>
    </body>
</html>