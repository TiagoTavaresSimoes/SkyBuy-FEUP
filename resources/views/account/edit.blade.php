@extends('layouts.app')

@section('title', 'Edit Account')

@section('content')
    <h1>Edit Account</h1>
    <form method="POST" action="{{ route('account.update') }}">
        @csrf
        @method('PUT')
        <label for="username">Username</label>
        <input id="username" type="text" name="username" value="{{ $user->username }}" required>

        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ $user->email }}" required>

        <label for="phone">Phone</label>
        <input id="phone" type="text" name="phone" value="{{ $user->phone }}">



        <button type="submit" class="button">Update</button>
    </form>
@endsection