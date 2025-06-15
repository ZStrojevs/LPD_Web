@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1>{{ __('messages.admin_dashboard') }}</h1>

    <p>{{ __('messages.total_users') }}: {{ $totalUsers }}</p>
    <p>{{ __('messages.active_rentals') }}: {{ $activeRentals }}</p>
    <p>{{ __('messages.pending_rentals') }}: {{ $pendingRentals }}</p>

    <hr>
    <h3>{{ __('messages.all_rental_requests') }}</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($rentalRequests->isEmpty())
        <p>{{ __('messages.no_rental_requests_found') }}</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('messages.item') }}</th>
                    <th>{{ __('messages.renter') }}</th>
                    <th>{{ __('messages.start_date') }}</th>
                    <th>{{ __('messages.end_date') }}</th>
                    <th>{{ __('messages.status') }}</th>
                    <th>{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rentalRequests as $request)
                <tr>
                    <td>{{ $request->item->title }}</td>
                    <td>{{ $request->renter->name }}</td>
                    <td>{{ $request->start_date }}</td>
                    <td>{{ $request->end_date }}</td>
                    <td>{{ ucfirst($request->status) }}</td>
                    <td>
                        <form action="{{ route('admin.rental-requests.delete', $request) }}" method="POST" onsubmit="return confirm('{{ __('messages.confirm_delete_request') }}');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" type="submit">{{ __('messages.delete') }}</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <hr>
    <h3>{{ __('messages.all_user_items') }}</h3>

    @if($items->isEmpty())
        <p>{{ __('messages.no_items_found') }}</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>{{ __('messages.title') }}</th>
                    <th>{{ __('messages.owner') }}</th>
                    <th>{{ __('messages.price') }}</th>
                    <th>{{ __('messages.category') }}</th>
                    <th>{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->user->name ?? __('messages.unknown') }}</td>
                    <td>${{ number_format($item->price, 2) }}</td>
                    <td>{{ $item->category ?? __('messages.uncategorized') }}</td>
                    <td>
                        <a href="{{ route('admin.items.edit', $item) }}" class="btn btn-warning btn-sm">{{ __('messages.edit') }}</a>

                        <form action="{{ route('admin.items.delete', $item) }}" method="POST" style="display:inline-block" onsubmit="return confirm('{{ __('messages.confirm_delete_item') }}');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" type="submit">{{ __('messages.delete') }}</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
