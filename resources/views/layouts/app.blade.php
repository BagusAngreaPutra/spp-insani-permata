<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Sistem Pembayaran SPP')</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Your Vite compiled CSS & JS (jika kamu pakai Laravel Mix/Vite) -->
    <style>
        *, *::before, *::after {
            box-sizing: border-box;
        }

        html,
        body {
            max-width: 100%;
            overflow-x: hidden;
        }

        body:has(.app-sidebar) main.flex-fill {
            padding: 0 !important;
        }

        body:has(.app-sidebar) main.flex-fill > .container {
            max-width: none !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            width: 100% !important;
        }

        body:has(.app-sidebar) .main-content {
            box-sizing: border-box;
            max-width: 100%;
            min-width: 0;
        }

        body:has(.app-sidebar) .content-area {
            box-sizing: border-box;
            max-width: 100%;
            min-width: 0;
        }

        body:has(.app-sidebar) .content-area img,
        body:has(.app-sidebar) .content-area canvas,
        body:has(.app-sidebar) .content-area svg {
            max-width: 100%;
        }

        body:has(.app-sidebar) .content-area table {
            width: 100%;
        }

        body:has(.app-sidebar) .table-container,
        body:has(.app-sidebar) .table-responsive,
        body:has(.app-sidebar) .students-table-container,
        body:has(.app-sidebar) .schools-table-wrap {
            max-width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        body:has(.app-sidebar) .content-area .action-buttons,
        body:has(.app-sidebar) .content-area .table-actions,
        body:has(.app-sidebar) .content-area .row-actions {
            align-items: center !important;
            display: inline-flex !important;
            flex-direction: row !important;
            flex-wrap: nowrap !important;
            gap: 0.5rem !important;
            justify-content: flex-start !important;
            max-width: 100%;
            overflow-x: auto;
            padding-bottom: 0.1rem;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }

        body:has(.app-sidebar) .content-area td[data-label="Aksi"] .action-buttons,
        body:has(.app-sidebar) .content-area td:last-child .action-buttons {
            width: max-content !important;
        }

        body:has(.app-sidebar) .content-area td[data-label="Aksi"] .btn,
        body:has(.app-sidebar) .content-area td:last-child .btn,
        body:has(.app-sidebar) .content-area td[data-label="Aksi"] button,
        body:has(.app-sidebar) .content-area td:last-child button {
            flex: 0 0 auto !important;
            white-space: nowrap !important;
        }

        body:has(.app-sidebar) .content-area .action-buttons > *,
        body:has(.app-sidebar) .content-area .table-actions > *,
        body:has(.app-sidebar) .content-area .row-actions > * {
            flex: 0 0 auto !important;
        }

        @media (max-width: 768px) {
            body:has(.app-sidebar) .main-content {
                left: auto !important;
                position: relative !important;
                right: auto !important;
                top: auto !important;
            }

            body:has(.app-sidebar) .content-area {
                overflow-x: auto;
            }

            body:has(.app-sidebar) .content-area table {
                min-width: 680px;
            }

            body:has(.app-sidebar) .filter-buttons,
            body:has(.app-sidebar) .table-actions,
            body:has(.app-sidebar) .header-actions {
                align-items: stretch;
                flex-direction: column;
            }

            body:has(.app-sidebar) .content-area .table-actions,
            body:has(.app-sidebar) .content-area .header-actions {
                align-items: center !important;
                flex-direction: row !important;
                overflow-x: auto;
            }

            body:has(.app-sidebar) .btn,
            body:has(.app-sidebar) button,
            body:has(.app-sidebar) .btn-filter,
            body:has(.app-sidebar) .btn-reset {
                max-width: 100%;
            }
        }

        @media (max-width: 480px) {
            body:has(.app-sidebar) .content-area table {
                min-width: 620px;
            }
        }
    </style>
</head>
<body class="font-sans antialiased bg-light">
    <div class="min-vh-100 d-flex flex-column">
        {{-- Navbar --}}

        {{-- Page Header --}}
        @if (isset($header))
            <header class="bg-white shadow-sm">
                <div class="container py-3">
                    {{ $header }}
                </div>
            </header>
        @endif

        {{-- Main Content --}}
        <main class="flex-fill py-4">
            <div class="container">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const hasCustomTitle = @json(View::hasSection('title'));

            if (hasCustomTitle) {
                return;
            }

            const selectors = [
                '.page-title',
                '.dashboard-title',
                '[data-page-title]',
                'main h1:not(.greeting)',
                'main h2:not(.greeting)',
            ];
            const heading = selectors
                .map((selector) => document.querySelector(selector))
                .find((element) => element && element.textContent.trim());

            if (heading) {
                document.title = heading.textContent.trim().replace(/\s+/g, ' ');
            }
        });
    </script>
</body>
</html>
