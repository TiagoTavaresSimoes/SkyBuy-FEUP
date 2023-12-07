@extends('layouts.app')

@section('title', 'Your Account')

@section('content')
    <h1>Your Account Details</h1>
    <div>
        <p><strong>Username:</strong> {{ $user->username }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Phone:</strong> {{ $user->phone }}</p>
        <p><strong>Profile Picture:</strong></p>
        <img src="{{ asset($user->profile_pic) }}" alt="Profile Picture" style="width: 150px; height: 150px;">
        <p><strong>Status:</strong> {{ $user->is_banned ? 'Banned' : 'Active' }}</p>
        <a href="{{ route('account.edit') }}" class="button">Edit Profile</a>
    </div>
@endsection