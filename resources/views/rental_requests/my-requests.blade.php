@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>{{ __('messages.my_rental_requests') }}</h2>

    @if($requests->isEmpty())
        <p class="text-muted">{{ __('messages.no_rental_requests_yet') }}</p>
    @else
        <div class="list-group mt-4">
            @foreach($requests as $request)
                <div class="list-group-item mb-3 shadow-sm">
                    <h5>{{ $request->item->name }}</h5>
                    <p><strong>{{ __('messages.dates') }}:</strong> {{ $request->start_date }} to {{ $request->end_date }}</p>
                    <p><strong>{{ __('messages.status') }}:</strong> 
                        <span class="badge 
                            @if($request->status == 'pending') bg-warning text-dark
                            @elseif($request->status == 'approved') bg-success
                            @else bg-danger
                            @endif">
                            {{ __( 'messages.' . $request->status ) }}
                        </span>
                    </p>

                    <p><strong>{{ __('messages.item_description') }}:</strong> {{ $request->item->description }}</p>
                    <p><strong>{{ __('messages.price_per_day') }}:</strong> ${{ number_format($request->item->price, 2) }}</p>

                    @php
                        $start = \Carbon\Carbon::parse($request->start_date);
                        $end = \Carbon\Carbon::parse($request->end_date);
                        $days = $start->diffInDays($end) + 1;
                        $totalCost = $days * $request->item->price;
                    @endphp

                    <p><strong>{{ __('messages.total_rental_days') }}:</strong> {{ $days }}</p>
                    <p><strong>{{ __('messages.total_cost') }}:</strong> ${{ number_format($totalCost, 2) }}</p>

                    <p><strong>{{ __('messages.request_created_at') }}:</strong> {{ $request->created_at->format('Y-m-d H:i') }}</p>

                    @if($request->status === 'pending')
                        <form action="{{ route('rental-requests.cancel', $request) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm">{{ __('messages.cancel_request') }}</button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
