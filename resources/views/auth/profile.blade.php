@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>{{ $user->name }}'s Profile</h2>
        <p>Email: {{ $user->email }}</p>
        <p>Address: {{ $user->address ?? 'Not provided' }}</p>
    </div>
@endsection

