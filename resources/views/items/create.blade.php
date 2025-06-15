@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1>{{ __('messages.add_new_item') }}</h1>

    <form method="POST" action="{{ route('items.store') }}">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">{{ __('messages.title') }}</label>
            <input 
                type="text" 
                id="title" 
                name="title" 
                class="form-control" 
                required 
                value="{{ old('title') }}" 
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
            >{{ old('description') }}</textarea>
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
                value="{{ old('price') }}"
                placeholder="{{ __('messages.price_placeholder') }}"
            >
        </div>

        <div class="mb-3">
            <label for="category" class="form-label">{{ __('messages.category') }}</label>
            <select id="category" name="category" class="form-control" required>
                <option value="" disabled {{ old('category') ? '' : 'selected' }}>{{ __('messages.select_category') }}</option>
                <option value="Electronics" {{ old('category') == 'Electronics' ? 'selected' : '' }}>{{ __('messages.category_electronics') }}</option>
                <option value="Furniture" {{ old('category') == 'Furniture' ? 'selected' : '' }}>{{ __('messages.category_furniture') }}</option>
                <option value="Books" {{ old('category') == 'Books' ? 'selected' : '' }}>{{ __('messages.category_books') }}</option>
                <option value="Clothing" {{ old('category') == 'Clothing' ? 'selected' : '' }}>{{ __('messages.category_clothing') }}</option>
                <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>{{ __('messages.category_other') }}</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">{{ __('messages.save_item') }}</button>
        <a href="{{ route('items.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
    </form>
</div>
@endsection
