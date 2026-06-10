@extends('layouts.site')

@section('title', $course->title . ' • ' . config('app.name', 'iTechBD Ltd'))

@section('content')
<main>
    @php
        $oldPrice = $course->old_price;
        $discountPrice = $course->discount_price;
        $displayPrice = !is_null($discountPrice) ? (float) $discountPrice : (!is_null($oldPrice) ? (float) $oldPrice : null);

        $thumbUrl = $course->thumbnail_url;
        $description = trim((string) $course->description);

        $descriptionLooksLikeHtml = \Illuminate\Support\Str::contains($description, ['<p', '<br', '<ul', '<ol', '<li', '<h', '</']);
        $descriptionAllowedTags = '<p><br><strong><b><em><i><ul><ol><li><a><h2><h3><h4><blockquote><code><pre>';
        $descriptionHtml = strip_tags($description, $descriptionAllowedTags);
        $descriptionHtml = preg_replace('/<\s*(\/?)\s*script\b[^>]*>/i', '', $descriptionHtml);
        $descriptionHtml = preg_replace('/<([a-z0-9]+)\b[^>]*>/i', '<$1>', $descriptionHtml);

        $hasBatches = $course->relationLoaded('batches') && $course->batches->count() > 0;

        $isFullStackLike = \Illuminate\Support\Str::contains(mb_strtolower($course->title), ['full stack', 'full-stack', 'web development']);
        $techStack = $isFullStackLike
            ? ['HTML5', 'CSS', 'JavaScript', 'PHP', 'SQL', 'Git/GitHub', 'Bootstrap 5']
            : [];
    @endphp

    <section class="border-b border-slate-200/70 dark:border-white/10">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <a href="/courses" class="text-sm font-semibold text-slate-600 hover:text-slate-900 dark:text-slate-200 dark:hover:text-white">← {{ __('frontend.courses') }}</a>

            <div class="mt-4 grid grid-cols-1 gap-8 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    <h1 class="text-3xl font-semibold text-slate-900 dark:text-white sm:text-4xl">{{ $course->title }}</h1>

                    @if($techStack)
                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach($techStack as $tech)
                                <span class="inline-flex items-center rounded-full bg-slate-900/5 px-3 py-1 text-xs font-semibold text-slate-700 ring-1 ring-slate-900/10 dark:bg-white/10 dark:text-white/90 dark:ring-white/10">
                                    {{ $tech }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    @if($thumbUrl)
                        <div class="mt-6 overflow-hidden rounded-3xl bg-slate-100 ring-1 ring-slate-200/70 shadow-sm shadow-slate-200/60 dark:bg-slate-950/30 dark:ring-white/10 dark:shadow-none">
                            <div class="aspect-[16/9]">
                                <img src="{{ $thumbUrl }}" alt="{{ $course->title }} thumbnail" class="h-full w-full object-cover" loading="lazy">
                            </div>
                        </div>
                    @endif

                    <div class="mt-8 rounded-3xl bg-white p-6 ring-1 ring-slate-200/70 shadow-sm shadow-slate-200/60 dark:bg-white/5 dark:ring-white/10 dark:shadow-none sm:p-8">
                        <div class="flex items-center justify-between gap-4">
                            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('frontend.course_overview') }}</h2>
                            <span class="inline-flex items-center rounded-full bg-emerald-500/10 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-500/20 dark:text-emerald-200">
                                {{ __('frontend.enroll_now') }}
                            </span>
                        </div>
                        <div class="mt-4 prose prose-slate max-w-none dark:prose-invert">
                            @if($descriptionLooksLikeHtml)
                                {!! $descriptionHtml !!}
                            @else
                                {!! nl2br(e($description)) !!}
                            @endif
                        </div>
                    </div>

                    @if($hasBatches)
                        <div class="mt-8 rounded-3xl bg-white p-6 ring-1 ring-slate-200/70 shadow-sm shadow-slate-200/60 dark:bg-white/5 dark:ring-white/10 dark:shadow-none sm:p-8">
                            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('frontend.upcoming_batches') }}</h2>
                            <div class="mt-4 divide-y divide-slate-200/70 dark:divide-white/10">
                                @foreach($course->batches as $batch)
                                    <div class="py-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                        <div class="min-w-0">
                                            <div class="font-semibold text-slate-900 dark:text-white truncate">{{ $batch->name }}</div>
                                            <div class="mt-1 text-sm text-slate-600 dark:text-slate-200">
                                                <span class="font-semibold">{{ ucfirst($batch->status) }}</span>
                                                <span class="mx-2 text-slate-300 dark:text-white/20">•</span>
                                                <span>
                                                    {{ optional($batch->start_date)->format('d M, Y') }} – {{ optional($batch->end_date)->format('d M, Y') }}
                                                </span>
                                                <span class="mx-2 text-slate-300 dark:text-white/20">•</span>
                                                <span>{{ $batch->class_time }}</span>
                                            </div>
                                        </div>
                                        <a href="/contact" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-white/10 dark:bg-white/5 dark:text-white/90 dark:hover:bg-white/[0.07]">
                                            {{ __('frontend.get_schedule') }}
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mt-8 rounded-3xl bg-gradient-to-br from-sky-700 to-indigo-700 p-8 text-white shadow-sm">
                        <div class="text-sm font-semibold uppercase tracking-wide text-white/80">{{ __('frontend.need_details') }}</div>
                        <div class="mt-2 text-2xl font-semibold">{{ __('frontend.need_details_subtitle') }}</div>
                        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                            <a href="/contact" class="inline-flex items-center justify-center rounded-2xl bg-white px-6 py-3 text-sm font-semibold text-slate-900 hover:bg-slate-100">
                                {{ __('frontend.contact') }}
                            </a>
                            <a href="/courses" class="inline-flex items-center justify-center rounded-2xl bg-white/10 px-6 py-3 text-sm font-semibold text-white ring-1 ring-white/20 hover:bg-white/15">
                                {{ __('frontend.explore_courses') }}
                            </a>
                        </div>
                    </div>
                </div>

                <aside class="lg:col-span-1">
                    <div class="sticky top-6 space-y-6">
                        <div class="rounded-3xl bg-white p-6 ring-1 ring-slate-200/70 shadow-sm shadow-slate-200/60 dark:bg-white/5 dark:ring-white/10 dark:shadow-none">
                            <div class="text-sm font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-200">{{ __('frontend.course_fee') }}</div>

                            @if(!is_null($displayPrice))
                                <div class="mt-2">
                                    <div class="text-3xl font-semibold text-slate-900 dark:text-white">
                                        {{ number_format((float) $displayPrice, 2) }}
                                    </div>

                                    @if(!is_null($oldPrice) && !is_null($discountPrice) && (float) $discountPrice < (float) $oldPrice)
                                        <div class="mt-1 text-sm text-slate-600 dark:text-slate-200">
                                            <span class="line-through">{{ number_format((float) $oldPrice, 2) }}</span>
                                            <span class="mx-2 text-slate-300 dark:text-white/20">•</span>
                                            <span class="font-semibold text-emerald-700 dark:text-emerald-200">{{ __('frontend.discount_price') }}</span>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="mt-2 text-sm text-slate-600 dark:text-slate-200">
                                    {{ __('frontend.contact_for_fee') }}
                                </div>
                            @endif

                            <div class="mt-6 flex flex-col gap-3">
                                          <a href="/courses/{{ $course->getRouteKey() }}/checkout"
                                              @guest data-auth-trigger="login" data-auth-redirect="/courses/{{ $course->getRouteKey() }}/checkout" @endguest
                                   class="inline-flex w-full items-center justify-center rounded-2xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-100">
                                    {{ __('frontend.buy_now') }}
                                </a>
                                <a href="/contact" class="inline-flex w-full items-center justify-center rounded-2xl border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-white/10 dark:bg-white/5 dark:text-white/90 dark:hover:bg-white/[0.07]">
                                    {{ __('frontend.contact') }}
                                </a>
                            </div>

                            <div class="mt-6 rounded-2xl bg-slate-50 p-4 text-sm text-slate-700 ring-1 ring-slate-200/70 dark:bg-white/5 dark:text-white/80 dark:ring-white/10">
                                <div class="font-semibold">{{ __('frontend.need_details') }}</div>
                                <div class="mt-1">{{ __('frontend.course_details_help') }}</div>
                            </div>
                        </div>

                        <div class="rounded-3xl bg-white p-6 ring-1 ring-slate-200/70 shadow-sm shadow-slate-200/60 dark:bg-white/5 dark:ring-white/10 dark:shadow-none">
                            <div class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('frontend.faq_title') }}</div>
                            <div class="mt-1 text-sm text-slate-600 dark:text-slate-200">{{ __('frontend.faq_subtitle') }}</div>

                            <div class="mt-5 space-y-4">
                                <div>
                                    <div class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('frontend.faq_q1_title') }}</div>
                                    <div class="mt-1 text-sm text-slate-600 dark:text-slate-200">{{ __('frontend.faq_q1_answer') }}</div>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('frontend.faq_q2_title') }}</div>
                                    <div class="mt-1 text-sm text-slate-600 dark:text-slate-200">{{ __('frontend.faq_q2_answer') }}</div>
                                </div>
                            </div>

                            <div class="mt-6">
                                <a href="/contact" class="inline-flex w-full items-center justify-center rounded-2xl bg-sky-700 px-6 py-3 text-sm font-semibold text-white hover:bg-sky-800">
                                    {{ __('frontend.contact') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</main>
@endsection
