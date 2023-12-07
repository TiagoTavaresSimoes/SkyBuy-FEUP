@extends('layouts.app')

@section('title', 'Dashboard Administrativo')

@section('content')
    <h1>Dashboard Administrativo</h1>
    <div class="container">
        <header id="search-header" class="search-page-card">
            <h1>Painel Administrativo SkyBuy</h1><br>
            <input type="search" id="search" placeholder="Procurar..."></input>
        </header><br>

        <nav class="nav nav-pills nav-justified myNav" id="searchpage-nav" role="tablist">
            <a class="nav-item nav-link active text-white search-nav-bar-button left-nav-button" id="productResults" data-toggle="pill" href="#results-products" role="tab">Produtos</a>
            <a class="nav-item nav-link text-white search-nav-bar-button" id="userResults" data-toggle="pill" href="#results-users" role="tab">Usuários</a>
            <a class="nav-item nav-link text-white search-nav-bar-button" id="orderResults" data-toggle="pill" href="#results-orders" role="tab">Pedidos</a>
            <a class="nav-item nav-link text-white search-nav-bar-button right-nav-button" id="reviewResults" data-toggle="pill" href="#results-reviews" role="tab">Avaliações</a>
        </nav>
        
        <div class="tab-content">
            <section class="tab-pane show active" id="results-products" role="tabpanel">

            </section>
            <section class="tab-pane" id="results-users" role="tabpanel">

            </section>
            <section class="tab-pane" id="results-orders" role="tabpanel">

            </section>
            <section class="tab-pane" id="results-reviews" role="tabpanel">

            </section>
        </div>
    </div>
@endsection