<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Http\Controllers\Controller;
use App\Models\RentalRequest;
use Illuminate\Http\Request;

class RentalRequestController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        
        RentalRequest::create([
            'item_id' => $request->item_id,
            'renter_id' => auth()->id(),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'pending',
        ]);
        
        return back()->with('success', 'Rental request submitted.');
    }
    
    public function index()
    {
        $requests = RentalRequest::whereHas('item', function ($q) {
            $q->where('user_id', auth()->id());
        })->with('renter', 'item')->get();
        
        return view('rental_requests.index', compact('requests'));
    }
    
    public function approve(RentalRequest $rentalRequest)
    {
        if (auth()->user()->role !== 'admin') {
            $this->authorize('update', $rentalRequest);
        }

        $rentalRequest->update(['status' => 'approved']);

        RentalRequest::where('item_id', $rentalRequest->item_id)
            ->where('id', '!=', $rentalRequest->id)
            ->where('status', 'pending')
            ->where(function ($query) use ($rentalRequest) {
                $query->whereBetween('start_date', [$rentalRequest->start_date, $rentalRequest->end_date])
                    ->orWhereBetween('end_date', [$rentalRequest->start_date, $rentalRequest->end_date])
                    ->orWhere(function ($q) use ($rentalRequest) {
                        $q->where('start_date', '<=', $rentalRequest->start_date)
                            ->where('end_date', '>=', $rentalRequest->end_date);
                    });
            })
            ->update(['status' => 'rejected']);

        return back()->with('success', 'Rental request approved. Conflicting requests have been rejected.');
    }
    
    public function create(Item $item)
    {
        return view('rental_requests.create', compact('item'));
    }
    
    public function myRequests()
    {
        $requests = RentalRequest::where('renter_id', auth()->id())
            ->with('item')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('rental_requests.my-requests', compact('requests'));
    }
    
    public function destroy(RentalRequest $rentalRequest)
    {
        if (auth()->user()->role !== 'admin') {
            $this->authorize('delete', $rentalRequest);
            
            if ($rentalRequest->status !== 'rejected') {
                return back()->withErrors('Only rejected requests can be deleted.');
            }
        }
        
        $rentalRequest->delete();
        return back()->with('success', 'Rental request deleted.');
    }
    
    public function cancel(RentalRequest $rentalRequest)
    {
        if ($rentalRequest->renter_id !== auth()->id()) {
            abort(403);
        }
        
        if ($rentalRequest->status !== 'pending') {
            return back()->withErrors('Only pending requests can be canceled.');
        }
        
        $rentalRequest->delete();
        return back()->with('success', 'Your rental request has been canceled.');
    }
    public function dashboard()
    {
        $user = auth()->user();

        $userItems = Item::where('user_id', $user->id)
            ->withCount('rentalRequests')
            ->get();

        $incomingRequests = RentalRequest::whereHas('item', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with(['renter', 'item'])->orderBy('created_at', 'desc')->get();

        $myRequests = RentalRequest::where('renter_id', $user->id)
            ->with('item')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard', compact('userItems', 'incomingRequests', 'myRequests'));
    }
    public function reject(RentalRequest $rentalRequest)
    {
        if (auth()->user()->role !== 'admin') {
            $this->authorize('update', $rentalRequest);
        }

        if ($rentalRequest->status !== 'pending') {
            return back()->withErrors('Only pending requests can be rejected.');
        }

        $rentalRequest->update(['status' => 'rejected']);

        return back()->with('success', 'Rental request rejected.');
    }
}