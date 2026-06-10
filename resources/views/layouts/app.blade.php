<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-force-theme="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        @php
            $faviconPath = $frontendSettings['site_favicon_path'] ?? null;
            $faviconUrl = $faviconPath ? asset('storage/' . ltrim((string) $faviconPath, '/')) : asset('favicon.ico');
        @endphp
        <link rel="icon" href="{{ $faviconUrl }}" sizes="any">
        <link rel="shortcut icon" href="{{ $faviconUrl }}">
        <link rel="apple-touch-icon" href="{{ $faviconUrl }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" referrerpolicy="no-referrer" />

        <!-- Scripts -->
        @php
            $manifestPath = public_path('build/manifest.json');
            $hotPath = public_path('hot');
            $useHot = false;

            if (file_exists($hotPath)) {
                $hotUrl = trim((string) @file_get_contents($hotPath));
                if ($hotUrl !== '') {
                    $parts = parse_url($hotUrl);
                    $host = $parts['host'] ?? null;
                    $port = $parts['port'] ?? (($parts['scheme'] ?? '') === 'https' ? 443 : 80);
                    if ($host && $port) {
                        $conn = @fsockopen($host, (int) $port, $errno, $errstr, 0.15);
                        if (is_resource($conn)) {
                            fclose($conn);
                            $useHot = true;
                        }
                    }
                }
            }

            $manifest = null;
            if (! $useHot && file_exists($manifestPath)) {
                $manifest = json_decode((string) file_get_contents($manifestPath), true) ?: [];
            }
        @endphp

        @if ($useHot)
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @elseif (is_array($manifest))
            @if (!empty($manifest['resources/css/app.css']['file']))
                <link rel="stylesheet" href="{{ asset('build/' . $manifest['resources/css/app.css']['file']) }}">
            @endif
            @if (!empty($manifest['resources/js/app.js']['file']))
                <script type="module" src="{{ asset('build/' . $manifest['resources/js/app.js']['file']) }}"></script>
            @endif
        @else
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        @if(Auth::check() && Auth::user()->hasRole('admin'))
            <meta name="wysiwyg-upload-url" content="{{ route('admin.wysiwyg.upload') }}">
            <meta name="tinymce-base-url" content="{{ asset('vendor/tinymce') }}">
            @if ($useHot)
                @vite(['resources/js/admin.js'])
            @elseif (is_array($manifest) && !empty($manifest['resources/js/admin.js']['file']))
                <script type="module" src="{{ asset('build/' . $manifest['resources/js/admin.js']['file']) }}"></script>
            @else
                @vite(['resources/js/admin.js'])
            @endif
        @endif

        @stack('styles')

        <style>
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="font-sans antialiased">
        @php
            $panelLabel = 'Student Panel';
            $user = auth()->user();

            if ($user && method_exists($user, 'hasRole')) {
                if ($user->hasRole('admin')) {
                    $panelLabel = 'Admin Panel';
                } elseif ($user->hasRole('mentor')) {
                    $panelLabel = 'Mentor Panel';
                }
            }
        @endphp
        <div x-data="{ sidebarOpen: false }" class="min-h-screen bg-slate-100">
            <!-- Mobile sidebar -->
            <div x-show="sidebarOpen" x-cloak style="display: none;" class="fixed inset-0 z-40 lg:hidden" aria-hidden="true">
                <div class="absolute inset-0 z-40 bg-slate-900/60" @click="sidebarOpen = false"></div>
                <div class="absolute inset-y-0 left-0 z-50 w-72 bg-slate-900 p-4 shadow-xl" @click.stop>
                    <div class="flex items-center justify-between">
                        <a href="/dashboard" class="flex items-center gap-2">
                            <div class="h-9 w-9 rounded-lg bg-indigo-600/90 text-white grid place-items-center font-bold">{{ strtoupper(substr(config('app.name', 'A'), 0, 1)) }}</div>
                            <div class="text-white">
                                <div class="text-sm font-semibold leading-5">{{ config('app.name', 'Laravel') }}</div>
                                <div class="text-xs text-slate-300">{{ $panelLabel }}</div>
                            </div>
                        </a>
                        <button type="button" class="rounded-md p-2 text-slate-200 hover:bg-slate-800" @click="sidebarOpen = false" aria-label="Close sidebar">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </button>
                    </div>

                    <div class="mt-6">
                        @include('layouts.sidebar')
                    </div>
                </div>
            </div>

            <div class="flex min-h-screen">
                <!-- Desktop sidebar -->
                <aside class="hidden lg:flex lg:w-72 lg:flex-col lg:bg-slate-900 lg:text-white lg:sticky lg:top-0 lg:h-screen lg:self-start">
                    <div class="flex h-16 items-center gap-3 px-4 border-b border-slate-800">
                        <div class="h-9 w-9 rounded-lg bg-indigo-600/90 text-white grid place-items-center font-bold">{{ strtoupper(substr(config('app.name', 'A'), 0, 1)) }}</div>
                        <div class="min-w-0">
                            <div class="text-sm font-semibold leading-5 truncate">{{ config('app.name', 'Laravel') }}</div>
                            <div class="text-xs text-slate-300">{{ $panelLabel }}</div>
                        </div>
                    </div>
                    <div class="flex-1 overflow-y-auto p-4">
                        @include('layouts.sidebar')
                    </div>
                </aside>

                <!-- Main content -->
                <div class="flex min-w-0 flex-1 flex-col">
                    <header class="sticky top-0 z-30 bg-white/95 backdrop-blur border-b border-slate-200">
                        <div class="flex h-16 items-center justify-between px-4 lg:px-6">
                            <div class="flex items-center gap-3">
                                <button type="button" class="lg:hidden rounded-md p-2 text-slate-600 hover:bg-slate-100" @click="sidebarOpen = true" aria-label="Open sidebar">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                                    </svg>
                                </button>

                                <div class="hidden sm:block text-sm text-slate-500">
                                    {{ now()->format('l, d M Y') }}
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button class="inline-flex items-center gap-2 rounded-md border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50">
                                            <span class="hidden sm:block">{{ Auth::user()->name }}</span>
                                            <x-avatar :user="Auth::user()" />
                                        </button>
                                    </x-slot>

                                    <x-slot name="content">
                                        <x-dropdown-link href="/profile">
                                            {{ __('Profile') }}
                                        </x-dropdown-link>

                                        <form method="POST" action="/logout">
                                            @csrf
                                            <x-dropdown-link href="/logout"
                                                onclick="event.preventDefault(); this.closest('form').submit();">
                                                {{ __('Log Out') }}
                                            </x-dropdown-link>
                                        </form>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        </div>

                        @isset($header)
                            <div class="px-4 lg:px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <div class="min-w-0">
                                        {{ $header }}
                                    </div>
                                </div>
                            </div>
                        @endisset
                    </header>

                    <main class="flex-1 px-4 lg:px-6 py-6">
                        @php
                            $globalSuccess = session('success');

                            if (! $globalSuccess && session('status') === 'password-updated') {
                                $globalSuccess = __('frontend.password_updated_success');
                            }
                        @endphp

                        @if ($globalSuccess)
                            <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 ring-1 ring-emerald-100">
                                {{ $globalSuccess }}
                            </div>
                        @endif

                        @if (isset($slot))
                            {{ $slot }}
                        @else
                            @yield('content')
                        @endif
                    </main>
                </div>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
