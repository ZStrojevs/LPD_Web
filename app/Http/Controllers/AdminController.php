<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\RentalRequest;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Existing dashboard method
    public function dashboard()
    {
        $totalUsers = User::count();
        $activeRentals = RentalRequest::where('status', 'approved')->count();
        $pendingRentals = RentalRequest::where('status', 'pending')->count();

        $rentalRequests = RentalRequest::with('renter', 'item')->orderBy('created_at', 'desc')->get();

        $items = Item::with('user')->orderBy('created_at', 'desc')->get();
        return view('admin.dashboard', compact('totalUsers', 'activeRentals', 'pendingRentals', 'rentalRequests', 'items'));
    }

    // Delete rental request (existing)
    public function deleteRentalRequest(RentalRequest $rentalRequest)
    {
        $rentalRequest->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Rental request deleted.');
    }

    // Show all items (optionally, you can add pagination)
    public function items()
    {
        $items = Item::with('user')->orderBy('created_at', 'desc')->get();
        return view('admin.items.index', compact('items'));
    }

    // Show edit form for item
    public function editItem(Item $item)
    {
        return view('admin.items.edit', compact('item'));
    }

    // Update item
    public function updateItem(Request $request, Item $item)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:100',
        ]);

        $item->update($validated);

        return redirect()->route('admin.dashboard')->with('success', __('messages.item_updated'));
    }

    // Delete item
    public function deleteItem(Item $item)
    {
        $item->delete();

        return redirect()->route('admin.items')->with('success', 'Item deleted successfully.');
    }
}
