@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>{{ __('messages.request_rental_for') }}: {{ $item->title }}</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('rental-requests.store') }}" method="POST" class="mt-4">
        @csrf
        <input type="hidden" name="item_id" value="{{ $item->id }}">

        <div class="mb-3">
            <label for="start_date" class="form-label">{{ __('messages.start_date') }}</label>
            <input type="date" name="start_date" class="form-control" required>
            @error('start_date')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="end_date" class="form-label">{{ __('messages.end_date') }}</label>
            <input type="date" name="end_date" class="form-control" required>
            @error('end_date')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">{{ __('messages.submit_request') }}</button>
    </form>
</div>
@endsection
