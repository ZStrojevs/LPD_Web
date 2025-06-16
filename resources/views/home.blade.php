<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ __('messages.welcome') }} - {{ __('messages.home') }}</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
    <a class="navbar-brand" href="{{ route('home') }}">{{ __('messages.welcome') }}</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="{{ __('messages.toggle_navigation') }}">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown me-3">
                <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ strtoupper(app()->getLocale()) }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end p-3" aria-labelledby="languageDropdown" style="min-width: 150px;">
                    <li>
                        <form method="POST" action="{{ route('language.switch') }}">
                            @csrf
                            <select name="language" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>English</option>
                                <option value="ru" {{ app()->getLocale() === 'ru' ? 'selected' : '' }}>Русский</option>
                            </select>
                        </form>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('items.index') }}">{{ __('messages.browse_items') }}</a>
            </li>

            @auth
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('items.my') }}">{{ __('messages.my_items') }}</a></li>
                        <li><a class="dropdown-item" href="{{ route('rental-requests.index') }}">{{ __('messages.rental_requests_for_your_items') }}</a></li>
                        <li><a class="dropdown-item" href="{{ route('rental-requests.my') }}">{{ __('messages.my_rental_requests') }}</a></li>
                        @if(auth()->user()->role === 'admin')
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">{{ __('messages.admin_dashboard') }}</a></li>
                        @endif
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">{{ __('messages.logout') }}</button>
                            </form>
                        </li>
                    </ul>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('messages.login') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">{{ __('messages.register') }}</a>
                </li>
            @endauth
        </ul>
    </div>
</nav>

<div class="container my-5">
    @auth
        @if(auth()->user()->is_admin)
            <h1 class="mb-4">{{ __('messages.admin_dashboard') }}</h1>
            <div class="row">
                <div class="col-md-4">
                    <div class="card bg-primary text-white mb-3">
                        <div class="card-header">{{ __('messages.users') }}</div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $userCount ?? 0 }}</h5>
                            <p class="card-text">{{ __('messages.total_registered_users') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white mb-3">
                        <div class="card-header">{{ __('messages.items') }}</div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $itemCount ?? 0 }}</h5>
                            <p class="card-text">{{ __('messages.total_listed_items') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-warning text-white mb-3">
                        <div class="card-header">{{ __('messages.rental_requests') }}</div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $rentalRequestCount ?? 0 }}</h5>
                            <p class="card-text">{{ __('messages.total_rental_requests') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endauth

    <h1 class="text-center mb-4">{{ __('messages.welcome') }}</h1>
    <p class="text-center mb-5">{{ __('messages.find_and_rent') }}</p>

    <h2 class="mb-4">{{ __('messages.latest_items') }}</h2>
    <div class="row">
        @forelse ($items as $item)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top" alt="{{ $item->title }}" style="max-height: 200px; object-fit: cover;">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->title }}</h5>
                        <p class="card-text text-muted">{{ $item->description }}</p>
                        <p class="fw-bold">${{ number_format($item->price, 2) }}</p>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center">{{ __('messages.no_items_available') }}</p>
        @endforelse
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
