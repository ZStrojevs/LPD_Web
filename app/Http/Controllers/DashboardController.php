<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\RentalRequest;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Items owned by user
        $items = Item::where('user_id', $userId)->get();

        // Rental requests the user sent
        $sentRequests = RentalRequest::where('renter_id', $userId)
            ->with('item')
            ->orderBy('created_at', 'desc')
            ->get();

        // Rental requests received for user's items
        $receivedRequests = RentalRequest::whereHas('item', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->with('item', 'renter')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard', compact('items', 'sentRequests', 'receivedRequests'));
    }
}
