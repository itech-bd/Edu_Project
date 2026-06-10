@extends('layouts.site')

@section('title', __('frontend.order_confirmed') . ' • ' . config('app.name', 'iTechBD Ltd'))

@section('content')
<main>
    <section class="border-b border-slate-200/70 dark:border-white/10">
        <div class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
            <a href="/courses/{{ $order->course->getRouteKey() }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900 dark:text-slate-200 dark:hover:text-white">← {{ __('frontend.view_details') }}</a>

            <div class="mt-4 rounded-3xl bg-white p-6 ring-1 ring-slate-200/70 shadow-sm shadow-slate-200/60 dark:bg-white/5 dark:ring-white/10 dark:shadow-none sm:p-10">
                <div class="inline-flex items-center rounded-full bg-emerald-500/10 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-500/20 dark:text-emerald-200">
                    {{ __('frontend.order_confirmed') }}
                </div>

                <h1 class="mt-4 text-3xl font-semibold text-slate-900 dark:text-white">{{ __('frontend.thanks_for_order') }}</h1>
                <p class="mt-3 text-slate-600 dark:text-slate-200">{{ __('frontend.order_confirmed_subtitle') }}</p>

                <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-200/70 dark:bg-white/5 dark:ring-white/10">
                        <div class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-white/70">{{ __('frontend.order_id') }}</div>
                        <div class="mt-1 font-semibold text-slate-900 dark:text-white">#{{ $order->id }}</div>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-200/70 dark:bg-white/5 dark:ring-white/10">
                        <div class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-white/70">{{ __('frontend.selected_course') }}</div>
                        <div class="mt-1 font-semibold text-slate-900 dark:text-white">{{ $order->course->title }}</div>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-200/70 dark:bg-white/5 dark:ring-white/10">
                        <div class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-white/70">{{ __('frontend.amount') }}</div>
                        <div class="mt-1 font-semibold text-slate-900 dark:text-white">{{ number_format((float) $order->amount, 2) }} {{ $order->currency }}</div>
                    </div>
                </div>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="/contact" class="inline-flex items-center justify-center rounded-2xl bg-sky-700 px-6 py-3 text-sm font-semibold text-white hover:bg-sky-800">
                        {{ __('frontend.contact') }}
                    </a>
                    <a href="/courses" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-white/10 dark:bg-white/5 dark:text-white/90 dark:hover:bg-white/[0.07]">
                        {{ __('frontend.explore_courses') }}
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
