@extends('layouts.site')

@section('title', 'Contact • ' . config('app.name', 'iTechBD Ltd'))

@section('content')
<main>
    <section class="border-b border-slate-200/70 dark:border-white/10">
        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            @php
                $hero = $cmsSectionsByKey->get('hero');
                $emailSection = $cmsSectionsByKey->get('contact_email');
                $phoneSection = $cmsSectionsByKey->get('contact_phone');
                $currentUser = auth()->user();

                $normalizeInlineText = function ($value): string {
                    $text = trim((string) $value);
                    if ($text === '') {
                        return '';
                    }

                    $text = preg_replace('/<\s*br\s*\/?\s*>/i', "\n", $text) ?? $text;
                    $text = strip_tags($text);
                    $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    $text = preg_replace('/\s+/u', ' ', $text) ?? $text;

                    return trim($text);
                };

                $rawEmailText = $normalizeInlineText(optional($emailSection)->content);
                $rawPhoneText = $normalizeInlineText(optional($phoneSection)->content);

                $emailLabel = optional($emailSection)->title ?: __('frontend.contact_email_label');
                $emailValue = $rawEmailText;
                if ($emailValue !== '' && preg_match('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}/i', $emailValue, $m)) {
                    $emailValue = $m[0];
                }
                $emailValue = $emailValue !== '' ? $emailValue : 'info@example.com';
                $emailHref = optional($emailSection)->button_link ?: ('mailto:' . $emailValue);

                $phoneLabel = optional($phoneSection)->title ?: __('frontend.contact_phone_label');
                $phoneValue = $rawPhoneText;
                if ($phoneValue !== '' && preg_match('/\+?[0-9][0-9\s\-().]{6,}/', $phoneValue, $m)) {
                    $phoneValue = trim($m[0]);
                }
                $phoneValue = $phoneValue !== '' ? $phoneValue : '+880 10 0000 0000';

                $phoneTel = preg_replace('/[^\d+]/', '', $phoneValue);
                $phoneHref = optional($phoneSection)->button_link ?: ('tel:' . $phoneTel);
            @endphp

            <div class="reveal">
                <h1 class="text-3xl font-semibold text-slate-900 dark:text-white sm:text-4xl">{{ optional($hero)->title ?: __('frontend.contact_title') }}</h1>
                <p class="mt-3 max-w-2xl text-slate-600 dark:text-slate-200">{{ optional($hero)->content ?: __('frontend.contact_subtitle') }}</p>
            </div>

            <div class="reveal mt-10 grid gap-6 lg:grid-cols-[minmax(0,1.15fr)_minmax(320px,0.85fr)]">
                <div id="contact-form" class="rounded-3xl bg-white p-6 ring-1 ring-slate-200/70 shadow-sm shadow-slate-200/60 dark:bg-white/5 dark:ring-white/10 dark:shadow-none sm:p-8">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-semibold text-slate-900 dark:text-white">Send us a message</h2>
                            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Share your question, project idea, or support request and our team will get back to you directly.</p>
                        </div>
                        <div class="hidden rounded-2xl bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-600 dark:bg-white/10 dark:text-slate-200 sm:block">Reply within 1-2 business days</div>
                    </div>

                    @if (session('contact_success'))
                        <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-200 dark:bg-emerald-50 dark:text-emerald-900">
                            {{ session('contact_success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('contact.store') }}" class="mt-6 space-y-5"
                          data-recaptcha-action="contact">
                        @csrf

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="contact_name" class="block text-sm font-medium text-slate-700 dark:text-slate-200">Full name</label>
                                <input id="contact_name" name="name" type="text" value="{{ old('name', $currentUser?->name) }}" class="contact-form-field mt-2 w-full rounded-2xl border-slate-300 bg-white/90 text-slate-900 placeholder:text-slate-400 shadow-sm focus:border-sky-500 focus:ring-sky-500 dark:border-white/10 dark:bg-slate-800/90 dark:text-white dark:placeholder:text-slate-400" required>
                                @error('name') <p class="mt-2 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="contact_email_input" class="block text-sm font-medium text-slate-700 dark:text-slate-200">Email address</label>
                                <input id="contact_email_input" name="email" type="email" value="{{ old('email', $currentUser?->email) }}" class="contact-form-field mt-2 w-full rounded-2xl border-slate-300 bg-white/90 text-slate-900 placeholder:text-slate-400 shadow-sm focus:border-sky-500 focus:ring-sky-500 dark:border-white/10 dark:bg-slate-800/90 dark:text-white dark:placeholder:text-slate-400" required>
                                @error('email') <p class="mt-2 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="contact_phone" class="block text-sm font-medium text-slate-700 dark:text-slate-200">Phone number</label>
                                <input id="contact_phone" name="phone" type="text" value="{{ old('phone') }}" required class="contact-form-field mt-2 w-full rounded-2xl border-slate-300 bg-white/90 text-slate-900 placeholder:text-slate-400 shadow-sm focus:border-sky-500 focus:ring-sky-500 dark:border-white/10 dark:bg-slate-800/90 dark:text-white dark:placeholder:text-slate-400">
                                @error('phone') <p class="mt-2 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="contact_subject" class="block text-sm font-medium text-slate-700 dark:text-slate-200">Subject</label>
                                <input id="contact_subject" name="subject" type="text" value="{{ old('subject') }}" class="contact-form-field mt-2 w-full rounded-2xl border-slate-300 bg-white/90 text-slate-900 placeholder:text-slate-400 shadow-sm focus:border-sky-500 focus:ring-sky-500 dark:border-white/10 dark:bg-slate-800/90 dark:text-white dark:placeholder:text-slate-400" required>
                                @error('subject') <p class="mt-2 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label for="contact_message" class="block text-sm font-medium text-slate-700 dark:text-slate-200">Message</label>
                            <textarea id="contact_message" name="message" rows="6" class="contact-form-field mt-2 w-full rounded-2xl border-slate-300 bg-white/90 text-slate-900 placeholder:text-slate-400 shadow-sm focus:border-sky-500 focus:ring-sky-500 dark:border-white/10 dark:bg-slate-800/90 dark:text-white dark:placeholder:text-slate-400" required>{{ old('message') }}</textarea>
                            @error('message') <p class="mt-2 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p> @enderror
                        </div>

                        @if (config('recaptcha.enabled') && config('recaptcha.site_key'))
                            <div>
                                @if (config('recaptcha.version') === 'v3')
                                    <input type="hidden" name="g-recaptcha-response" value="" />
                                @else
                                    <div class="g-recaptcha" data-sitekey="{{ config('recaptcha.site_key') }}"></div>
                                @endif
                                @error('g-recaptcha-response')
                                    <p class="mt-2 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <p class="text-sm text-slate-500 dark:text-slate-400">By submitting this form, you allow us to contact you regarding your enquiry.</p>
                            <button type="submit" class="inline-flex items-center justify-center rounded-full bg-sky-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-500">Send Message</button>
                        </div>
                    </form>
                </div>

                <div class="grid gap-6 sm:grid-cols-2">
                    <div class="group relative overflow-hidden rounded-3xl border border-slate-200/70 bg-white/90 p-6 text-center shadow-lg shadow-slate-200/50 transition duration-300 hover:-translate-y-1 hover:shadow-sky-200/40 dark:border-white/10 dark:bg-white/5 dark:shadow-none dark:hover:border-sky-400/30">
                        <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-sky-500 via-cyan-400 to-emerald-400 opacity-90"></div>
                        <div class="flex flex-col items-center gap-4">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-sky-100 text-sky-700 ring-1 ring-sky-200 shadow-sm dark:bg-sky-100 dark:text-sky-900 dark:ring-sky-200">
                                <svg viewBox="0 0 24 24" fill="none" class="h-7 w-7" aria-hidden="true">
                                    <path d="M4 6h16v12H4V6Z" stroke="currentColor" stroke-width="1.8" />
                                    <path d="m4 7 8 6 8-6" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                                </svg>
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ $emailLabel }}</div>
                                <div class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">Email us anytime</div>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-300">Best for admissions, support, and general enquiries.</p>
                                <a href="{{ $emailHref }}" class="mt-4 inline-flex items-center justify-center gap-2 text-sm font-semibold text-sky-700 transition hover:text-sky-800 dark:text-sky-200 dark:hover:text-sky-100">
                                    <span class="truncate">{{ $emailValue }}</span>
                                    <svg viewBox="0 0 20 20" fill="none" class="h-4 w-4" aria-hidden="true">
                                        <path d="M4.5 10h11" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                                        <path d="m10.5 4 5.5 6-5.5 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="group relative overflow-hidden rounded-3xl border border-slate-200/70 bg-white/90 p-6 text-center shadow-lg shadow-slate-200/50 transition duration-300 hover:-translate-y-1 hover:shadow-emerald-200/40 dark:border-white/10 dark:bg-white/5 dark:shadow-none dark:hover:border-emerald-400/30">
                        <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-emerald-500 via-teal-400 to-sky-400 opacity-90"></div>
                        <div class="flex flex-col items-center gap-4">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200 shadow-sm dark:bg-emerald-100 dark:text-emerald-900 dark:ring-emerald-200">
                                <svg viewBox="0 0 24 24" fill="none" class="h-7 w-7" aria-hidden="true">
                                    <path d="M7 3h2l2 5-2 1c1 3 3 5 6 6l1-2 5 2v2c0 1-1 2-2 2-9 0-16-7-16-16 0-1 1-2 2-2Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                                </svg>
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ $phoneLabel }}</div>
                                <div class="mt-2 text-lg font-semibold text-slate-900 dark:text-white">Talk to our team</div>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-300">Reach us directly for urgent questions during business hours.</p>
                                <a href="{{ $phoneHref }}" class="mt-4 inline-flex items-center justify-center gap-2 text-sm font-semibold text-emerald-700 transition hover:text-emerald-800 dark:text-emerald-200 dark:hover:text-emerald-100">
                                    <span>{{ $phoneValue }}</span>
                                    <svg viewBox="0 0 20 20" fill="none" class="h-4 w-4" aria-hidden="true">
                                        <path d="M4.5 10h11" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                                        <path d="m10.5 4 5.5 6-5.5 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@push('scripts')
@if (config('recaptcha.enabled') && config('recaptcha.site_key'))
    @if (config('recaptcha.version') === 'v3')
        <script src="https://www.google.com/recaptcha/api.js?render={{ config('recaptcha.site_key') }}" async defer></script>
        <script>
            window.__recaptcha = {
                enabled: true,
                version: 'v3',
                siteKey: @json(config('recaptcha.site_key')),
            };
        </script>
    @else
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
@endif
<script>
    (function () {
        var revealEls = Array.prototype.slice.call(document.querySelectorAll('.reveal'));
        if (!('IntersectionObserver' in window)) {
            revealEls.forEach(function (el) { el.classList.add('is-visible'); });
            return;
        }
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -10% 0px' });
        revealEls.forEach(function (el) { observer.observe(el); });
    })();
</script>
@endpush

@push('head')
<style>
    .dark .contact-form-field {
        background-color: rgba(30, 41, 59, 0.9) !important;
        color: #ffffff !important;
        caret-color: #ffffff;
    }

    .dark .contact-form-field::placeholder {
        color: #94a3b8 !important;
    }

    .contact-form-field:-webkit-autofill,
    .contact-form-field:-webkit-autofill:hover,
    .contact-form-field:-webkit-autofill:focus,
    .contact-form-field:-webkit-autofill:active {
        -webkit-text-fill-color: #0f172a;
        caret-color: #0f172a;
        box-shadow: 0 0 0 1000px #ffffff inset;
        -webkit-box-shadow: 0 0 0 1000px #ffffff inset;
        transition: background-color 9999s ease-in-out 0s;
    }

    .dark .contact-form-field:-webkit-autofill,
    .dark .contact-form-field:-webkit-autofill:hover,
    .dark .contact-form-field:-webkit-autofill:focus,
    .dark .contact-form-field:-webkit-autofill:active {
        -webkit-text-fill-color: #ffffff;
        caret-color: #ffffff;
        background-color: rgba(30, 41, 59, 0.9) !important;
        box-shadow: 0 0 0 1000px rgba(30, 41, 59, 0.9) inset;
        -webkit-box-shadow: 0 0 0 1000px rgba(30, 41, 59, 0.9) inset;
    }
</style>
@endpush
