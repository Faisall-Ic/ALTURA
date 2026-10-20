<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-canvas">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Portal' }} — ALTURA (Bimbel Alto)</title>

    <!-- Typography: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 Free CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>

    <!-- Styles & Tailwind -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    @endif

    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body x-data="{ sidebarOpen: false }" class="bg-canvas text-neutral-text min-h-screen flex antialiased selection:bg-brand-soft selection:text-brand-primary">
    
    <!-- Mobile Sidebar Backdrop Overlay -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs z-40 lg:hidden">
    </div>

    <!-- Collapsible Clean White Sidebar -->
    @include('layouts.partials.sidebar')

    <!-- Main Content Canvas Area -->
    <div class="flex-1 flex flex-col min-w-0 lg:pl-64 min-h-screen">
        
        <!-- Sticky Frosted-Glass Header Bar -->
        @include('layouts.partials.header')

        <!-- Main Content Canvas Area -->
        <main class="flex-1 min-w-0 bg-canvas p-4 sm:p-6 lg:p-8 overflow-y-auto">
            <!-- Flash Messages -->
            @if (session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between shadow-2xs">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                        <span class="font-semibold">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 rounded-2xl bg-red-accent-soft border border-red-200 text-red-accent-text text-sm flex items-center justify-between shadow-2xs">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-circle-exclamation text-red-accent text-base"></i>
                        <span class="font-semibold">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <div class="max-w-7xl mx-auto w-full">
                {{ $slot }}
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-surface border-t border-neutral-border px-6 py-4 text-center sm:text-left text-xs text-neutral-muted mt-auto">
            <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2">
                <p class="font-semibold text-neutral-text">&copy; {{ date('Y') }} ALTURA — Bimbingan Belajar Alto (Alumni Tutorial)</p>
                <p>Sistem Akademik Terpadu • Sesi Aktif 120 Menit</p>
            </div>
        </footer>
    </div>
</body>
</html>
