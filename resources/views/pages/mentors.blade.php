@extends('layouts.site')

@section('title', 'Mentors • ' . config('app.name', 'iTechBD Ltd'))

@section('content')
<main>
    <section class="border-b border-slate-200/70 dark:border-white/10">
        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            @php
                $hero = $cmsSectionsByKey->get('hero');
            @endphp

            <div class="reveal">
                <h1 class="text-3xl font-semibold text-slate-900 dark:text-white sm:text-4xl">{{ optional($hero)->title ?: 'Mentors' }}</h1>
                <p class="mt-3 max-w-2xl text-slate-600 dark:text-slate-200">{{ optional($hero)->content ?: 'Meet mentors from different topics and learn with weekly guidance.' }}</p>
            </div>

            <div class="reveal mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                @forelse ($mentors as $mentor)
                    <div class="rounded-3xl bg-white p-6 text-center ring-1 ring-slate-200/70 shadow-sm shadow-slate-200/60 dark:bg-white/5 dark:ring-white/10 dark:shadow-none">
                        @php
                            $mentorImageUrl = optional($mentor->user)->profile_image_url;
                        @endphp
                        <div class="mx-auto aspect-square w-full overflow-hidden rounded-2xl bg-slate-100 ring-1 ring-slate-200/70 grid place-items-center dark:bg-slate-950/30 dark:ring-white/10">
                            @if (is_string($mentorImageUrl) && $mentorImageUrl !== '')
                                <img
                                    src="{{ $mentorImageUrl }}"
                                    alt="{{ $mentor->name }}"
                                    class="h-full w-full object-cover"
                                    loading="lazy"
                                    decoding="async"
                                />
                            @else
                                <svg viewBox="0 0 24 24" fill="none" class="h-24 w-24 text-slate-500 dark:text-slate-200/70" aria-hidden="true">
                                    <path d="M12 12a5 5 0 1 0 0-10 5 5 0 0 0 0 10Z" fill="currentColor" opacity="0.85" />
                                    <path d="M3.2 21c2.3-4.3 6.2-6.7 8.8-6.7S18.5 16.7 20.8 21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" opacity="0.85" />
                                </svg>
                            @endif
                        </div>
                        <div class="mt-4 text-sm font-semibold text-slate-900 dark:text-white">{{ $mentor->name }}</div>
                        <div class="mt-1 text-xs text-slate-500 dark:text-slate-300">{{ $mentor->topic ?? 'Mentor' }} • Weekly support</div>
                        <div class="mt-5">
                            <a href="{{ route('mentors.show', $mentor->public_route_key) }}" class="mx-auto mt-4 inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white ring-1 ring-slate-900/10 transition hover:bg-slate-800 dark:bg-white/10 dark:ring-white/10 dark:hover:bg-white/15">
                                See Details
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="rounded-3xl bg-white p-6 ring-1 ring-slate-200/70 shadow-sm shadow-slate-200/60 text-slate-600 dark:bg-white/5 dark:ring-white/10 dark:text-slate-200 dark:shadow-none">No mentors available yet.</div>
                @endforelse
            </div>

            <div class="mt-10">
                {{ $mentors->links() }}
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
