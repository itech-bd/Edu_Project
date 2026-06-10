<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        (function () {
            try {
                var theme = localStorage.getItem('theme');
                var isDark = theme !== 'light';
                document.documentElement.classList.toggle('dark', isDark);
                document.documentElement.dataset.theme = isDark ? 'dark' : 'light';
                document.documentElement.style.colorScheme = isDark ? 'dark' : 'light';
            } catch (e) {
                document.documentElement.classList.add('dark');
                document.documentElement.dataset.theme = 'dark';
                document.documentElement.style.colorScheme = 'dark';
            }
        })();
    </script>

    <title>@yield('title', config('app.name', 'iTechBD Ltd'))</title>
    @php
        $faviconPath = $frontendSettings['site_favicon_path'] ?? null;
        $faviconUrl = $faviconPath ? asset('storage/' . ltrim((string) $faviconPath, '/')) : asset('favicon.ico');
    @endphp
    <link rel="icon" href="{{ $faviconUrl }}" sizes="any">
    <link rel="shortcut icon" href="{{ $faviconUrl }}">
    <link rel="apple-touch-icon" href="{{ $faviconUrl }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" referrerpolicy="no-referrer" />

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
        <script>
            tailwind = window.tailwind || {};
            tailwind.config = Object.assign({}, tailwind.config || {}, { darkMode: 'class' });
        </script>
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

    @stack('head')

    <style>
        @media (min-width: 768px) {
            .force-dashboard-md { display: inline-flex !important; }
        }
    </style>

    <style>
        :root {
            --bg1: 99 102 241;
            --bg2: 14 165 233;
            --bg3: 16 185 129;
        }

        @media (prefers-reduced-motion: reduce) {
            .animate-float,
            .animate-gradient,
            .reveal {
                animation: none !important;
                transition: none !important;
            }
        }

        .animate-gradient {
            background-size: 200% 200%;
            animation: gradientMove 14s ease infinite;
        }

        @keyframes itechPulseDot {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
                box-shadow: 0 0 0 0 rgba(16, 185, 129, .55);
            }
            55% {
                transform: scale(1.45);
                opacity: .35;
                box-shadow: 0 0 0 12px rgba(16, 185, 129, 0);
            }
        }

        .itech-pulse-dot {
            transform-origin: center;
            animation: itechPulseDot 1.15s ease-in-out infinite !important;
        }

        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .animate-float { animation: floatY 6s ease-in-out infinite; }
        .animate-float-slow { animation: floatY 9s ease-in-out infinite; }

        @keyframes floatY {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .reveal {
            opacity: 0;
            transform: translateY(14px);
            transition: opacity 700ms cubic-bezier(0.2, 0.8, 0.2, 1), transform 700ms cubic-bezier(0.2, 0.8, 0.2, 1);
        }
        .reveal.is-visible { opacity: 1; transform: translateY(0); }

        .mentor-carousel {
            scroll-snap-type: x mandatory;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .mentor-carousel::-webkit-scrollbar {
            display: none;
        }

        .mentor-card {
            scroll-snap-align: start;
        }
    </style>

    @if (session('auth_modal'))
        <script>
            window.__authModalToOpen = @json(session('auth_modal'));
        </script>
    @endif

</head>
<body class="min-h-screen bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100">
    <!-- Background -->
    <div class="fixed inset-0 -z-10">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-white to-slate-100 dark:from-slate-950 dark:via-slate-950 dark:to-slate-900"></div>
        <div class="absolute -top-40 -left-40 h-[28rem] w-[28rem] rounded-full blur-3xl opacity-35 animate-float"
             style="background: radial-gradient(circle at 30% 30%, rgba(var(--bg1), .55), rgba(var(--bg1), 0) 60%);"></div>
        <div class="absolute top-24 -right-44 h-[30rem] w-[30rem] rounded-full blur-3xl opacity-30 animate-float-slow"
             style="background: radial-gradient(circle at 30% 30%, rgba(var(--bg2), .50), rgba(var(--bg2), 0) 60%);"></div>
        <div class="absolute -bottom-48 left-1/4 h-[34rem] w-[34rem] rounded-full blur-3xl opacity-25 animate-float"
             style="background: radial-gradient(circle at 30% 30%, rgba(var(--bg3), .45), rgba(var(--bg3), 0) 60%);"></div>
        <div class="absolute inset-0 opacity-[0.06] dark:opacity-[0.08] [background-image:radial-gradient(#0f172a_1px,transparent_1px)] dark:[background-image:radial-gradient(#ffffff_1px,transparent_1px)] [background-size:18px_18px]"></div>
    </div>

    <!-- Top bar / Nav -->
    <header x-data="{ mobileMenuOpen: false }" class="sticky top-0 z-50 border-b border-slate-200/70 bg-white/80 backdrop-blur dark:border-white/10 dark:bg-slate-950/70">
        <div class="border-b border-slate-200/70 bg-slate-50/70 dark:border-white/10 dark:bg-slate-950/40">
            <div class="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-2 text-xs text-slate-600 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8 dark:text-slate-300">
                <div class="inline-flex items-center gap-2">
                    <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4 text-slate-500 dark:text-slate-300" aria-hidden="true">
                        <path d="M12 22s7-4.5 7-11a7 7 0 1 0-14 0c0 6.5 7 11 7 11Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round" />
                        <path d="M12 11.5a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z" stroke="currentColor" stroke-width="1.7" />
                    </svg>
                    <span>{{ $frontendSettings['site_address'] ?? 'Dhaka, Bangladesh' }}</span>
                </div>

                <div class="flex flex-wrap items-center gap-x-4 gap-y-1">
                    @php
                        $sitePhone = $frontendSettings['site_phone'] ?? '+880 10 0000 0000';
                        $sitePhoneTel = 'tel:' . preg_replace('/\s+/', '', (string) $sitePhone);

                        $siteEmail = $frontendSettings['site_email'] ?? 'info@example.com';
                        $siteEmailMailto = 'mailto:' . (string) $siteEmail;
                    @endphp

                    <a href="{{ $sitePhoneTel }}" class="inline-flex items-center gap-2 hover:text-slate-900 dark:hover:text-white">
                        <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4 text-slate-500 dark:text-slate-300" aria-hidden="true">
                            <path d="M7 3h2l2 5-2 1c1 3 3 5 6 6l1-2 5 2v2c0 1-1 2-2 2-9 0-16-7-16-16 0-1 1-2 2-2Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round" />
                        </svg>
                        <span>{{ $sitePhone }}</span>
                    </a>
                    <a href="{{ $siteEmailMailto }}" class="inline-flex items-center gap-2 hover:text-slate-900 dark:hover:text-white">
                        <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4 text-slate-500 dark:text-slate-300" aria-hidden="true">
                            <path d="M4 6h16v12H4V6Z" stroke="currentColor" stroke-width="1.7" />
                            <path d="m4 7 8 6 8-6" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round" />
                        </svg>
                        <span>{{ $siteEmail }}</span>
                    </a>

                    @php
                        $currentLocale = app()->getLocale();
                        $languageOptions = [
                            'bn' => [
                                'label' => __('frontend.language_bn'),
                            ],
                            'en' => [
                                'label' => __('frontend.language_en'),
                            ],
                        ];
                        $currentLanguage = $languageOptions[$currentLocale] ?? $languageOptions['en'];
                        $flagSvg = static function (string $locale): string {
                            if ($locale === 'bn') {
                                return '<svg viewBox="0 0 20 14" class="h-3.5 w-5 shrink-0 rounded-[2px] ring-1 ring-black/5" aria-hidden="true"><rect width="20" height="14" fill="#006a4e"/><circle cx="8.6" cy="7" r="3.2" fill="#f42a41"/></svg>';
                            }

                            return '<svg viewBox="0 0 20 14" class="h-3.5 w-5 shrink-0 rounded-[2px] ring-1 ring-black/5" aria-hidden="true"><rect width="20" height="14" fill="#ffffff"/><rect width="20" height="1.08" y="0" fill="#b22234"/><rect width="20" height="1.08" y="2.16" fill="#b22234"/><rect width="20" height="1.08" y="4.32" fill="#b22234"/><rect width="20" height="1.08" y="6.48" fill="#b22234"/><rect width="20" height="1.08" y="8.64" fill="#b22234"/><rect width="20" height="1.08" y="10.8" fill="#b22234"/><rect width="20" height="1.08" y="12.92" fill="#b22234"/><rect width="8" height="7.54" fill="#3c3b6e"/></svg>';
                        };
                    @endphp

                    <details class="relative hidden sm:block">
                        <summary class="flex cursor-pointer list-none items-center gap-2 rounded-xl border-l border-slate-200/70 pl-4 text-slate-700 transition hover:text-slate-900 dark:border-white/10 dark:text-slate-200 dark:hover:text-white">
                            {!! $flagSvg($currentLocale) !!}
                            <span>{{ $currentLanguage['label'] }}</span>
                            <svg viewBox="0 0 20 20" fill="none" class="h-4 w-4 text-slate-500 dark:text-slate-400" aria-hidden="true">
                                <path d="m5 7.5 5 5 5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </summary>

                        <div class="absolute right-0 top-full z-50 mt-2 min-w-40 overflow-hidden rounded-2xl border border-slate-200/70 bg-white/95 p-1 text-slate-800 shadow-lg shadow-slate-200/60 backdrop-blur dark:border-slate-300/70 dark:bg-white/95 dark:text-slate-900 dark:shadow-none">
                            @foreach ($languageOptions as $localeCode => $language)
                                <a
                                    href="{{ route('language.switch', ['lang' => $localeCode]) }}"
                                    class="flex items-center justify-between gap-3 rounded-xl border px-3 py-2 transition hover:bg-slate-100 hover:text-slate-900 dark:hover:bg-slate-100 dark:hover:text-slate-900 {{ $currentLocale === $localeCode ? 'border-slate-200 bg-slate-100 text-slate-900 dark:border-slate-200 dark:bg-slate-100 dark:text-slate-900' : 'border-transparent text-slate-700 dark:text-slate-900' }}"
                                >
                                    <span class="flex items-center gap-2">
                                        {!! $flagSvg($localeCode) !!}
                                        <span>{{ $language['label'] }}</span>
                                    </span>

                                    @if ($currentLocale === $localeCode)
                                        <svg viewBox="0 0 20 20" fill="none" class="h-4 w-4" aria-hidden="true">
                                            <path d="m5 10 3 3 7-7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </details>
                </div>
            </div>
        </div>

        <div class="mx-auto flex md:hidden max-w-7xl items-center justify-start gap-3 px-4 py-2 sm:px-6 lg:px-8">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="inline-flex items-center rounded-xl bg-slate-900/5 px-4 py-2 text-sm font-medium text-slate-800 ring-1 ring-slate-200/70 transition hover:bg-slate-900/10 dark:bg-white/10 dark:text-white dark:ring-white/10 dark:hover:bg-white/15">
                        {{ __('frontend.dashboard') }}
                    </a>
                @else
                    <a href="/login" data-auth-trigger="login" class="inline-flex rounded-xl px-4 py-2 text-sm text-slate-700 ring-1 ring-slate-200/70 transition hover:bg-slate-100 dark:text-slate-200 dark:ring-white/10 dark:hover:bg-white/10">
                        {{ __('frontend.login') }}
                    </a>

                    @if (Route::has('register'))
                        <a href="/register" data-auth-trigger="register" class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-100">
                            {{ __('frontend.enroll_now') }}
                        </a>
                    @else
                        <a href="/contact" class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-100">
                            {{ __('frontend.contact') }}
                        </a>
                    @endif
                @endauth
            @endif
        </div>

        <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-4 py-4 sm:px-6 lg:px-8">
            <a href="/" class="group inline-flex shrink-0 items-center gap-3">
                @php
                    $logoPath = $frontendSettings['site_logo_path'] ?? null;
                @endphp

                @if ($logoPath)
                    <img
                        src="{{ asset('storage/' . $logoPath) }}"
                        alt="{{ config('app.name', 'iTechBD Ltd') }}"
                        class="h-12 w-auto max-w-[360px] object-contain"
                        style="filter: drop-shadow(0 1px 0 rgba(255,255,255,.35)) drop-shadow(0 6px 14px rgba(255,255,255,.12));"
                    />
                @else
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 ring-1 ring-white/10">
                        <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5 text-white" aria-hidden="true">
                            <path d="M4 19V6.5a2.5 2.5 0 0 1 2.5-2.5H20v15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                            <path d="M8 8h8M8 12h8M8 16h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                        </svg>
                    </span>
                    <span class="leading-tight">
                        <span class="block text-base font-semibold tracking-tight">{{ config('app.name', 'iTechBD Ltd') }}</span>
                        <span class="block text-xs text-slate-300/80">Training Institute • Career-focused</span>
                    </span>
                @endif
            </a>

            <nav class="relative z-10 hidden flex-1 items-center justify-center gap-5 text-sm text-slate-700 md:flex dark:text-slate-200">
                <a href="/" class="hover:text-slate-900 dark:hover:text-white">{{ __('frontend.home') }}</a>
                <a href="/about" class="hover:text-slate-900 dark:hover:text-white">{{ __('frontend.about') }}</a>
                <a href="/courses" class="hover:text-slate-900 dark:hover:text-white">{{ __('frontend.courses') }}</a>
                <a href="/mentors" class="hover:text-slate-900 dark:hover:text-white">{{ __('frontend.mentors') }}</a>
                <a href="/reviews" class="hover:text-slate-900 dark:hover:text-white">{{ __('frontend.reviews') }}</a>
                <a href="/news" class="hover:text-slate-900 dark:hover:text-white">{{ __('frontend.news') }}</a>
                <a href="/contact" class="hover:text-slate-900 dark:hover:text-white">{{ __('frontend.contact') }}</a>
            </nav>

            <div class="flex shrink-0 items-center gap-3">
                <!-- Mobile menu toggle (JS-driven) -->
                <button id="mobile-menu-toggle" type="button" class="inline-flex items-center justify-center rounded-xl p-2 text-slate-700 md:hidden ring-1 ring-slate-200/70 bg-white/0 hover:bg-slate-100 dark:text-slate-200 dark:ring-white/10" aria-label="Toggle menu" aria-expanded="false">
                    <svg id="mobile-open-icon" viewBox="0 0 24 24" fill="none" class="h-5 w-5" aria-hidden="true">
                        <path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                    </svg>
                    <svg id="mobile-close-icon" viewBox="0 0 24 24" fill="none" class="hidden h-5 w-5" aria-hidden="true">
                        <path d="M6 6l12 12M18 6 6 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                    </svg>
                </button>

                <button type="button"
                        data-theme-toggle
                        class="inline-flex items-center justify-center rounded-xl bg-slate-900/5 p-2 text-slate-700 ring-1 ring-slate-200/70 transition hover:bg-slate-900/10 dark:bg-white/10 dark:text-slate-200 dark:ring-white/10 dark:hover:bg-white/15"
                        aria-label="Toggle theme">
                    <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5 dark:hidden" aria-hidden="true">
                        <path d="M12 18a6 6 0 1 0 0-12 6 6 0 0 0 0 12Z" stroke="currentColor" stroke-width="1.8" />
                        <path d="M12 2v2.5M12 19.5V22M4.2 4.2 6 6M18 18l1.8 1.8M2 12h2.5M19.5 12H22M4.2 19.8 6 18M18 6l1.8-1.8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                    </svg>
                    <svg viewBox="0 0 24 24" fill="none" class="hidden h-5 w-5 dark:block" aria-hidden="true">
                        <path d="M21 14.2A7.8 7.8 0 0 1 9.8 3 7 7 0 1 0 21 14.2Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                    </svg>
                </button>

                
                @if (Route::has('login'))
                    <div class="hidden md:flex items-center gap-3">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="inline-flex force-dashboard-md items-center rounded-xl bg-slate-900/5 px-4 py-2 text-sm font-medium text-slate-800 ring-1 ring-slate-200/70 transition hover:bg-slate-900/10 dark:bg-white/10 dark:text-white dark:ring-white/10 dark:hover:bg-white/15">
                                {{ __('frontend.dashboard') }}
                            </a>
                        @else
                            <a href="/login" data-auth-trigger="login" class="inline-flex rounded-xl px-4 py-2 text-sm text-slate-700 ring-1 ring-slate-200/70 transition hover:bg-slate-100 dark:text-slate-200 dark:ring-white/10 dark:hover:bg-white/10">
                                {{ __('frontend.login') }}
                            </a>

                            @if (Route::has('register'))
                                <a href="/register" data-auth-trigger="register" class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-100">
                                    {{ __('frontend.enroll_now') }}
                                </a>
                            @else
                                <a href="/contact" class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-100">
                                    {{ __('frontend.contact') }}
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif

            </div>
        </div>
    </header>

    <!-- Mobile navigation panel (JS-driven) -->
    <div id="mobile-nav-panel" class="hidden md:hidden">
        <div class="border-b border-slate-200/70 !bg-slate-900/95 p-4 dark:!bg-slate-900/95 dark:border-white/10 text-slate-100">
            <nav class="flex flex-col gap-3 text-base">
                <a href="/" class="block rounded-md px-3 py-2 !text-slate-100 hover:bg-slate-800">{{ __('frontend.home') }}</a>
                <a href="/about" class="block rounded-md px-3 py-2 !text-slate-100 hover:bg-slate-800">{{ __('frontend.about') }}</a>
                <a href="/courses" class="block rounded-md px-3 py-2 !text-slate-100 hover:bg-slate-800">{{ __('frontend.courses') }}</a>
                <a href="/mentors" class="block rounded-md px-3 py-2 !text-slate-100 hover:bg-slate-800">{{ __('frontend.mentors') }}</a>
                <a href="/reviews" class="block rounded-md px-3 py-2 !text-slate-100 hover:bg-slate-800">{{ __('frontend.reviews') }}</a>
                <a href="/news" class="block rounded-md px-3 py-2 !text-slate-100 hover:bg-slate-800">{{ __('frontend.news') }}</a>
                <a href="/contact" class="block rounded-md px-3 py-2 !text-slate-100 hover:bg-slate-800">{{ __('frontend.contact') }}</a>
            </nav>

            <div class="mt-4 flex flex-col gap-3">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center rounded-xl bg-slate-900/5 px-4 py-2 text-sm font-medium !text-slate-100 ring-1 ring-slate-200/70 dark:!text-slate-100">{{ __('frontend.dashboard') }}</a>
                    @else
                        <a href="/login" data-auth-trigger="login" class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm !text-slate-700 dark:!text-slate-100 ring-1 ring-slate-200/70 dark:ring-white/10">{{ __('frontend.login') }}</a>

                        @if (Route::has('register'))
                            <a href="/register" data-auth-trigger="register" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold !text-white dark:!text-slate-950 dark:bg-white">{{ __('frontend.enroll_now') }}</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var toggle = document.getElementById('mobile-menu-toggle');
                var panel = document.getElementById('mobile-nav-panel');
                var openIcon = document.getElementById('mobile-open-icon');
                var closeIcon = document.getElementById('mobile-close-icon');

                function updateToggleVisibility() {
                    if (!toggle) return;
                    try {
                        if (window.innerWidth >= 768) {
                            toggle.classList.add('hidden');
                        } else {
                            toggle.classList.remove('hidden');
                        }
                    } catch (e) {
                        // ignore
                    }
                }

                if (toggle && panel) {
                    toggle.addEventListener('click', function () {
                        var isHidden = panel.classList.toggle('hidden');
                        toggle.setAttribute('aria-expanded', String(!isHidden));
                        if (openIcon && closeIcon) {
                            openIcon.classList.toggle('hidden');
                            closeIcon.classList.toggle('hidden');
                        }
                    });

                    window.addEventListener('resize', updateToggleVisibility);
                    updateToggleVisibility();
                }
            });
        </script>
    @endpush

    @yield('content')

    <footer class="relative border-t border-slate-200/70 bg-white/70 dark:border-white/10 dark:bg-slate-950/50">
        @php
            $footerBrandTagline = $frontendSettings['footer_brand_tagline'] ?? __('frontend.footer_brand_tagline');
            $footerBrandDescription = $frontendSettings['footer_brand_description'] ?? __('frontend.footer_brand_description');
            $footerUpdatesTitle = $frontendSettings['footer_updates_title'] ?? 'Updates';
            $footerUpdatesSubtitle = $frontendSettings['footer_updates_subtitle'] ?? __('frontend.footer_updates_subtitle');
            $footerContactTitle = $frontendSettings['footer_contact_title'] ?? 'Contact Info';
            $footerPhoneLabel = $frontendSettings['footer_phone_label'] ?? 'Phone';
            $footerEmailLabel = $frontendSettings['footer_email_label'] ?? 'Email';
            $footerLocationLabel = $frontendSettings['footer_location_label'] ?? 'Location';
            $footerCopyright = $frontendSettings['footer_copyright'] ?? 'All rights reserved.';
            $footerFacebookUrl = $frontendSettings['footer_facebook_url'] ?? '#';
            $footerLinkedinUrl = $frontendSettings['footer_linkedin_url'] ?? '#';
            $footerYoutubeUrl = $frontendSettings['footer_youtube_url'] ?? '#';
            $sitePhone = $frontendSettings['site_phone'] ?? '+880 10 0000 0000';
            $siteEmail = $frontendSettings['site_email'] ?? 'info@example.com';
            $siteAddress = $frontendSettings['site_address'] ?? 'Dhaka, Bangladesh';
        @endphp
        <div class="absolute inset-x-0 -top-px h-px bg-gradient-to-r from-indigo-400/0 via-sky-300/70 to-emerald-300/0"></div>

        <div class="mx-auto max-w-7xl px-4 py-12 text-sm text-slate-600 sm:px-6 lg:px-8 dark:text-slate-300">
            <div class="mb-10 overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-500/15 via-sky-500/10 to-emerald-500/15 p-6 ring-1 ring-slate-200/70 sm:p-8 dark:ring-white/10">
                <div class="relative">
                    <div class="pointer-events-none absolute -right-24 -top-24 h-56 w-56 rounded-full bg-sky-400/10 blur-3xl"></div>
                    <div class="pointer-events-none absolute -left-24 -bottom-24 h-56 w-56 rounded-full bg-indigo-400/10 blur-3xl"></div>

                    <div class="relative flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <div class="inline-flex items-center gap-2 rounded-full bg-slate-900/5 px-3 py-1 text-xs font-semibold text-slate-900 ring-1 ring-slate-200/70 dark:bg-white/10 dark:text-white dark:ring-white/10">
                                <span class="inline-flex h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                                {{ __('frontend.footer_cta_pill') }}
                            </div>
                            <div class="mt-4 text-lg font-semibold text-slate-900 dark:text-white">{{ __('frontend.footer_cta_title') }}</div>
                            <div class="mt-1 text-sm text-slate-600 dark:text-slate-200">{{ __('frontend.footer_cta_subtitle') }}</div>

                            <div class="mt-5 flex flex-wrap gap-2 text-xs text-slate-700 dark:text-slate-200">
                                <span class="inline-flex items-center gap-2 rounded-full bg-slate-900/5 px-3 py-1 ring-1 ring-slate-200/70 dark:bg-slate-950/30 dark:ring-white/10">{{ __('frontend.footer_cta_tag_weekly_reviews') }}</span>
                                <span class="inline-flex items-center gap-2 rounded-full bg-slate-900/5 px-3 py-1 ring-1 ring-slate-200/70 dark:bg-slate-950/30 dark:ring-white/10">{{ __('frontend.footer_cta_tag_portfolio_projects') }}</span>
                                <span class="inline-flex items-center gap-2 rounded-full bg-slate-900/5 px-3 py-1 ring-1 ring-slate-200/70 dark:bg-slate-950/30 dark:ring-white/10">{{ __('frontend.footer_cta_tag_career_support') }}</span>
                            </div>
                        </div>

                        <div class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row">
                            <a href="/courses" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-100">{{ __('frontend.explore_courses') }}</a>
                            <a href="/contact" class="inline-flex items-center justify-center rounded-2xl bg-slate-900/5 px-6 py-3 text-sm font-semibold text-slate-900 ring-1 ring-slate-200/70 transition hover:bg-slate-900/10 dark:bg-white/10 dark:text-white dark:ring-white/10 dark:hover:bg-white/15">{{ __('frontend.contact') }}</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid items-stretch gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div class="h-full sm:col-span-2">
                    <div class="flex h-full flex-col rounded-3xl bg-white p-6 ring-1 ring-slate-200/70 dark:bg-white/5 dark:ring-white/10">
                        <div class="inline-flex items-center gap-3">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 ring-1 ring-white/10">
                            <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5 text-white" aria-hidden="true">
                                <path d="M4 19V6.5a2.5 2.5 0 0 1 2.5-2.5H20v15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                                <path d="M8 8h8M8 12h8M8 16h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                            </svg>
                        </span>
                        <div>
                            <div class="font-semibold text-slate-900 dark:text-white">{{ config('app.name', 'iTechBD Ltd') }}</div>
                            <div class="text-xs text-slate-500 dark:text-slate-300">{{ $footerBrandTagline }}</div>
                        </div>
                    </div>
                    <p class="mt-4 max-w-md text-sm text-slate-600 dark:text-slate-200">{{ $footerBrandDescription }}</p>

                        <div class="mt-6 rounded-3xl bg-slate-50 p-5 ring-1 ring-slate-200/70 dark:bg-slate-950/30 dark:ring-white/10">
                            <div class="text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-200">{{ $footerUpdatesTitle }}</div>
                            <div class="mt-2 text-sm text-slate-600 dark:text-slate-200">{{ $footerUpdatesSubtitle }}</div>

                            <form class="mt-4" onsubmit="return false;">
                                <label class="sr-only" for="footerEmail">{{ __('frontend.footer_email_label') }}</label>
                                <div class="flex items-stretch overflow-hidden rounded-2xl bg-white ring-1 ring-slate-200/70 divide-x divide-slate-200/70 transition focus-within:ring-2 focus-within:ring-sky-400/40 dark:bg-slate-950/40 dark:divide-white/10 dark:ring-white/10 dark:focus-within:ring-sky-300/40">
                                    <div class="flex shrink-0 items-center px-4 text-slate-400">
                                        <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" aria-hidden="true">
                                            <path d="M4 6h16v12H4V6Z" stroke="currentColor" stroke-width="1.7" opacity="0.9" />
                                            <path d="m4 7 8 6 8-6" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round" opacity="0.9" />
                                        </svg>
                                    </div>
                                    <input id="footerEmail" type="email" inputmode="email" autocomplete="email" placeholder="you@example.com"
                                         class="w-full flex-1 bg-transparent px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 outline-none dark:text-white" />
                                        <button type="button"
                                            class="inline-flex items-center gap-2 bg-gradient-to-r from-sky-300 to-emerald-300 px-5 text-sm font-semibold text-slate-950 transition hover:opacity-95">
                                        {{ __('frontend.footer_notify') }}
                                        <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4" aria-hidden="true">
                                            <path d="M7 17 17 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                            <path d="M10 7h7v7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="mt-2 text-xs text-slate-500 dark:text-slate-400">{{ __('frontend.footer_no_spam') }}</div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="h-full">
                    <div class="flex h-full flex-col rounded-3xl bg-white p-6 ring-1 ring-slate-200/70 dark:bg-white/5 dark:ring-white/10">
                        <div class="text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-200">{{ __('frontend.footer_quick_links') }}</div>
                        <div class="mt-4 grid gap-2">
                            <a href="/" class="group inline-flex items-center justify-between rounded-xl px-2 py-1.5 transition hover:bg-slate-100 hover:text-slate-900 dark:hover:bg-white/5 dark:hover:text-white"><span>{{ __('frontend.home') }}</span><span class="opacity-0 transition group-hover:opacity-100">→</span></a>
                            <a href="/about" class="group inline-flex items-center justify-between rounded-xl px-2 py-1.5 transition hover:bg-slate-100 hover:text-slate-900 dark:hover:bg-white/5 dark:hover:text-white"><span>{{ __('frontend.about') }}</span><span class="opacity-0 transition group-hover:opacity-100">→</span></a>
                            <a href="/courses" class="group inline-flex items-center justify-between rounded-xl px-2 py-1.5 transition hover:bg-slate-100 hover:text-slate-900 dark:hover:bg-white/5 dark:hover:text-white"><span>{{ __('frontend.courses') }}</span><span class="opacity-0 transition group-hover:opacity-100">→</span></a>
                            <a href="/mentors" class="group inline-flex items-center justify-between rounded-xl px-2 py-1.5 transition hover:bg-slate-100 hover:text-slate-900 dark:hover:bg-white/5 dark:hover:text-white"><span>{{ __('frontend.mentors') }}</span><span class="opacity-0 transition group-hover:opacity-100">→</span></a>
                            <a href="/reviews" class="group inline-flex items-center justify-between rounded-xl px-2 py-1.5 transition hover:bg-slate-100 hover:text-slate-900 dark:hover:bg-white/5 dark:hover:text-white"><span>{{ __('frontend.reviews') }}</span><span class="opacity-0 transition group-hover:opacity-100">→</span></a>
                            <a href="/news" class="group inline-flex items-center justify-between rounded-xl px-2 py-1.5 transition hover:bg-slate-100 hover:text-slate-900 dark:hover:bg-white/5 dark:hover:text-white"><span>{{ __('frontend.news') }}</span><span class="opacity-0 transition group-hover:opacity-100">→</span></a>
                            <a href="/contact" class="group inline-flex items-center justify-between rounded-xl px-2 py-1.5 transition hover:bg-slate-100 hover:text-slate-900 dark:hover:bg-white/5 dark:hover:text-white"><span>{{ __('frontend.contact') }}</span><span class="opacity-0 transition group-hover:opacity-100">→</span></a>
                        </div>
                    </div>
                </div>

                <div class="h-full">
                    <div class="flex h-full flex-col rounded-3xl bg-white p-6 ring-1 ring-slate-200/70 dark:bg-white/5 dark:ring-white/10">
                        <div class="text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-200">{{ __('frontend.footer_social_media') }}</div>
                        <div class="mt-4 grid grid-cols-3 gap-2">
                            <a href="{{ $footerFacebookUrl ?: '#' }}" class="group flex flex-col items-center justify-center gap-2 rounded-2xl bg-slate-50 p-3 ring-1 ring-slate-200/70 transition hover:bg-slate-100 hover:text-slate-900 dark:bg-white/5 dark:ring-white/10 dark:hover:bg-white/10 dark:hover:text-white" aria-label="Facebook" target="_blank" rel="noopener noreferrer">
                                <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5 text-indigo-100" aria-hidden="true">
                                    <path d="M14 8.5h2V6h-2c-1.9 0-3.5 1.6-3.5 3.5V12H8v2.5h2.5V20H13v-5.5h2.5L16 12h-3V9.5c0-.6.4-1 1-1Z" fill="currentColor" opacity="0.95"/>
                                </svg>
                                <span class="text-xs text-slate-600 group-hover:text-slate-900 dark:text-slate-200 dark:group-hover:text-white">Facebook</span>
                            </a>
                            <a href="{{ $footerLinkedinUrl ?: '#' }}" class="group flex flex-col items-center justify-center gap-2 rounded-2xl bg-slate-50 p-3 ring-1 ring-slate-200/70 transition hover:bg-slate-100 hover:text-slate-900 dark:bg-white/5 dark:ring-white/10 dark:hover:bg-white/10 dark:hover:text-white" aria-label="LinkedIn" target="_blank" rel="noopener noreferrer">
                                <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5 text-sky-100" aria-hidden="true">
                                    <path d="M6.5 7.5V17.8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M6.5 6.2a1.2 1.2 0 1 0 0-2.4 1.2 1.2 0 0 0 0 2.4Z" fill="currentColor"/>
                                    <path d="M10.2 10.2v7.6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M10.2 12.1c.7-1.3 1.8-2 3.2-2 2 0 3.6 1.6 3.6 3.6v4.1" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                </svg>
                                <span class="text-xs text-slate-600 group-hover:text-slate-900 dark:text-slate-200 dark:group-hover:text-white">LinkedIn</span>
                            </a>
                            <a href="{{ $footerYoutubeUrl ?: '#' }}" class="group flex flex-col items-center justify-center gap-2 rounded-2xl bg-slate-50 p-3 ring-1 ring-slate-200/70 transition hover:bg-slate-100 hover:text-slate-900 dark:bg-white/5 dark:ring-white/10 dark:hover:bg-white/10 dark:hover:text-white" aria-label="YouTube" target="_blank" rel="noopener noreferrer">
                                <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5 text-emerald-100" aria-hidden="true">
                                    <path d="M12 20.2c5.1 0 8.2-3.1 8.2-8.2S17.1 3.8 12 3.8 3.8 6.9 3.8 12 6.9 20.2 12 20.2Z" stroke="currentColor" stroke-width="1.3" opacity="0.85"/>
                                    <path d="M10.4 9.8v4.6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M9 8.6h0" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                                    <path d="M13.6 9.8c1.6 0 2.8 1.2 2.8 2.8v2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                </svg>
                                <span class="text-xs text-slate-600 group-hover:text-slate-900 dark:text-slate-200 dark:group-hover:text-white">YouTube</span>
                            </a>
                        </div>

                        <div class="mt-7 text-xs font-semibold uppercase tracking-wider text-slate-700 dark:text-slate-200">{{ $footerContactTitle }}</div>
                        <div class="mt-4 grid gap-2">
                        <a href="tel:{{ preg_replace('/\s+/', '', $sitePhone) }}" class="group inline-flex items-center gap-3 rounded-2xl bg-white/0 px-3 py-2 ring-1 ring-transparent transition hover:bg-slate-100 hover:text-slate-900 hover:ring-slate-200/70 dark:hover:bg-white/5 dark:hover:text-white dark:hover:ring-white/10">
                            <span class="grid h-9 w-9 place-items-center rounded-xl bg-slate-50 ring-1 ring-slate-200/70 dark:bg-white/5 dark:ring-white/10">
                                <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4 text-slate-600 dark:text-slate-200" aria-hidden="true">
                                    <path d="M7 3h2l2 5-2 1c1 3 3 5 6 6l1-2 5 2v2c0 1-1 2-2 2-9 0-16-7-16-16 0-1 1-2 2-2Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round" />
                                </svg>
                            </span>
                            <span>
                                <span class="block text-xs text-slate-500 dark:text-slate-400">{{ $footerPhoneLabel }}</span>
                                <span class="block text-sm">{{ $sitePhone }}</span>
                            </span>
                        </a>

                        <a href="mailto:{{ $siteEmail }}" class="group inline-flex items-center gap-3 rounded-2xl bg-white/0 px-3 py-2 ring-1 ring-transparent transition hover:bg-slate-100 hover:text-slate-900 hover:ring-slate-200/70 dark:hover:bg-white/5 dark:hover:text-white dark:hover:ring-white/10">
                            <span class="grid h-9 w-9 place-items-center rounded-xl bg-slate-50 ring-1 ring-slate-200/70 dark:bg-white/5 dark:ring-white/10">
                                <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4 text-slate-600 dark:text-slate-200" aria-hidden="true">
                                    <path d="M4 6h16v12H4V6Z" stroke="currentColor" stroke-width="1.7" />
                                    <path d="m4 7 8 6 8-6" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round" />
                                </svg>
                            </span>
                            <span>
                                <span class="block text-xs text-slate-500 dark:text-slate-400">{{ $footerEmailLabel }}</span>
                                <span class="block text-sm">{{ $siteEmail }}</span>
                            </span>
                        </a>

                        <div class="inline-flex items-center gap-3 rounded-2xl bg-white/0 px-3 py-2 text-slate-700 ring-1 ring-transparent dark:text-slate-200">
                            <span class="grid h-9 w-9 place-items-center rounded-xl bg-slate-50 ring-1 ring-slate-200/70 dark:bg-white/5 dark:ring-white/10">
                                <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4 text-slate-600 dark:text-slate-200" aria-hidden="true">
                                    <path d="M12 22s7-4.5 7-11a7 7 0 1 0-14 0c0 6.5 7 11 7 11Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round" />
                                    <path d="M12 11.5a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z" stroke="currentColor" stroke-width="1.7" />
                                </svg>
                            </span>
                            <span>
                                <span class="block text-xs text-slate-500 dark:text-slate-400">{{ $footerLocationLabel }}</span>
                                <span class="block text-sm">{{ $siteAddress }}</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-10 flex items-center justify-between gap-4 border-t border-slate-200/70 pt-6 text-[11px] text-slate-500 sm:text-xs dark:border-white/10 dark:text-slate-400">
                <div class="min-w-0 truncate whitespace-nowrap">© {{ date('Y') }} {{ config('app.name', 'iTechBD Ltd') }}. {{ $footerCopyright }}</div>
                <div class="flex shrink-0 items-center gap-4 whitespace-nowrap">
                    <a href="/privacy" class="hover:text-slate-700 dark:hover:text-slate-200">Privacy</a>
                    <a href="/terms" class="hover:text-slate-700 dark:hover:text-slate-200">Terms</a>
                </div>
            </div>
        </div>
    </footer>
    @guest
        <x-auth.login-modal />
        <x-auth.register-modal />
        <x-auth.forgot-password-modal />
        <x-auth.reset-success-modal />
        @if (session('status') === 'verified' || session('status') === 'already-verified')
            <x-auth.verification-status-modal />
        @endif
    @endguest

    @stack('scripts')
</body>
</html>
