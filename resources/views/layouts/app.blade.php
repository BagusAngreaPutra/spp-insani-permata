<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Sistem Pembayaran')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/table-sort.css') }}?v=20260804-5" rel="stylesheet">
    @stack('page-styles')
    <link href="{{ asset('css/permata-system.css') }}?v=20260804-1" rel="stylesheet">
    <script src="{{ asset('js/table-sort.js') }}?v=20260804-5" defer></script>
</head>
<body>
    @isset($header)
        {{ $header }}
    @endisset

    <main>
        @yield('content')
        {{ $slot ?? '' }}
    </main>

    <script src="{{ asset('js/permata-system.js') }}?v=20260804-1"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const heading = document.querySelector('[data-page-title], .page-title, .dashboard-title, main h1, main h2');
            if (heading && !document.title.trim()) document.title = heading.textContent.trim();
        });
    </script>
</body>
</html>
