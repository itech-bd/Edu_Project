@extends('layouts.site')

@section('title', ($newsUpdate->title ?? 'News') . ' • ' . config('app.name', 'iTechBD Ltd'))

@section('content')
<main>
    <section class="border-b border-slate-200/70 dark:border-white/10">
        <div class="mx-auto max-w-3xl px-4 py-14 sm:px-6 lg:px-8">
            <div class="reveal">
                <a href="{{ route('news') }}" class="text-sm font-semibold text-slate-600 hover:underline dark:text-slate-200">← Back to news</a>

                <h1 class="mt-4 text-3xl font-semibold text-slate-900 dark:text-white sm:text-4xl">{{ $newsUpdate->title }}</h1>

                @php
                    $dt = $newsUpdate->published_at ?: $newsUpdate->created_at;
                @endphp
                <div class="mt-3 text-sm text-slate-500 dark:text-slate-300">
                    {{ $dt ? $dt->format('d M Y') : '' }}
                </div>

                @if (is_string($newsUpdate->excerpt) && trim($newsUpdate->excerpt) !== '')
                    <p class="mt-5 text-base text-slate-700 dark:text-slate-200">{{ $newsUpdate->excerpt }}</p>
                @endif

                <article class="prose prose-slate mt-8 max-w-none dark:prose-invert">
                    {!! $newsUpdate->body !!}
                </article>
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
