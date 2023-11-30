@extends('layouts.app')

@section('title', 'FAQ - Perguntas Frequentes')

@section('content')
    <div class="faq-container">
        <h1>Perguntas Frequentes (FAQ)</h1>
        @foreach ($faqs as $faq)
            <div class="faq-item">
                <h2>{{ $faq->question }}</h2>
                <p>{{ $faq->answer }}</p>
            </div>
        @endforeach
    </div>

    <footer>
        <!-- Footer Content -->
        <p>© 2023 SkyBuy. All rights reserved.</p>
    </footer>
@endsection