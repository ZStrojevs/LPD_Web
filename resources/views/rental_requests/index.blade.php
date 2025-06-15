@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>{{ __('messages.rental_requests_for_your_items') }}</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($requests->isEmpty())
        <p class="text-muted">{{ __('messages.no_rental_requests') }}</p>
    @else
        <div class="list-group mt-4">
            @foreach ($requests as $request)
                <div class="list-group-item mb-3 shadow-sm">
                    <h5><strong>{{ __('messages.item_name') }}:</strong> {{ $request->item->title }}</h5>
                    <p><strong>{{ __('messages.requested_by') }}:</strong> {{ $request->renter->name }}</p>
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

                    @if ($request->status === 'pending')
                        <form action="{{ route('rental-requests.approve', $request) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">{{ __('messages.approve') }}</button>
                        </form>

                        <form action="{{ route('rental-requests.reject', $request) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">{{ __('messages.reject') }}</button>
                        </form>
                    @elseif ($request->status === 'rejected')
                        <form action="{{ route('rental-requests.destroy', $request) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('messages.delete_rejected_confirm') }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">{{ __('messages.delete_request') }}</button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

