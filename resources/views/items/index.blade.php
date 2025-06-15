@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1>{{ __('messages.items') }}</h1>

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
                placeholder="{{ __('messages.search_placeholder') }}" 
                value="{{ request('search') }}"
            >
        </div>
        <div class="col-md-4">
            <select name="category" class="form-control">
                <option value="">{{ __('messages.all_categories') }}</option>
                <option value="Books" {{ request('category') == 'Books' ? 'selected' : '' }}>{{ __('messages.category_books') }}</option>
                <option value="Electronics" {{ request('category') == 'Electronics' ? 'selected' : '' }}>{{ __('messages.category_electronics') }}</option>
                <option value="Clothing" {{ request('category') == 'Clothing' ? 'selected' : '' }}>{{ __('messages.category_clothing') }}</option>
                <option value="Furniture" {{ request('category') == 'Furniture' ? 'selected' : '' }}>{{ __('messages.category_furniture') }}</option>
                <option value="Other" {{ request('category') == 'Other' ? 'selected' : '' }}>{{ __('messages.category_other') }}</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-outline-primary">{{ __('messages.search') }}</button>
        </div>
    </form>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($items->isEmpty())
        <p>{{ __('messages.no_items_found') }}</p>
    @else
        <div class="row">
            @foreach ($items as $item)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        {{-- Show image if it exists --}}
                        @if($item->image)
                            <img 
                                src="{{ asset('storage/' . $item->image) }}" 
                                alt="{{ $item->title }}" 
                                class="card-img-top" 
                                style="height: 200px; object-fit: cover;"
                            >
                        @endif

                        <div class="card-body">
                            <h5 class="card-title">{{ $item->title }}</h5>
                            <p class="card-text">{{ $item->description }}</p>
                            <p class="fw-bold">${{ number_format($item->price, 2) }}</p>
                            <p class="text-muted">{{ __('messages.category') }}: {{ $item->category ?? __('messages.uncategorized') }}</p>
                            <p class="text-muted">{{ __('messages.owner') }}: {{ $item->user?->name ?? __('messages.unknown') }}</p>
                        </div>

                        @auth
                            <div class="card-footer d-flex justify-content-between align-items-center">
                                @if(auth()->id() === $item->user_id)
                                    <a href="{{ route('items.edit', $item) }}" class="btn btn-sm btn-warning">{{ __('messages.edit') }}</a>
                                    <form action="{{ route('items.destroy', $item) }}" method="POST" onsubmit="return confirm('{{ __('messages.are_you_sure') }}');" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">{{ __('messages.delete') }}</button>
                                    </form>
                                @else
                                    <a href="{{ route('rental-requests.create', $item->id) }}" class="btn btn-sm btn-primary w-100">
                                        {{ __('messages.request_rental') }}
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
