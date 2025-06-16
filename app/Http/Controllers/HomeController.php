<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\User;
use App\Models\RentalRequest;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $items = Item::latest()
            ->whereDoesntHave('rentalRequests', function ($query) {
                $query->where('status', 'approved');
            })
            ->take(6)
            ->get();

        $userCount = User::count();
        $itemCount = Item::count();
        $rentalRequestCount = RentalRequest::count();

        return view('home', compact('items', 'userCount', 'itemCount', 'rentalRequestCount'));
    }

}
