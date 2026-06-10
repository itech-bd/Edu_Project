@extends('layouts.site')

@section('title', 'Privacy • ' . config('app.name', 'iTechBD Ltd'))

@section('content')
<main>
    <section class="border-b border-slate-200/70 dark:border-white/10">
        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            @php
                $hero = $cmsSectionsByKey->get('hero');
                $body = $cmsSectionsByKey->get('privacy_body');
            @endphp

            <div class="reveal">
                <h1 class="text-3xl font-semibold text-slate-900 dark:text-white sm:text-4xl">{{ optional($hero)->title ?: 'Privacy Policy' }}</h1>
                <p class="mt-3 max-w-3xl text-slate-600 dark:text-slate-200">{{ optional($hero)->content ?: 'This is a placeholder privacy policy page. Replace this content with your real policy.' }}</p>
            </div>
        </div>
    </section>

    <section class="border-b border-slate-200/70 dark:border-white/10">
        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <div class="reveal rounded-3xl bg-white p-8 ring-1 ring-slate-200/70 shadow-sm shadow-slate-200/60 dark:bg-white/5 dark:ring-white/10 dark:shadow-none">
                <div class="text-sm leading-relaxed text-slate-600 dark:text-slate-200 [&_p]:mt-3 [&_ul]:mt-3 [&_ul]:list-disc [&_ul]:pl-5 [&_ol]:mt-3 [&_ol]:list-decimal [&_ol]:pl-5 [&_a]:text-sky-700 dark:[&_a]:text-white [&_a]:underline">
                    {!! trim((string) (optional($body)->content ?: '<p>We respect your privacy. Update this section with how you collect, use, and protect user data.</p><ul><li>What data you collect</li><li>How you use it</li><li>How users can request deletion</li></ul>')) !!}
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@push('scripts')
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
