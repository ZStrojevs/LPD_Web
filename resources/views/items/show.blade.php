@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="card-title mb-3">{{ $item->name }}</h2>
            <p class="card-text text-muted mb-4">{{ $item->description }}</p>
            <h5 class="mb-4">Price per day: <span class="text-success">${{ number_format($item->price_per_day, 2) }}</span></h5>

            @auth
                @if (auth()->id() === $item->owner_id)
                    <a href="{{ route('items.edit', $item) }}" class="btn btn-primary me-2">
                        <i class="bi bi-pencil"></i> Edit
                    </a>

                    <form action="{{ route('items.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this item?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                @endif
            @endauth

            <a href="{{ route('items.index') }}" class="btn btn-secondary mt-4">Back to Items</a>
        </div>
    </div>
</div>
@endsection
