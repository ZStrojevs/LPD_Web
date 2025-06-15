@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1>{{ __('messages.edit_item') }}</h1>

    <form method="POST" action="{{ route('items.update', $item) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">{{ __('messages.title') }}</label>
            <input 
                type="text" 
                id="title" 
                name="title" 
                class="form-control" 
                required 
                value="{{ old('title', $item->title) }}"
                placeholder="{{ __('messages.title_placeholder') }}"
            >
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">{{ __('messages.description') }}</label>
            <textarea 
                id="description" 
                name="description" 
                class="form-control"
                placeholder="{{ __('messages.description_placeholder') }}"
            >{{ old('description', $item->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">{{ __('messages.price') }} ($)</label>
            <input 
                type="number" 
                id="price" 
                name="price" 
                class="form-control" 
                required 
                step="0.01" 
                min="0" 
                value="{{ old('price', $item->price) }}"
                placeholder="{{ __('messages.price_placeholder') }}"
            >
        </div>

        <div class="mb-3">
            <label for="category" class="form-label">{{ __('messages.category') }}</label>
            <select id="category" name="category" class="form-control" required>
                <option value="" disabled {{ old('category', $item->category) ? '' : 'selected' }}>
                    {{ __('messages.select_category') }}
                </option>
                <option value="Electronics" {{ old('category', $item->category) == 'Electronics' ? 'selected' : '' }}>
                    {{ __('messages.category_electronics') }}
                </option>
                <option value="Furniture" {{ old('category', $item->category) == 'Furniture' ? 'selected' : '' }}>
                    {{ __('messages.category_furniture') }}
                </option>
                <option value="Books" {{ old('category', $item->category) == 'Books' ? 'selected' : '' }}>
                    {{ __('messages.category_books') }}
                </option>
                <option value="Clothing" {{ old('category', $item->category) == 'Clothing' ? 'selected' : '' }}>
                    {{ __('messages.category_clothing') }}
                </option>
                <option value="Other" {{ old('category', $item->category) == 'Other' ? 'selected' : '' }}>
                    {{ __('messages.category_other') }}
                </option>
            </select>
        </div>

        {{-- New image upload field --}}
        <div class="mb-3">
            <label for="image" class="form-label">{{ __('messages.image') }}</label>
            @if($item->image)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" style="max-width: 150px; max-height: 150px;">
                </div>
            @endif
            <input 
                type="file" 
                id="image" 
                name="image" 
                class="form-control"
                accept="image/*"
            >
            <small class="form-text text-muted">{{ __('messages.image_help') }}</small>
        </div>

        <button type="submit" class="btn btn-primary">{{ __('messages.update_item') }}</button>
        <a href="{{ route('items.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
    </form>
</div>
@endsection
