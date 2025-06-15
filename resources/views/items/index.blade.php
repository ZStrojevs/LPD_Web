@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1>{{ __('messages.items') ?? 'Items' }}</h1>

    @auth
        <a href="{{ route('items.create') }}" class="btn btn-primary mb-3">{{ __('messages.add_new_item') }}</a>
    @endauth

    {{-- Search + Filter --}}
    <form method="GET" action="{{ route('items.index') }}" class="mb-4 row g-3">
        <div class="col-md-5">
            <input 
                type="text" 
                name="search" 
                class="form-control" 
                placeholder="{{ __('messages.search_placeholder') ?? 'Search by title or description...' }}" 
                value="{{ request('search') }}"
            >
        </div>
        <div class="col-md-4">
            <select name="category" class="form-control">
                <option value="">{{ __('messages.all_categories') ?? 'All Categories' }}</option>
                <option value="Books" {{ request('category') == 'Books' ? 'selected' : '' }}>{{ __('messages.category_books') ?? 'Books' }}</option>
                <option value="Electronics" {{ request('category') == 'Electronics' ? 'selected' : '' }}>{{ __('messages.category_electronics') ?? 'Electronics' }}</option>
                <option value="Clothing" {{ request('category') == 'Clothing' ? 'selected' : '' }}>{{ __('messages.category_clothing') ?? 'Clothing' }}</option>
                <option value="Furniture" {{ request('category') == 'Furniture' ? 'selected' : '' }}>{{ __('messages.category_furniture') ?? 'Furniture' }}</option>
                <option value="Other" {{ request('category') == 'Other' ? 'selected' : '' }}>{{ __('messages.category_other') ?? 'Other' }}</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-outline-primary">{{ __('messages.search') ?? 'Search' }}</button>
        </div>
    </form>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($items->isEmpty())
        <p>{{ __('messages.no_items_found') ?? 'No items found.' }}</p>
    @else
        <div class="row">
            @foreach ($items as $item)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">{{ $item->title }}</h5>
                            <p class="card-text">{{ $item->description }}</p>
                            <p class="fw-bold">${{ number_format($item->price, 2) }}</p>
                            <p class="text-muted">{{ __('messages.category') ?? 'Category:' }} {{ $item->category ?? __('messages.uncategorized') }}</p>
                            <p class="text-muted">{{ __('messages.owner') ?? 'Owner:' }} {{ $item->user?->name ?? __('messages.unknown') }}</p>
                        </div>
                        @auth
                            <div class="card-footer d-flex justify-content-between">
                                @if(auth()->id() === $item->user_id)
                                    <a href="{{ route('items.edit', $item) }}" class="btn btn-sm btn-warning">{{ __('messages.edit') }}</a>
                                    <form action="{{ route('items.destroy', $item) }}" method="POST" onsubmit="return confirm('{{ __('messages.are_you_sure') ?? 'Are you sure?' }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">{{ __('messages.delete') }}</button>
                                    </form>
                                @else
                                    <a href="{{ route('rental-requests.create', $item->id) }}" class="btn btn-sm btn-primary w-100">
                                        {{ __('messages.request_rental') ?? 'Request Rental' }}
                                    </a>
                                @endif
                            </div>
                        @endauth

                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
