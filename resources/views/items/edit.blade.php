@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1>Edit Item</h1>

    <form method="POST" action="{{ route('items.update', $item) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" id="name" name="name" class="form-control" required value="{{ old('name', $item->name) }}">
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" class="form-control">{{ old('description', $item->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="price_per_day" class="form-label">Price per Day ($)</label>
            <input type="number" id="price_per_day" name="price_per_day" class="form-control" required step="0.01" min="0" value="{{ old('price_per_day', $item->price_per_day) }}">
        </div>

        <button type="submit" class="btn btn-primary">Update Item</button>
        <a href="{{ route('items.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
