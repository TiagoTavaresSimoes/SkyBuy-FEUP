<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Auction;
use App\Models\Bid;


class UserProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function showProfile(int $id)
    {
        $user = User::find($id);

        $biddingAuctions = Auction::whereIn('id', Bid::distinct('auction_id')
            ->where('bidder', $id)
            ->get()->map->only(['auction_id'])
        )->get();
        
        $ownedAuctions = Auction::where('owner_id', $user->id)
        ->get();

        return view('profile', [
            'user' => $user,
            'ownedAuctions' => $ownedAuctions,
            // 'profileImage' => $profileImage,
            'biddingAuctions' => $biddingAuctions
        ]);
    }

    public function createAuctionView()
    {
        return view('pages.auctionCreate');
    }

    public function createAuction(Request $request)
    {

        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|numeric',
            'minvalue' => 'required|numeric'
        ];

        $request->validate($rules);

        Auction::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'datecreated' => now(),
            'minvalue' => $request->input('minvalue'),
            'duration' => $request->input('duration'),
            'owner_id' => Auth::id(),
        ]);

        return redirect()->route('users.showProfile', ['id' => Auth::id()])->with('success', 'Auction created successfully');
    }

    public function edit($id)
    {
        /*
        if (Auth::user()->id != $id) {
            return redirect('/users/' . $id);
        }*/

        $user = Auth::user();
        return view('pages.profileEdit', ['user' => $user]);
    }

    public function submitEdit(Request $request, $id)
    {
        /*
        if (Auth::user()->id != $id) {
            return redirect('/users/' . $id);
        }*/

        $user = Auth::user();

        try {
            $user->username = $request->input('name');
            $user->address = $request->input('address');
            $user->postalcode = $request->input('postalcode');
            $user->phonenumber = $request->input('phonenumber');

            $user->save();

            return redirect('/users/' . $id)->with('success', 'Profile updated successfully!');
        } catch (QueryException $qe) {
            $errors = new MessageBag();
            $errors->add('An error occurred', "There was a problem editing profile information. Try again!");
            Log::error($qe->getMessage());
            return redirect('/users/' . $id)->withErrors($errors);
        }
    }
}