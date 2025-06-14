@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1>My Items</h1>

    <a href="{{ route('items.create') }}" class="btn btn-primary mb-3">Add New Item</a>

    @if($items->isEmpty())
        <div class="alert alert-info">
            You have no items yet.
            <a href="{{ route('items.create') }}" class="btn btn-sm btn-success ms-2">Add your first item</a>
        </div>
    @else
        <div class="row">
            @foreach ($items as $item)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">{{ $item->name }}</h5>
                            <p class="card-text">{{ $item->description }}</p>
                            <p class="fw-bold">${{ number_format($item->price_per_day, 2) }}</p>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <a href="{{ route('items.edit', $item) }}" class="btn btn-sm btn-warning">Edit</a>

                            <form action="{{ route('items.destroy', $item) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
