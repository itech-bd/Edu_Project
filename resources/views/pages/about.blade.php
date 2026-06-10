@extends('layouts.site')

@section('title', __('frontend.about') . ' • ' . config('app.name', 'iTechBD Ltd'))

@section('content')
<main id="top">
    @php
        $hero = $cmsSectionsByKey->get('hero');
        $intro = $cmsSectionsByKey->get('about_intro');
        $mission = $cmsSectionsByKey->get('about_mission');
        $vision = $cmsSectionsByKey->get('about_vision');
        $statsTitle = $cmsSectionsByKey->get('about_stats_title');
        $cta = $cmsSectionsByKey->get('about_cta');

        $ctaLink = optional($cta)->button_link ?: '/courses';
        $ctaText = optional($cta)->button_text ?: __('frontend.about_page_cta_button');

        $valuesKeys = ['about_value_1', 'about_value_2', 'about_value_3', 'about_value_4', 'about_value_5', 'about_value_6'];
        $statKeys = ['about_stat_1', 'about_stat_2', 'about_stat_3', 'about_stat_4'];

        $renderRich = function (?string $value): array {
            $value = is_string($value) ? trim($value) : '';
            if ($value === '') {
                return [false, ''];
            }

            $isHtml = $value !== strip_tags($value);
            return [$isHtml, $value];
        };

        $richClasses = 'leading-relaxed text-slate-600 dark:text-slate-200 [&_p]:mt-3 [&_ul]:mt-3 [&_ul]:list-disc [&_ul]:pl-5 [&_ol]:mt-3 [&_ol]:list-decimal [&_ol]:pl-5 [&_a]:text-sky-700 dark:[&_a]:text-white [&_a]:underline';
    @endphp

    <!-- Hero -->
    <section class="relative">
        <div class="mx-auto grid max-w-7xl items-center gap-10 px-4 py-14 sm:px-6 lg:grid-cols-12 lg:px-8 lg:py-20">
            <div class="lg:col-span-7">
                <div class="reveal inline-flex items-center gap-2 rounded-full bg-slate-900/5 px-4 py-2 text-xs text-slate-700 ring-1 ring-slate-200/70 shadow-sm shadow-slate-200/60 dark:bg-white/10 dark:text-slate-200 dark:ring-white/10 dark:shadow-none">
                    <span class="itech-pulse-dot inline-flex h-2 w-2 rounded-full bg-emerald-400"></span>
                    {{ __('frontend.footer_cta_pill') }}
                </div>

                <h1 class="reveal mt-6 text-4xl font-semibold tracking-tight text-slate-900 dark:text-white sm:text-5xl lg:text-6xl">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-sky-600 to-emerald-600 dark:from-indigo-300 dark:via-sky-300 dark:to-emerald-300 animate-gradient">{{ optional($hero)->title ?: __('frontend.about_title') }}</span>
                </h1>

                @php
                    [$heroIsHtml, $heroContent] = $renderRich(optional($hero)->content ?: __('frontend.about_subtitle'));
                @endphp
                @if($heroIsHtml)
                    <div class="reveal mt-5 max-w-2xl {{ $richClasses }}">{!! $heroContent !!}</div>
                @else
                    <p class="reveal mt-5 max-w-2xl text-base leading-relaxed text-slate-600 dark:text-slate-200 sm:text-lg">{{ $heroContent }}</p>
                @endif

                <div class="reveal mt-8 flex flex-col gap-3 sm:flex-row sm:items-center">
                    <a href="{{ $ctaLink }}" class="inline-flex items-center justify-center rounded-2xl bg-white px-6 py-3 text-sm font-semibold text-slate-950 transition hover:bg-slate-100">
                        {{ $ctaText }}
                    </a>
                    <a href="#story" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white ring-1 ring-slate-900/10 transition hover:bg-slate-800 dark:bg-white/10 dark:ring-white/10 dark:hover:bg-white/15">
                        {{ __('frontend.about_page_intro_title') }} →
                    </a>
                </div>
            </div>

            <div class="lg:col-span-5">
                <div class="reveal relative overflow-hidden rounded-3xl bg-white p-6 ring-1 ring-slate-200/70 shadow-sm shadow-slate-200/60 dark:bg-white/5 dark:ring-white/10 dark:shadow-none">
                    <div class="absolute inset-0 opacity-30"
                         style="background: radial-gradient(circle at 20% 10%, rgba(99,102,241,.35), transparent 55%), radial-gradient(circle at 80% 30%, rgba(14,165,233,.30), transparent 50%), radial-gradient(circle at 40% 90%, rgba(16,185,129,.25), transparent 55%);"></div>

                    <div class="relative">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs text-slate-500 dark:text-slate-300">{{ __('frontend.about_page_stats_title') }}</div>
                                <div class="mt-1 text-lg font-semibold text-slate-900 dark:text-white">{{ optional($statsTitle)->title ?: __('frontend.about_page_stats_title') }}</div>
                            </div>
                            <div class="rounded-2xl bg-slate-900/5 px-3 py-2 text-xs text-slate-700 ring-1 ring-slate-200/70 dark:bg-white/10 dark:text-slate-200 dark:ring-white/10">
                                {{ __('frontend.hero_badge_secondary') }}
                            </div>
                        </div>

                        <div class="mt-6 grid gap-4 sm:grid-cols-2">
                            @foreach($statKeys as $i => $key)
                                @php
                                    $stat = $cmsSectionsByKey->get($key);
                                    $value = optional($stat)->title ?: __('frontend.about_page_stat_' . ($i + 1) . '_value');
                                    $label = optional($stat)->content ?: __('frontend.about_page_stat_' . ($i + 1) . '_label');
                                @endphp
                                <div class="rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-200/70 dark:bg-slate-950/40 dark:ring-white/10">
                                    <div class="text-xs text-slate-500 dark:text-slate-300">{{ $label }}</div>
                                    <div class="mt-1 text-xl font-semibold text-slate-900 dark:text-white">{{ $value }}</div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6 grid gap-4">
                            <div class="rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-200/70 dark:bg-white/10 dark:ring-white/10">
                                <div class="flex items-start gap-3">
                                    <div class="mt-0.5 inline-flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-500/20 ring-1 ring-indigo-400/20">
                                        <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5 text-indigo-700 dark:text-indigo-100" aria-hidden="true">
                                            <path d="M7 7h10M7 11h10M7 15h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                                            <path d="M6 3h12a2 2 0 0 1 2 2v14l-3-2-3 2-3-2-3 2-3-2V5a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-slate-900 dark:text-white">{{ optional($mission)->title ?: __('frontend.about_page_mission_title') }}</div>
                                        @php
                                            [$missionIsHtml, $missionContent] = $renderRich(optional($mission)->content ?: __('frontend.about_page_mission_body'));
                                        @endphp
                                        @if($missionIsHtml)
                                            <div class="mt-1 text-sm {{ $richClasses }}">{!! $missionContent !!}</div>
                                        @else
                                            <div class="mt-1 text-sm text-slate-600 dark:text-slate-200">{{ $missionContent }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-200/70 dark:bg-white/10 dark:ring-white/10">
                                <div class="flex items-start gap-3">
                                    <div class="mt-0.5 inline-flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-500/20 ring-1 ring-emerald-400/20">
                                        <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5 text-emerald-700 dark:text-emerald-100" aria-hidden="true">
                                            <path d="M12 21s7-4 7-11a7 7 0 1 0-14 0c0 7 7 11 7 11Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round" />
                                            <path d="M9.5 11.5 11 13l3.5-4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-slate-900 dark:text-white">{{ optional($vision)->title ?: __('frontend.about_page_vision_title') }}</div>
                                        @php
                                            [$visionIsHtml, $visionContent] = $renderRich(optional($vision)->content ?: __('frontend.about_page_vision_body'));
                                        @endphp
                                        @if($visionIsHtml)
                                            <div class="mt-1 text-sm {{ $richClasses }}">{!! $visionContent !!}</div>
                                        @else
                                            <div class="mt-1 text-sm text-slate-600 dark:text-slate-200">{{ $visionContent }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Story -->
    <section id="story" class="border-t border-slate-200/70 dark:border-white/10">
        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <div class="reveal grid gap-10 lg:grid-cols-12 lg:items-start">
                <div class="lg:col-span-5">
                    <div class="rounded-3xl bg-white p-6 ring-1 ring-slate-200/70 shadow-sm shadow-slate-200/60 dark:bg-white/5 dark:ring-white/10 dark:shadow-none">
                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-300">{{ __('frontend.about') }}</div>
                        <h2 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white sm:text-3xl">{{ optional($intro)->title ?: __('frontend.about_page_intro_title') }}</h2>
                        <div class="mt-5 flex flex-wrap gap-2 text-xs text-slate-600 dark:text-slate-200">
                            <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-slate-700 ring-1 ring-slate-200/70 dark:bg-slate-950/30 dark:text-slate-200 dark:ring-white/10">{{ __('frontend.footer_cta_tag_weekly_reviews') }}</span>
                            <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-slate-700 ring-1 ring-slate-200/70 dark:bg-slate-950/30 dark:text-slate-200 dark:ring-white/10">{{ __('frontend.footer_cta_tag_portfolio_projects') }}</span>
                            <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-slate-700 ring-1 ring-slate-200/70 dark:bg-slate-950/30 dark:text-slate-200 dark:ring-white/10">{{ __('frontend.footer_cta_tag_career_support') }}</span>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-7">
                    @php
                        [$introIsHtml, $introContent] = $renderRich(optional($intro)->content ?: __('frontend.about_page_intro_body'));
                    @endphp
                    @if($introIsHtml)
                        <div class="rounded-3xl bg-white p-8 ring-1 ring-slate-200/70 shadow-sm shadow-slate-200/60 dark:bg-white/5 dark:ring-white/10 dark:shadow-none {{ $richClasses }}">{!! $introContent !!}</div>
                    @else
                        <div class="rounded-3xl bg-white p-8 ring-1 ring-slate-200/70 shadow-sm shadow-slate-200/60 dark:bg-white/5 dark:ring-white/10 dark:shadow-none">
                            <p class="text-base leading-relaxed text-slate-600 dark:text-slate-200 sm:text-lg">{{ $introContent }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Values -->
    <section class="border-t border-slate-200/70 dark:border-white/10">
        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <div class="reveal flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-2xl font-semibold text-slate-900 dark:text-white sm:text-3xl">{{ __('frontend.about_page_values_title') }}</h2>
                    <p class="mt-2 max-w-3xl text-slate-600 dark:text-slate-200">{{ __('frontend.about_page_values_subtitle') }}</p>
                </div>
            </div>

            @php
                $valueAccent = [
                    ['bg' => 'bg-indigo-500/20', 'ring' => 'ring-indigo-400/20', 'text' => 'text-indigo-700 dark:text-indigo-100'],
                    ['bg' => 'bg-sky-500/20', 'ring' => 'ring-sky-400/20', 'text' => 'text-sky-700 dark:text-sky-100'],
                    ['bg' => 'bg-emerald-500/20', 'ring' => 'ring-emerald-400/20', 'text' => 'text-emerald-700 dark:text-emerald-100'],
                    ['bg' => 'bg-violet-500/20', 'ring' => 'ring-violet-400/20', 'text' => 'text-violet-700 dark:text-violet-100'],
                ];
            @endphp

            <div class="reveal mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($valuesKeys as $idx => $key)
                    @php
                        $c = $valueAccent[$idx % count($valueAccent)];
                        $valueSection = $cmsSectionsByKey->get($key);
                        $fallbackTitle = __('frontend.about_page_value_' . ($idx + 1) . '_title');
                        $fallbackDesc = __('frontend.about_page_value_' . ($idx + 1) . '_desc');
                        [$descIsHtml, $desc] = $renderRich(optional($valueSection)->content ?: $fallbackDesc);
                    @endphp

                    <div class="group rounded-3xl bg-white p-6 ring-1 ring-slate-200/70 shadow-sm shadow-slate-200/60 transition hover:bg-slate-50 dark:bg-white/5 dark:ring-white/10 dark:hover:bg-white/[0.07] dark:shadow-none">
                        <div class="flex items-start gap-3">
                            <div class="inline-flex h-10 w-10 items-center justify-center rounded-2xl {{ $c['bg'] }} ring-1 {{ $c['ring'] }}">
                                <div class="text-sm font-semibold {{ $c['text'] }}">{{ $idx + 1 }}</div>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-slate-900 dark:text-white">{{ optional($valueSection)->title ?: $fallbackTitle }}</div>
                                @if($descIsHtml)
                                    <div class="mt-2 text-sm {{ $richClasses }}">{!! $desc !!}</div>
                                @else
                                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-200">{{ $desc }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="border-t border-slate-200/70 dark:border-white/10">
        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <div class="reveal overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-500/15 via-sky-500/10 to-emerald-500/15 p-8 ring-1 ring-slate-200/70 shadow-sm shadow-slate-200/60 dark:ring-white/10 dark:shadow-none">
                <div class="grid gap-6 lg:grid-cols-12 lg:items-center">
                    <div class="lg:col-span-8">
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-white">{{ optional($cta)->title ?: __('frontend.about_page_cta_title') }}</h2>
                        @php
                            [$ctaIsHtml, $ctaContent] = $renderRich(optional($cta)->content ?: __('frontend.about_page_cta_body'));
                        @endphp
                        @if($ctaIsHtml)
                            <div class="mt-3 {{ $richClasses }}">{!! $ctaContent !!}</div>
                        @else
                            <p class="mt-3 text-slate-600 dark:text-slate-200">{{ $ctaContent }}</p>
                        @endif
                    </div>
                    <div class="lg:col-span-4 lg:text-right">
                        <a href="{{ $ctaLink }}" class="inline-flex items-center justify-center rounded-2xl bg-white px-6 py-3 text-sm font-semibold text-slate-950 transition hover:bg-slate-100">
                            {{ $ctaText }}
                        </a>
                        <div class="mt-3 text-xs text-slate-500 dark:text-slate-300">{{ optional($statsTitle)->content ?: __('frontend.about_page_stats_subtitle') }}</div>
                    </div>
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
