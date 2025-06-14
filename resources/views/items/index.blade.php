@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Items</h1>

    <a href="{{ route('items.create') }}" class="btn btn-primary mb-3">Add New Item</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($items->isEmpty())
        <p>No items found.</p>
    @else
        <div class="row">
            @foreach ($items as $item)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">{{ $item->title }}</h5>
                            <p class="card-text">{{ $item->description }}</p>
                            <p class="fw-bold">${{ number_format($item->price, 2) }}</p>
                            <p class="text-muted">Owner: {{ $item->user?->name ?? 'Unknown' }}</p>
                        </div>
                        @auth
                            @if(auth()->user()->id === $item->user_id)
                                <div class="card-footer d-flex justify-content-between">
                                    <a href="{{ route('items.edit', $item) }}" class="btn btn-sm btn-warning">Edit</a>

                                    <form action="{{ route('items.destroy', $item) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
