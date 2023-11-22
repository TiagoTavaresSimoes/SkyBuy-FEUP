@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('product') }}">
    {{ csrf_field() }}
    <head>
        <link rel="stylesheet" type="text/css" href="resources/css/product.css">
    </head>
    <body>
        <div class="barra-pesquisa">
            <input type="text" placeholder="Pesquisar...">
        </div>
        <div class="produto">
            <h2><?php echo $produto; ?></h2>
            <p class="preco"><?php echo $preco; ?></p>
            <p><?php echo $descricao; ?></p>
            <p><?php echo $envio; ?></p>
            <p><?php echo $entrega; ?></p>
            <p><?php echo $retorno; ?></p>
        </div>
    </body>
</form>
@endsection