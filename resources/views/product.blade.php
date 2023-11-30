<!DOCTYPE html>
<html lang="en-US">
    {{ csrf_field() }}
  <head>
    <title>Product page</title>
    <link href="{{ url('css/milligram.min.css') }}" rel="stylesheet">
    <link href="{{ url('css/app.css') }}" rel="stylesheet">
    <link href="{{ url('css/product.css') }}" rel="stylesheet">
    
    <script type="text/javascript">
        // Fix for Firefox autofocus CSS bug
        // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
    </script>
    <script type="text/javascript" src={{ url('js/app.js') }} defer>
    </script>
  </head>
  <body>
    <header class="navbar">
        <a href="{{ route('home') }}" class="nav-logo">SkyBuy</a>
        <div class="nav-container">
            <!-- Links da navegação -->
            <a href="{{ route('home') }}" class="nav-items">Home</a>
            <a href="/categories" class="nav-items">Categories</a>
            <a href="{{ route('account') }}">Your Account</a>
            <a href="/cart" class="nav-items">Cart</a>

            <!-- Botão de Login/Logout -->
            @if (Auth::check())
                <a href="{{ route('profile.index') }}" class="nav-items">Profile</a>
                <form action="{{ route('logout') }}" method="POST" class="nav-items" style="display: inline;">
                    @csrf
                    <button type="submit" class="login-button">Logout</button>
                </form>
                <span class="nav-items">{{ Auth::user()->name }}</span>
            @else
                <a href="{{ route('login') }}" class="login-button">Login</a>
            @endif
        </div>
    </header>
    <div class="produto">
        <h2><?php echo $name; ?></h2>
        <p>Marca: <?php echo $brand; ?></p>
        <p>Tamanho: <?php echo $size; ?></p>
        <p>Rating: <?php echo $rating; ?></p>
        <p class="preco">Preço: <?php echo $price; ?></p>
        <p>Descrição: <?php echo $description; ?></p>

        
    </div>
  </body>
</html>
