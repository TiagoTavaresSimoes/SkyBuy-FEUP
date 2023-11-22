@extends('layouts.app')

@section('content')
<div class="container">
@if(Auth::id() != $user->id)


<div class="user-profile">
        
        <div class="user-details">
            <br>
            <h2>{{ $user->username }},</h2>
            <img src="{{ asset($user->avatar ?? 'images/defaultAvatar.jpg') }}" alt="User avatar" class="user-avatar">
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Address:</strong> {{ $user->address }}</p>
            <p><strong>Postal Code:</strong> {{ $user->postalcode }}</p>
            <p><strong>Phone Number:</strong> {{ $user->phonenumber }}</p>
        </div>
    </div>
<div class="alert alert-danger">This profile is not yours, you cannot edit it!</div>
@endif

@if($user->type == "bidder")
<h2>Bidding Auctions:</h2>
    <ul>
        @foreach ($biddingAuctions as $auction)
            <li>
            <p>Auction Title: {{ $auction->title }}</p>
                <p>Description: {{ $auction->description }}</p>
                <p>Duration: {{ $auction->duration}} min</p>
            </li>
        @endforeach
    </ul>
@endif

@if($user->type == "auctionowner" and Auth::id() == $user->id)
<a href="{{ route('createAuctionView') }}" class="createAuctionButton">Create New Auction</a>

<h2>Owned Auctions:</h2>
    <ul>
        @foreach ($ownedAuctions as $auction)
            <li>
                <p>Auction Title: {{ $auction->title }}</p>
                <p>Description: {{ $auction->description }}</p>
                <p>Duration: {{ $auction->duration}} min</p>
            </li>
        @endforeach
    </ul>
@endif



@if(Auth::id() == $user->id)



    <div class="user-profile">
        
        <div class="user-details">
            <br>
            <h2>Hello {{ $user->username }},</h2>
            <img src="{{ asset($user->avatar ?? 'images/defaultAvatar.jpg') }}" alt="User avatar" class="user-avatar">
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Address:</strong> {{ $user->address }}</p>
            <p><strong>Postal Code:</strong> {{ $user->postalcode }}</p>
            <p><strong>Phone Number:</strong> {{ $user->phonenumber }}</p>
        </div>
    </div>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <button id="edit-user" type="submit" class="btn btn-primary">Edit your profile</button>
    @endif
</div>
@endsection