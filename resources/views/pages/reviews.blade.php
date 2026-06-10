@extends('layouts.site')

@section('title', 'Reviews • ' . config('app.name', 'iTechBD Ltd'))

@section('content')
<main>
    <section class="border-b border-slate-200/70 dark:border-white/10">
        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            @php
                $hero = $cmsSectionsByKey->get('hero');
            @endphp

            <div class="reveal">
                <h1 class="text-3xl font-semibold text-slate-900 dark:text-white sm:text-4xl">{{ optional($hero)->title ?: __('frontend.home_reviews_title') }}</h1>
                <p class="mt-3 max-w-2xl text-slate-600 dark:text-slate-200">{{ optional($hero)->content ?: __('frontend.home_reviews_subtitle') }}</p>
            </div>

            <div class="mt-10 grid gap-6 md:grid-cols-3">
                @forelse ($reviews ?? [] as $review)
                    @php
                        $rating = max(1, min(5, (int) ($review->rating ?? 5)));
                    @endphp
                    <div class="reveal rounded-3xl bg-white p-6 ring-1 ring-slate-200/70 shadow-sm shadow-slate-200/60 dark:bg-white/5 dark:ring-white/10 dark:shadow-none">
                        <div class="flex items-center gap-1 text-amber-300" aria-label="{{ $rating }} star rating">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4 {{ $i <= $rating ? '' : 'opacity-30' }}">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 0 0 .95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 0 0-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 0 0-1.176 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 0 0-.364-1.118L2.98 8.71c-.783-.57-.38-1.81.588-1.81H7.03a1 1 0 0 0 .951-.69l1.07-3.292Z"/>
                                </svg>
                            @endfor
                        </div>
                        <p class="mt-4 text-sm text-slate-600 dark:text-slate-200">“{{ $review->quote }}”</p>
                        <div class="mt-4 text-xs font-semibold text-slate-900 dark:text-white">
                            — {{ $review->name }}
                            @if(!empty($review->designation))
                                <span class="font-normal text-slate-500 dark:text-slate-300">• {{ $review->designation }}</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="reveal rounded-3xl bg-white p-8 ring-1 ring-slate-200/70 shadow-sm shadow-slate-200/60 dark:bg-white/5 dark:ring-white/10 dark:shadow-none md:col-span-3">
                        <div class="text-slate-900 dark:text-white font-semibold">{{ __('frontend.no_reviews_title') }}</div>
                        <div class="mt-2 text-sm text-slate-600 dark:text-slate-200">{{ __('frontend.no_reviews_body') }}</div>
                    </div>
                @endforelse
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
