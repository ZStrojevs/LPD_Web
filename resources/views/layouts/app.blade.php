<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <title>@yield('title', 'RentLoop')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
    <a class="navbar-brand" href="{{ route('home') }}">RentLoop</a>
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
                                <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>
                                <option value="ru" {{ app()->getLocale() == 'ru' ? 'selected' : '' }}>Русский</option>
                            </select>
                        </form>
                    </li>
                </ul>
            </li>

            <li class="nav-item"><a class="nav-link" href="{{ route('items.index') }}">{{ __('messages.browse_items') }}</a></li>

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
                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">{{ __('messages.login') }}</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">{{ __('messages.register') }}</a></li>
            @endauth
        </ul>
    </div>
</nav>

@yield('content')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
