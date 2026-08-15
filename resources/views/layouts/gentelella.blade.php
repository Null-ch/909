<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') | {{ config('app.name') }}</title>

    <link rel="icon" href="{{ asset('vendor/gentelella/images/favicon.svg') }}" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script>
        (function () {
            try {
                var stored = localStorage.getItem('theme');
                var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                var theme = stored || (prefersDark ? 'dark' : 'light');
                document.documentElement.setAttribute('data-theme', theme);
            } catch (e) {}
        })();
    </script>

    @vite(['resources/js/admin.js'])
</head>
<body
    data-shell="admin"
    data-page="@yield('page', 'dashboard')"
    data-breadcrumb="@yield('breadcrumb', 'Главная')"
>
    @include('layouts.partials.admin-sidebar')

    <main class="main">
        @include('layouts.partials.admin-topbar')

        <div class="page-wrapper">
            @yield('content')
            @include('layouts.partials.admin-alerts')
        </div>

        @include('layouts.partials.admin-footer')
    </main>

    @stack('scripts')
</body>
</html>
