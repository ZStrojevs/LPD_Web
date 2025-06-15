<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

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
            $q->where('status', 'approved');
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
        ];

        // Only validate category if the column exists
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

        // Only add category if the column exists
        if (Schema::hasColumn('items', 'category') && $request->has('category')) {
            $data['category'] = $request->category;
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
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'price'        => 'required|numeric|min:0',
        ];

        // Only validate category if the column exists
        if (Schema::hasColumn('items', 'category')) {
            $rules['category'] = 'nullable|string|max:255';
        }

        $request->validate($rules);

        $data = [
            'title'       => $request->title,
            'description' => $request->description,
            'price'       => $request->price,
        ];

        // Only update category if the column exists
        if (Schema::hasColumn('items', 'category') && $request->has('category')) {
            $data['category'] = $request->category;
        }

        $item->update($data);

        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
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