<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with('user')->latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Check if category column exists before filtering
        if ($request->filled('category') && Schema::hasColumn('items', 'category')) {
            $query->where('category', $request->input('category'));
        }

        // **Exclude items that have ANY approved rental requests**
        $query->whereDoesntHave('rentalRequests', function ($q) {
            $q->where('status', 'approved')
              ->whereDate('start_date', '<=', Carbon::today())
              ->whereDate('end_date', '>=', Carbon::today());
        });

        $items = $query->get();

        // Get categories only if the column exists
        $categories = collect();
        if (Schema::hasColumn('items', 'category')) {
            $categories = Item::select('category')
                            ->whereNotNull('category')
                            ->distinct()
                            ->pluck('category');
        }

        return view('items.index', compact('items', 'categories'));
    }

    public function create()
    {
        return view('items.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'image'       => 'nullable|image|max:2048',
        ];

        if (Schema::hasColumn('items', 'category')) {
            $rules['category'] = 'nullable|string|max:255';
        }

        $request->validate($rules);

        $data = [
            'user_id'     => Auth::id(),
            'title'       => $request->title,
            'description' => $request->description,
            'price'       => $request->price,
        ];

        if (Schema::hasColumn('items', 'category') && $request->has('category')) {
            $data['category'] = $request->category;
        }

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $path = $request->file('image')->store('items', 'public'); 
            $data['image'] = $path;
        }

        Item::create($data);

        return redirect()->route('items.index')->with('success', 'Item created successfully.');
    }

    public function edit(Item $item)
    {
        if ($item->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('items.edit', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
        if ($item->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $rules = [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'image'       => 'nullable|image|max:2048', // max 2MB
        ];

        if (Schema::hasColumn('items', 'category')) {
            $rules['category'] = 'nullable|string|max:255';
        }

        $request->validate($rules);

        $data = [
            'title'       => $request->title,
            'description' => $request->description,
            'price'       => $request->price,
        ];

        if (Schema::hasColumn('items', 'category') && $request->has('category')) {
            $data['category'] = $request->category;
        }

        // Handle image upload and delete old image if any
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            // Delete old image if exists
            if ($item->image && Storage::disk('public')->exists($item->image)) {
                Storage::disk('public')->delete($item->image);
            }

            $path = $request->file('image')->store('items', 'public');
            $data['image'] = $path;
        }

        $item->update($data);

        return redirect()->route('items.index')->with('success', __('messages.item_updated'));
    }

    public function destroy(Item $item)
    {
        if ($item->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $item->delete();

        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }

    public function myItems()
    {
        $items = Item::where('user_id', Auth::id())->get();
        return view('items.my-items', compact('items'));
    }

    public function show(Item $item)
    {
        return view('items.show', compact('item'));
    }
}