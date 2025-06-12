<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - GivnGet</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="http://pyhjavd-m2.wsr.ru/public/css/fonts.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="http://pyhjavd-m2.wsr.ru/public/css/custom.css">

    <!-- Styles -->
    <link rel="stylesheet" href="http://pyhjavd-m2.wsr.ru/public/css/bootstrap.css">
    <link rel="stylesheet" href="http://pyhjavd-m2.wsr.ru/public/css/bootstrap-grid.css">
    <style>
        :root {
            --primary-color: #2D3436;
            --secondary-color: #636E72;
            --accent-color: #00B894;
            --background-color: #F5F6FA;
            --card-color: #FFFFFF;
            --text-color: #2D3436;
            --text-muted: #636E72;
            --border-color: #DFE6E9;
            --hover-color: #00CEC9;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
        }

        .navbar {
            background-color: var(--card-color);
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .navbar-brand {
            color: var(--primary-color) !important;
            font-weight: 600;
        }

        .nav-link {
            color: var(--text-color) !important;
            font-weight: 500;
        }

        .nav-link:hover {
            color: var(--accent-color) !important;
        }

        .btn-primary {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        .btn-primary:hover {
            background-color: var(--hover-color);
            border-color: var(--hover-color);
        }

        .btn-outline-primary {
            color: var(--accent-color);
            border-color: var(--accent-color);
        }

        .btn-outline-primary:hover {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        .card {
            background-color: var(--card-color);
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .text-primary {
            color: var(--accent-color) !important;
        }

        .bg-primary {
            background-color: var(--accent-color) !important;
        }

        .border-primary {
            border-color: var(--accent-color) !important;
        }

        .text-muted {
            color: var(--text-muted) !important;
        }

        .footer {
            background-color: var(--card-color);
            border-top: 1px solid var(--border-color);
        }

        .profile-image {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            background-color: var(--background-color);
        }

        .profile-image-placeholder {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--background-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
        }

        .badge {
            font-weight: 500;
            padding: 0.5em 0.75em;
        }

        .badge.bg-success {
            background-color: var(--accent-color) !important;
        }

        .pagination .page-link {
            color: var(--accent-color);
            border-color: var(--border-color);
        }

        .pagination .page-item.active .page-link {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .dropdown-item:hover {
            background-color: var(--background-color);
        }

        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(0,184,148,0.25);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-exchange-alt me-2"></i>GivnGet
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('items.index') }}">Предметы</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('categories.index') }}">Категории</a>
                    </li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Войти</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Регистрация</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                @if(Auth::user()->profile_image)
                                    <img src="{{ Storage::url(Auth::user()->profile_image) }}" alt="Profile" class="profile-image">
                                @else
                                    <div class="profile-image-placeholder">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                                {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user-circle me-2"></i>Профиль
                                </a>
                                <a class="dropdown-item" href="{{ route('items.create') }}">
                                    <i class="fas fa-plus me-2"></i>Добавить предмет
                                </a>
                                <a class="dropdown-item" href="{{ route('exchanges.index') }}">
                                    <i class="fas fa-exchange-alt me-2"></i>Мои обмены
                                </a>
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i>Выйти
                                    </button>
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>

    <footer class="footer mt-5 py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-3">GivnGet</h5>
                    <p class="text-muted">Платформа для обмена предметами между пользователями.</p>
                </div>
                <div class="col-md-3">
                    <h5 class="mb-3">Навигация</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('items.index') }}" class="text-decoration-none text-muted">Предметы</a></li>
                        <li><a href="{{ route('categories.index') }}" class="text-decoration-none text-muted">Категории</a></li>
                        <li><a href="{{ route('exchanges.index') }}" class="text-decoration-none text-muted">Мои обмены</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5 class="mb-3">Контакты</h5>
                    <ul class="list-unstyled">
                        <li class="text-muted"><i class="fas fa-envelope me-2"></i>support@givnget.com</li>
                        <li class="text-muted"><i class="fas fa-phone me-2"></i>+7 (999) 123-45-67</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center text-muted">
                <small>&copy; {{ date('Y') }} GivnGet. Все права защищены.</small>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="http://pyhjavd-m2.wsr.ru/public/js/bootstrap.bundle.min.js"></script>
    <script src="http://pyhjavd-m2.wsr.ru/public/js/item-edit.js"></script>
</body>
</html>
