<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('meta_title', $metaTitle ?? $defaultMetaTitle ?? config('app.name'))</title>
    <meta name="description" content="@yield('meta_description', $metaDescription ?? $defaultMetaDescription ?? '')">
    <meta name="keywords" content="@yield('meta_keywords', $defaultMetaKeywords ?? '')">

    @if(!empty($faviconUrl))
        <link rel="icon" href="{{ $faviconUrl }}" type="image/png">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    @vite(['resources/js/front.js'])

    @livewireStyles
    @stack('styles')
</head>
<body class="site-body">
    @include('front.partials.header')
    @include('front.partials.menu')

    @hasSection('breadcrumbs')
        @yield('breadcrumbs')
    @endif

    <main class="site-main">
        @hasSection('content')
            @yield('content')
        @else
            {{ $slot ?? '' }}
        @endif
    </main>

    @include('front.partials.footer')
    @include('front.partials.toasts')

    @php
        $mobileContactHref = $socialWhatsapp
            ? (str_starts_with($socialWhatsapp, 'http') ? $socialWhatsapp : 'https://wa.me/'.preg_replace('/\D/', '', $socialWhatsapp))
            : ($contactPhone ? 'tel:'.preg_replace('/[^\d+]/', '', $contactPhone) : null);
    @endphp
    @if($mobileContactHref)
        <a href="{{ $mobileContactHref }}" class="site-mobile-contact d-md-none">
            <i class="fa-solid fa-headset me-2"></i>Связаться с менеджером
        </a>
    @endif

    @livewireScripts
    @stack('scripts')
</body>
</html>
