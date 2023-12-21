@extends('layouts.app')

@section('title', 'Dashboard Administrativo')

@section('content')
    <h1>Dashboard Administrador</h1>
    <div class="container">
        <header id="search-header" class="search-page-card">
            <h1>Painel Administrativo</h1><br>
        </header><br>

        <nav class="nav nav-pills nav-justified myNav" id="searchpage-nav" role="tablist">
            <a class="nav-item nav-link active text-white search-nav-bar-button left-nav-button" id="productResults" href="{{ route('admin.showProducts') }}" role="tab">Produtos</a>
            <a class="nav-item nav-link text-white search-nav-bar-button" id="userResults" href="{{ route('admin.showUsers') }}">Usuários</a>
            <a class="nav-item nav-link text-white search-nav-bar-button" id="orderResults" href="{{ route('admin.showOrders') }}">Pedidos</a>
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

