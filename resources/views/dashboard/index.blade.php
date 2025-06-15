@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1>Your Dashboard</h1>

    <hr>
    <h3>Your Items</h3>
    @if($items->isEmpty())
        <p>You have no items listed.</p>
    @else
        <div class="row">
            @foreach($items as $item)
                <div class="col-md-4 mb-3">
                    <div class="card p-3">
                        <h5>{{ $item->title }}</h5>
                        <p>{{ $item->description }}</p>
                        <p><strong>Price:</strong> ${{ number_format($item->price, 2) }}</p>
                        <a href="{{ route('items.edit', $item) }}" class="btn btn-sm btn-warning">Edit</a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <hr>
    <h3>Rental Requests You Sent</h3>
    @if($sentRequests->isEmpty())
        <p>No rental requests sent.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sentRequests as $request)
                <tr>
                    <td>{{ $request->item->title }}</td>
                    <td>{{ $request->start_date }}</td>
                    <td>{{ $request->end_date }}</td>
                    <td>{{ ucfirst($request->status) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <hr>
    <h3>Rental Requests You Received</h3>
    @if($receivedRequests->isEmpty())
        <p>No rental requests received.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Renter</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($receivedRequests as $request)
                <tr>
                    <td>{{ $request->item->title }}</td>
                    <td>{{ $request->renter->name }}</td>
                    <td>{{ $request->start_date }}</td>
                    <td>{{ $request->end_date }}</td>
                    <td>{{ ucfirst($request->status) }}</td>
                    <td>
                        @if($request->status === 'pending')
                            <form action="{{ route('rental-requests.approve', $request) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-success btn-sm" type="submit">Approve</button>
                            </form>

                            <form action="{{ route('rental-requests.reject', $request) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-danger btn-sm" type="submit">Reject</button>
                            </form>
                        @else
                            <span>N/A</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
