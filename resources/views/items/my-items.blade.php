@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1>{{ __('messages.my_items') ?? 'My Items' }}</h1>

    <a href="{{ route('items.create') }}" class="btn btn-primary mb-3">{{ __('messages.add_new_item') ?? 'Add New Item' }}</a>

    @if($items->isEmpty())
        <div class="alert alert-info">
            {{ __('messages.no_items_yet') ?? 'You have no items yet.' }}
            <a href="{{ route('items.create') }}" class="btn btn-sm btn-success ms-2">{{ __('messages.add_your_first_item') ?? 'Add your first item' }}</a>
        </div>
    @else
        <div class="row">
            @foreach ($items as $item)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">{{ $item->title }}</h5>
                            {{-- no description --}}
                            @if(!empty($item->price) && $item->price > 0)
                                <p class="fw-bold">${{ number_format($item->price, 2) }}</p>
                            @endif
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <a href="{{ route('items.edit', $item) }}" class="btn btn-sm btn-warning">{{ __('messages.edit') ?? 'Edit' }}</a>

                            <form action="{{ route('items.destroy', $item) }}" method="POST" onsubmit="return confirm('{{ __('messages.are_you_sure') ?? 'Are you sure?' }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">{{ __('messages.delete') ?? 'Delete' }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

