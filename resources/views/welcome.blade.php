@extends('layouts.site')

@section('title', config('app.name', 'iTechBD Ltd'))

@section('content')

    @php
        $heroPrimary = $cmsSectionsByKey->get('hero_primary');
        $heroEmphasis = $cmsSectionsByKey->get('hero_emphasis');
        $heroParagraph = $cmsSectionsByKey->get('hero_paragraph');
        $heroCtaPrimary = $cmsSectionsByKey->get('hero_cta_primary');

        $fallbackReasonsByIndex = [
            1 => [
                'title' => __('frontend.hero_reason_career_title'),
                'subtitle' => __('frontend.hero_reason_career_subtitle'),
                'desc' => __('frontend.hero_reason_career_desc'),
            ],
            2 => [
                'title' => __('frontend.hero_reason_freelance_title'),
                'subtitle' => __('frontend.hero_reason_freelance_subtitle'),
                'desc' => __('frontend.hero_reason_freelance_desc'),
            ],
            3 => [
                'title' => __('frontend.hero_reason_progress_title'),
                'subtitle' => __('frontend.hero_reason_progress_subtitle'),
                'desc' => __('frontend.hero_reason_progress_desc'),
            ],
            4 => [
                'title' => __('frontend.hero_reason_community_title'),
                'subtitle' => __('frontend.hero_reason_community_subtitle'),
                'desc' => __('frontend.hero_reason_community_desc'),
            ],
        ];

        $parseHeroReason = function ($section, array $fallback): array {
            $title = trim((string) optional($section)->title);
            if ($title === '' && isset($fallback['title'])) {
                $title = $fallback['title'];
            }

            $raw = trim((string) optional($section)->content);
            if ($raw === '') {
                return [
                    'title' => $title,
                    'subtitle' => (string) ($fallback['subtitle'] ?? ''),
                    'desc' => (string) ($fallback['desc'] ?? ''),
                ];
            }

            $lines = preg_split("/\r\n|\r|\n/", $raw) ?: [];
            $lines = array_values(array_filter(array_map('trim', $lines), fn ($v) => $v !== ''));

            if (count($lines) === 0) {
                return [
                    'title' => $title,
                    'subtitle' => (string) ($fallback['subtitle'] ?? ''),
                    'desc' => (string) ($fallback['desc'] ?? ''),
                ];
            }

            if (count($lines) === 1) {
                return [
                    'title' => $title,
                    'subtitle' => '',
                    'desc' => $lines[0],
                ];
            }

            return [
                'title' => $title,
                'subtitle' => $lines[0],
                'desc' => trim(implode(' ', array_slice($lines, 1))),
            ];
        };

        $cmsReasonSections = $cmsSectionsByKey
            ->filter(fn ($section, $key) => is_string($key) && str_starts_with($key, 'hero_different_reason_'))
            ->sortBy(function ($section, $key) {
                if (is_string($key) && preg_match('/^hero_different_reason_(\d+)$/', $key, $m)) {
                    return (int) $m[1];
                }

                return 9999;
            });

        $heroReasons = [];
        foreach ($cmsReasonSections as $key => $section) {
            if (!is_string($key) || !preg_match('/^hero_different_reason_(\d+)$/', $key, $m)) {
                continue;
            }

            $i = (int) $m[1];
            $heroReasons[] = $parseHeroReason($section, $fallbackReasonsByIndex[$i] ?? []);
        }

        if (count($heroReasons) === 0) {
            foreach ($fallbackReasonsByIndex as $fallback) {
                $heroReasons[] = $parseHeroReason(null, $fallback);
            }
        }
    @endphp

    <main id="top">
        <!-- Hero -->
        <section class="relative">
            <div class="mx-auto grid max-w-7xl items-center gap-10 px-4 py-14 sm:px-6 lg:grid-cols-12 lg:px-8 lg:py-20">
                <div class="lg:col-span-7">
                    <div class="reveal inline-flex items-center gap-2 rounded-full bg-slate-900/5 px-4 py-2 text-xs text-slate-700 shadow-sm shadow-slate-200/60 ring-1 ring-slate-200/70 dark:bg-white/10 dark:text-slate-200 dark:shadow-none dark:ring-white/10">
                        <span class="itech-pulse-dot inline-flex h-2 w-2 rounded-full bg-emerald-400"></span>
                        {{ __('frontend.hero_badge_primary') }}
                        <span class="hidden text-slate-500 dark:text-slate-300 sm:inline">•</span>
                        <span class="hidden text-slate-500 dark:text-slate-300 sm:inline">{{ __('frontend.hero_badge_secondary') }}</span>
                    </div>

                    <h1 class="reveal mt-6 text-4xl font-semibold tracking-tight text-slate-900 dark:text-white sm:text-5xl lg:text-6xl">
                        {{ optional($heroPrimary)->title ?: __('frontend.hero_heading_primary') }}
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-sky-600 to-emerald-600 dark:from-indigo-300 dark:via-sky-300 dark:to-emerald-300 animate-gradient">{{ optional($heroEmphasis)->title ?: __('frontend.hero_heading_emphasis') }}</span>
                    </h1>

                    <p class="reveal mt-5 max-w-2xl text-base leading-relaxed text-slate-600 dark:text-slate-200 sm:text-lg">
                        {!! optional($heroParagraph)->content ?: __('frontend.hero_paragraph') !!}
                    </p>

                    <div class="reveal mt-8 flex flex-col gap-3 sm:flex-row sm:items-center">
                        <a href="{{ optional($heroCtaPrimary)->button_link ?: '/courses' }}"
                           class="inline-flex items-center justify-center rounded-2xl bg-white px-6 py-3 text-sm font-semibold text-slate-950 transition hover:bg-slate-100">
                            {{ optional($heroCtaPrimary)->button_text ?: 'Explore Courses' }}
                        </a>
                        <a href="#outcomes"
                           class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white ring-1 ring-slate-900/10 transition hover:bg-slate-800 dark:bg-white/10 dark:ring-white/10 dark:hover:bg-white/15">
                            {{ __('frontend.hero_cta_outcomes') }}
                        </a>
                    </div>

                    <div class="reveal mt-10 grid grid-cols-2 gap-4 sm:grid-cols-4">
                        <div class="rounded-2xl bg-white p-4 shadow-sm shadow-slate-200/60 ring-1 ring-slate-200/70 dark:bg-white/5 dark:shadow-none dark:ring-white/10">
                            <div class="text-xs text-slate-500 dark:text-slate-300">{{ __('frontend.hero_stat_live_label') }}</div>
                            <div class="mt-1 font-semibold text-slate-900 dark:text-white">{{ __('frontend.hero_stat_live_value') }}</div>
                        </div>
                        <div class="rounded-2xl bg-white p-4 shadow-sm shadow-slate-200/60 ring-1 ring-slate-200/70 dark:bg-white/5 dark:shadow-none dark:ring-white/10">
                            <div class="text-xs text-slate-500 dark:text-slate-300">{{ __('frontend.hero_stat_projects_label') }}</div>
                            <div class="mt-1 font-semibold text-slate-900 dark:text-white">{{ __('frontend.hero_stat_projects_value') }}</div>
                        </div>
                        <div class="rounded-2xl bg-white p-4 shadow-sm shadow-slate-200/60 ring-1 ring-slate-200/70 dark:bg-white/5 dark:shadow-none dark:ring-white/10">
                            <div class="text-xs text-slate-500 dark:text-slate-300">{{ __('frontend.hero_stat_support_label') }}</div>
                            <div class="mt-1 font-semibold text-slate-900 dark:text-white">{{ __('frontend.hero_stat_support_value') }}</div>
                        </div>
                        <div class="rounded-2xl bg-white p-4 shadow-sm shadow-slate-200/60 ring-1 ring-slate-200/70 dark:bg-white/5 dark:shadow-none dark:ring-white/10">
                            <div class="text-xs text-slate-500 dark:text-slate-300">{{ __('frontend.hero_stat_community_label') }}</div>
                            <div class="mt-1 font-semibold text-slate-900 dark:text-white">{{ __('frontend.hero_stat_community_value') }}</div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-5">
                    <div class="reveal relative overflow-hidden rounded-3xl bg-white p-6 shadow-sm shadow-slate-200/60 ring-1 ring-slate-200/70 dark:bg-white/5 dark:shadow-none dark:ring-white/10">
                        <div class="absolute inset-0 opacity-30"
                             style="background: radial-gradient(circle at 20% 10%, rgba(99,102,241,.35), transparent 55%), radial-gradient(circle at 80% 30%, rgba(14,165,233,.30), transparent 50%), radial-gradient(circle at 40% 90%, rgba(16,185,129,.25), transparent 55%);"></div>

                        <div class="relative">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-xs text-slate-500 dark:text-slate-300">{{ __('frontend.hero_side_why_choose_us') }}</div>
                                    <div class="mt-1 text-lg font-semibold text-slate-900 dark:text-white">{{ optional($cmsSectionsByKey->get('hero_side_heading'))->title ?: __('frontend.hero_side_what_makes_different') }}</div>
                                </div>
                                <div class="rounded-2xl bg-slate-900/5 px-3 py-2 text-xs text-slate-700 shadow-sm shadow-slate-200/60 ring-1 ring-slate-200/70 dark:bg-white/10 dark:text-slate-200 dark:shadow-none dark:ring-white/10">
                                    {{ __('frontend.hero_side_pill') }}
                                </div>
                            </div>

                            <div class="mt-6">
                                <div id="differentReasonsViewport" class="overflow-hidden" data-force-motion="1">
                                    <div id="differentReasonsTrack" class="flex flex-col gap-4 will-change-transform">
                                        @php
                                            $reasonColorClasses = [
                                                ['bg' => 'bg-indigo-500/20', 'ring' => 'ring-indigo-400/20', 'title' => 'text-indigo-700 dark:text-indigo-100'],
                                                ['bg' => 'bg-sky-500/20', 'ring' => 'ring-sky-400/20', 'title' => 'text-sky-700 dark:text-sky-100'],
                                                ['bg' => 'bg-emerald-500/20', 'ring' => 'ring-emerald-700/20', 'title' => 'text-emerald-700 dark:text-emerald-100'],
                                                ['bg' => 'bg-violet-500/20', 'ring' => 'ring-violet-400/20', 'title' => 'text-violet-700 dark:text-violet-100'],
                                            ];
                                        @endphp

                                        @foreach($heroReasons as $idx => $reason)
                                            @php $c = $reasonColorClasses[$idx % count($reasonColorClasses)]; @endphp
                                            <div class="different-reason rounded-2xl bg-slate-50 p-4 shadow-sm shadow-slate-200/60 ring-1 ring-slate-200/70 dark:bg-slate-950/40 dark:shadow-none dark:ring-white/10">
                                                <div class="flex items-start gap-3">
                                                    <div class="mt-0.5 rounded-xl {{ $c['bg'] }} p-2 ring-1 {{ $c['ring'] }}">
                                                        <div class="text-xs font-semibold {{ $c['title'] }}">{{ $reason['title'] }}</div>
                                                        @if(($reason['subtitle'] ?? '') !== '')
                                                            <div class="mt-1 text-sm text-slate-600 dark:text-slate-200">{{ $reason['subtitle'] }}</div>
                                                        @endif
                                                    </div>
                                                    <div class="text-sm text-slate-600 dark:text-slate-200">{{ $reason['desc'] }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div id="contact" class="mt-6 rounded-2xl bg-slate-50 p-4 shadow-sm shadow-slate-200/60 ring-1 ring-slate-200/70 dark:bg-white/10 dark:shadow-none dark:ring-white/10">
                                <div class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('frontend.hero_need_details_title') }}</div>
                                <div class="mt-1 text-sm text-slate-600 dark:text-slate-200">{{ __('frontend.hero_need_details_subtitle') }}</div>
                                <div class="mt-4 flex flex-col gap-2 sm:flex-row">
                                    <a href="mailto:info@example.com"
                                       class="inline-flex flex-1 items-center justify-center rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-950 transition hover:bg-slate-100">
                                        {{ __('frontend.hero_need_details_email_us') }}
                                    </a>
                                    <a href="#faq"
                                       class="inline-flex flex-1 items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white ring-1 ring-slate-900/10 transition hover:bg-slate-800 dark:bg-white/10 dark:ring-white/10 dark:hover:bg-white/15">
                                        {{ __('frontend.hero_need_details_read_faq') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- About -->
        <section id="about" class="border-t border-slate-200/70 dark:border-white/10">
            <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
                <div class="reveal">
                    <h2 class="text-2xl font-semibold text-slate-900 dark:text-white sm:text-3xl">{{ optional($cmsSectionsByKey->get('home_about_title'))->title ?: __('frontend.home_about_title') }}</h2>
                    <p class="mt-2 max-w-3xl text-slate-600 dark:text-slate-200">{!! optional($cmsSectionsByKey->get('home_about_subtitle'))->content ?: __('frontend.home_about_subtitle') !!}</p>
                </div>

                <div class="reveal mt-10 grid gap-6 md:grid-cols-3">
                    <div class="rounded-3xl bg-white p-6 shadow-sm shadow-slate-200/60 ring-1 ring-slate-200/70 dark:bg-white/5 dark:shadow-none dark:ring-white/10">
                        <div class="text-sm font-semibold text-slate-900 dark:text-white">{{ optional($cmsSectionsByKey->get('home_about_card_1'))->title ?: __('frontend.home_about_card_1_title') }}</div>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-200">{!! optional($cmsSectionsByKey->get('home_about_card_1'))->content ?: __('frontend.home_about_card_1_desc') !!}</p>
                    </div>
                    <div class="rounded-3xl bg-white p-6 shadow-sm shadow-slate-200/60 ring-1 ring-slate-200/70 dark:bg-white/5 dark:shadow-none dark:ring-white/10">
                        <div class="text-sm font-semibold text-slate-900 dark:text-white">{{ optional($cmsSectionsByKey->get('home_about_card_2'))->title ?: __('frontend.home_about_card_2_title') }}</div>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-200">{!! optional($cmsSectionsByKey->get('home_about_card_2'))->content ?: __('frontend.home_about_card_2_desc') !!}</p>
                    </div>
                    <div class="rounded-3xl bg-white p-6 shadow-sm shadow-slate-200/60 ring-1 ring-slate-200/70 dark:bg-white/5 dark:shadow-none dark:ring-white/10">
                        <div class="text-sm font-semibold text-slate-900 dark:text-white">{{ optional($cmsSectionsByKey->get('home_about_card_3'))->title ?: __('frontend.home_about_card_3_title') }}</div>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-200">{!! optional($cmsSectionsByKey->get('home_about_card_3'))->content ?: __('frontend.home_about_card_3_desc') !!}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Courses -->
        <section id="courses" class="border-t border-slate-200/70 dark:border-white/10">
            <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
                <div class="reveal flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-white sm:text-3xl">{{ optional($cmsSectionsByKey->get('home_skill_tracks_title'))->title ?: __('frontend.home_skill_tracks_title') }}</h2>
                        @php
                            $skillTracksSubtitleValue = optional($cmsSectionsByKey->get('home_skill_tracks_subtitle'))->content
                                ?: __('frontend.home_skill_tracks_subtitle');
                            $skillTracksSubtitleIsHtml = is_string($skillTracksSubtitleValue)
                                && preg_match('/<[^>]+>/', $skillTracksSubtitleValue);
                        @endphp
                        <div class="mt-2 max-w-2xl text-slate-600 dark:text-slate-200 [&_p]:m-0 [&_p]:text-slate-600 dark:[&_p]:text-slate-200">
                            @if($skillTracksSubtitleIsHtml)
                                {!! $skillTracksSubtitleValue !!}
                            @else
                                {{ $skillTracksSubtitleValue }}
                            @endif
                        </div>
                    </div>
                    @php $skillTracksCta = $cmsSectionsByKey->get('home_skill_tracks_cta'); @endphp
                    <a href="{{ optional($skillTracksCta)->button_link ?: '#outcomes' }}" class="text-sm font-medium text-sky-700 hover:text-sky-800 dark:text-sky-200 dark:hover:text-sky-100">
                        {{ optional($skillTracksCta)->button_text ?: 'How we help you get hired →' }}
                    </a>
                </div>

                @php
                    $defaultSkillTracks = [
                        [
                            'icon' => 'fa-solid fa-code',
                            'title' => 'Web Development',
                            'desc' => 'Front-end + back-end fundamentals with real projects.',
                            'bullets' => [
                                'HTML, CSS, TailwindCSS, JavaScript',
                                'Responsive UI + animations + components',
                                'APIs, database basics, deployment basics',
                            ],
                        ],
                        [
                            'icon' => 'fa-solid fa-magnifying-glass',
                            'title' => 'SEO (Search Engine Optimization)',
                            'desc' => 'Technical SEO + content + analytics.',
                            'bullets' => [
                                'On-page, off-page, technical SEO',
                                'Keyword research + content planning',
                                'Analytics basics + reporting',
                            ],
                        ],
                        [
                            'icon' => 'fa-brands fa-microsoft',
                            'title' => '.NET Development',
                            'desc' => 'C# + ASP.NET Core for modern applications.',
                            'bullets' => [
                                'C# fundamentals + OOP',
                                'ASP.NET Core APIs + auth basics',
                                'Database + EF Core basics',
                            ],
                        ],
                        [
                            'icon' => 'fa-solid fa-palette',
                            'title' => 'Graphics Design',
                            'desc' => 'Branding + marketing visuals + portfolio.',
                            'bullets' => [
                                'Photoshop / Illustrator fundamentals',
                                'Branding, typography, layouts',
                                'Portfolio + client workflow',
                            ],
                        ],
                        [
                            'icon' => 'fa-solid fa-star',
                            'title' => 'Extra Topics (Optional)',
                            'desc' => 'UI/UX, Git, communication and teamwork.',
                            'bullets' => [
                                'UI/UX basics (Figma)',
                                'Git basics + teamwork',
                                'Client communication',
                            ],
                        ],
                    ];

                    $defaultSkillTrackIcons = [
                        1 => 'fa-solid fa-code',
                        2 => 'fa-solid fa-magnifying-glass',
                        3 => 'fa-brands fa-microsoft',
                        4 => 'fa-solid fa-palette',
                        5 => 'fa-solid fa-star',
                    ];

                    $skillTrackSections = $cmsSectionsByKey
                        ->filter(fn ($section, $key) => is_string($key) && str_starts_with($key, 'home_skill_track_'))
                        ->sortBy(function ($section, $key) {
                            if (is_string($key) && preg_match('/^home_skill_track_(\d+)$/', $key, $m)) {
                                return (int) $m[1];
                            }

                            return 9999;
                        });

                    $skillTracks = [];
                    foreach ($skillTrackSections as $section) {
                        $title = trim((string) optional($section)->title);
                        $raw = trim((string) optional($section)->content);

                        if ($title === '' && $raw === '') {
                            continue;
                        }

                        $rawIsHtml = $raw !== '' && preg_match('/<[^>]+>/', $raw);

                        $desc = '';
                        $bullets = [];
                        if ($raw !== '' && !$rawIsHtml) {
                            $lines = preg_split("/\r\n|\r|\n/", $raw) ?: [];
                            $lines = array_values(array_filter(array_map('trim', $lines), fn ($v) => $v !== ''));

                            if (count($lines) > 0) {
                                $desc = array_shift($lines);
                            }

                            foreach ($lines as $line) {
                                $line = preg_replace('/^\s*[•\-]\s*/u', '', $line);
                                $line = trim((string) $line);
                                if ($line !== '') {
                                    $bullets[] = $line;
                                }
                            }
                        }

                        $skillTracks[] = [
                            'icon' => is_string(optional($section)->icon) ? (string) optional($section)->icon : '',
                            'title' => $title,
                            'desc' => $desc,
                            'bullets' => $bullets,
                            'html' => $rawIsHtml ? $raw : '',
                        ];
                    }

                    if (count($skillTracks) === 0) {
                        $skillTracks = $defaultSkillTracks;
                    }
                @endphp

                <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                    @foreach($skillTracks as $track)
                        <article class="reveal rounded-3xl bg-white p-6 text-center shadow-sm shadow-slate-200/60 ring-1 ring-slate-200/70 transition hover:bg-slate-50 dark:bg-white/5 dark:shadow-none dark:ring-white/10 dark:hover:bg-white/7">
                            @php
                                $iconValue = trim((string) ($track['icon'] ?? ''));
                                if ($iconValue === '') {
                                    $iconValue = (string) ($defaultSkillTrackIcons[$loop->iteration] ?? 'fa-solid fa-star');
                                }

                                $legacyToFa = [
                                    'code' => 'fa-solid fa-code',
                                    'search' => 'fa-solid fa-magnifying-glass',
                                    'dotnet' => 'fa-brands fa-microsoft',
                                    'design' => 'fa-solid fa-palette',
                                    'sparkles' => 'fa-solid fa-star',
                                    'rocket' => 'fa-solid fa-rocket',
                                    'chart' => 'fa-solid fa-chart-line',
                                    'shield' => 'fa-solid fa-shield-halved',
                                ];

                                if (array_key_exists($iconValue, $legacyToFa)) {
                                    $iconValue = $legacyToFa[$iconValue];
                                }

                                if (! preg_match('/^fa-(solid|regular|brands)\s+fa-[a-z0-9-]+$/', $iconValue)) {
                                    $iconValue = 'fa-solid fa-star';
                                }
                            @endphp

                            <div class="mx-auto mb-4 grid h-14 w-14 place-items-center rounded-2xl bg-gradient-to-br from-sky-50 to-indigo-50 text-sky-700 shadow-sm ring-1 ring-sky-100/80 dark:from-sky-500/15 dark:to-indigo-500/10 dark:text-sky-200 dark:ring-white/10" aria-hidden="true">
                                <i class="{{ $iconValue }} text-2xl"></i>
                            </div>

                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ $track['title'] }}</h3>
                            @if(!empty($track['html']))
                                <div class="mt-2 text-sm text-slate-600 dark:text-slate-200 [&_p]:m-0 [&_p]:text-slate-600 dark:[&_p]:text-slate-200 [&_ul]:mt-3 [&_ul]:list-disc [&_ul]:list-inside [&_ul]:pl-0 [&_ul]:space-y-2 [&_li]:text-slate-600 dark:[&_li]:text-slate-200">
                                    {!! $track['html'] !!}
                                </div>
                            @elseif(($track['desc'] ?? '') !== '')
                                <p class="mt-1 text-sm text-slate-600 dark:text-slate-200">{{ $track['desc'] }}</p>
                            @endif
                            @if(!empty($track['bullets']))
                                <ul class="mt-5 space-y-2 text-sm text-slate-600 dark:text-slate-200">
                                    @foreach($track['bullets'] as $b)
                                        <li>• {{ $b }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Mentors -->
        <section id="mentors" class="border-t border-slate-200/70 dark:border-white/10">
            <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
                <div class="reveal">
                    <h2 class="text-2xl font-semibold text-slate-900 dark:text-white sm:text-3xl">{{ __('frontend.home_mentors_title') }}</h2>
                    <p class="mt-2 max-w-2xl text-slate-600 dark:text-slate-200">{{ __('frontend.home_mentors_subtitle') }}</p>
                </div>

                <div class="reveal mt-10">
                    <div class="relative">
                        <div class="mb-3 flex items-center justify-end gap-2">
                            <button id="mentorPrev" type="button" aria-label="Previous mentors" class="inline-flex items-center justify-center rounded-xl bg-white px-3 py-2 text-slate-900 shadow-sm shadow-slate-200/60 ring-1 ring-slate-200/70 backdrop-blur transition hover:bg-slate-50 dark:bg-slate-950/70 dark:text-white dark:shadow-none dark:ring-white/10 dark:hover:bg-slate-950/90">
                                <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5">
                                    <path d="M15 6l-6 6 6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                            <button id="mentorNext" type="button" aria-label="Next mentors" class="inline-flex items-center justify-center rounded-xl bg-white px-3 py-2 text-slate-900 shadow-sm shadow-slate-200/60 ring-1 ring-slate-200/70 backdrop-blur transition hover:bg-slate-50 dark:bg-slate-950/70 dark:text-white dark:shadow-none dark:ring-white/10 dark:hover:bg-slate-950/90">
                                <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5">
                                    <path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>

                        <div id="mentorCarousel" class="mentor-carousel flex gap-6 overflow-x-auto scroll-smooth pb-2">
                            @php
                                /** @var \Illuminate\Support\Collection<int, \Modules\Mentors\Models\Mentor> $mentorItems */
                                $mentorItems = $mentors ?? collect();
                            @endphp

                            @if ($mentorItems->isEmpty())
                                <div class="rounded-3xl bg-white p-6 shadow-sm shadow-slate-200/60 ring-1 ring-slate-200/70 text-slate-600 dark:bg-white/5 dark:shadow-none dark:ring-white/10 dark:text-slate-200">No mentors available yet.</div>
                            @else
                                @foreach ($mentorItems as $mentor)
                                    <div class="mentor-card shrink-0 basis-full overflow-hidden rounded-3xl bg-white shadow-sm shadow-slate-200/60 ring-1 ring-slate-200/70 dark:bg-white/5 dark:shadow-none dark:ring-white/10 sm:basis-[calc(50%-0.75rem)] lg:basis-[calc(25%-1.125rem)]">
                                        @php
                                            $mentorImageUrl = optional($mentor->user)->profile_image_url;
                                        @endphp
                                        <div class="aspect-square w-full overflow-hidden bg-slate-100 grid place-items-center dark:bg-slate-950/30">
                                            @if (is_string($mentorImageUrl) && $mentorImageUrl !== '')
                                                <img
                                                    src="{{ $mentorImageUrl }}"
                                                    alt="{{ $mentor->name }}"
                                                    class="h-full w-full object-cover"
                                                    loading="lazy"
                                                    decoding="async"
                                                />
                                            @else
                                                <svg viewBox="0 0 24 24" fill="none" class="h-24 w-24 text-slate-500 dark:text-slate-200/70 sm:h-28 sm:w-28" aria-hidden="true">
                                                    <path d="M12 12a5 5 0 1 0 0-10 5 5 0 0 0 0 10Z" fill="currentColor" opacity="0.85" />
                                                    <path d="M3.2 21c2.3-4.3 6.2-6.7 8.8-6.7S18.5 16.7 20.8 21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" opacity="0.85" />
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="p-6 text-center">
                                            <div class="text-sm font-semibold text-slate-900 dark:text-white">{{ $mentor->name }}</div>
                                            <div class="mt-1 text-xs text-slate-500 dark:text-slate-300">{{ $mentor->topic ?? 'Mentor' }} • Weekly support</div>

										<a
                                            href="{{ route('mentors.show', $mentor->public_route_key) }}"
										class="mx-auto mt-4 inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white ring-1 ring-slate-900/10 transition hover:bg-slate-800 dark:bg-white/10 dark:ring-white/10 dark:hover:bg-white/15"
										>
											See details
										</a>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- News & Updates -->
        <section id="news" class="border-t border-slate-200/70 dark:border-white/10">
            <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
                <div class="reveal flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-white sm:text-3xl">News &amp; Updates</h2>
                        <p class="mt-2 max-w-2xl text-slate-600 dark:text-slate-200">Latest announcements, updates, and events.</p>
                    </div>
                    <a href="{{ route('news') }}" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white ring-1 ring-slate-900/10 transition hover:bg-slate-800 dark:bg-white/10 dark:ring-white/10 dark:hover:bg-white/15">See all</a>
                </div>

                @php
                    /** @var \Illuminate\Support\Collection<int, \Modules\NewsUpdates\Models\NewsUpdate> $latestNewsItems */
                    $latestNewsItems = $latestNews ?? collect();
                @endphp

                <div class="mt-10 grid gap-6 md:grid-cols-3">
                    @forelse($latestNewsItems as $n)
                        @php
                            $dt = $n->published_at ?: $n->created_at;
                            $excerpt = is_string($n->excerpt) ? trim($n->excerpt) : '';
                        @endphp
                        <article class="reveal rounded-3xl bg-white p-6 ring-1 ring-slate-200/70 shadow-sm shadow-slate-200/60 dark:bg-white/5 dark:ring-white/10 dark:shadow-none">
                            <div class="text-xs text-slate-500 dark:text-slate-300">{{ $dt ? $dt->format('d M Y') : '' }}</div>
                            <h3 class="mt-2 text-base font-semibold text-slate-900 dark:text-white">{{ $n->title }}</h3>
                            <p class="mt-2 text-sm text-slate-600 dark:text-slate-200">{{ $excerpt !== '' ? $excerpt : 'Read the latest update.' }}</p>
                            <a href="{{ route('news.show', $n) }}" class="mt-4 inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white ring-1 ring-slate-900/10 transition hover:bg-slate-800 dark:bg-white/10 dark:ring-white/10 dark:hover:bg-white/15">Read</a>
                        </article>
                    @empty
                        <div class="reveal rounded-3xl bg-white p-6 ring-1 ring-slate-200/70 text-slate-600 dark:bg-white/5 dark:ring-white/10 dark:text-slate-200 md:col-span-3">
                            No news available yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- Reviews -->
        <section id="reviews" class="border-t border-slate-200/70 dark:border-white/10">
            <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
                <div class="reveal">
                    <h2 class="text-2xl font-semibold text-slate-900 dark:text-white sm:text-3xl">{{ __('frontend.home_reviews_title') }}</h2>
                    <p class="mt-2 max-w-2xl text-slate-600 dark:text-slate-200">{{ __('frontend.home_reviews_subtitle') }}</p>
                </div>

                <div class="mt-10 grid gap-6 md:grid-cols-3">
                    @php
                        $reviews = [
                            ['name' => 'Student', 'quote' => 'Mentors were very supportive. The project reviews helped me build a strong portfolio.'],
                            ['name' => 'Freelancer', 'quote' => 'I learned how to communicate with clients and improved my proposals. Great guidance for freelancing.'],
                            ['name' => 'Job Seeker', 'quote' => 'The CV + interview practice sessions were super helpful. I felt confident applying for roles.'],
                        ];
                    @endphp

                    @foreach ($reviews as $r)
                        <div class="reveal rounded-3xl bg-white p-6 shadow-sm shadow-slate-200/60 ring-1 ring-slate-200/70 dark:bg-white/5 dark:shadow-none dark:ring-white/10">
                            <div class="flex items-center gap-1 text-amber-300" aria-label="5 star rating">
                                @for ($i = 0; $i < 5; $i++)
                                    <svg viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 0 0 .95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 0 0-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 0 0-1.176 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 0 0-.364-1.118L2.98 8.71c-.783-.57-.38-1.81.588-1.81H7.03a1 1 0 0 0 .951-.69l1.07-3.292Z"/>
                                    </svg>
                                @endfor
                            </div>
                            <p class="mt-4 text-sm text-slate-600 dark:text-slate-200">“{{ $r['quote'] }}”</p>
                            <div class="mt-4 text-xs font-semibold text-slate-900 dark:text-white">— {{ $r['name'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Outcomes -->
        <section id="outcomes" class="border-t border-slate-200/70 dark:border-white/10">
            <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
                <div class="reveal">
                    <h2 class="text-2xl font-semibold text-slate-900 dark:text-white sm:text-3xl">{{ __('frontend.home_outcomes_title') }}</h2>
                    <p class="mt-2 max-w-2xl text-slate-600 dark:text-slate-200">{{ __('frontend.home_outcomes_subtitle') }}</p>
                </div>

                <div class="reveal mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-3xl bg-white p-6 text-center shadow-sm shadow-slate-200/60 ring-1 ring-slate-200/70 dark:bg-white/5 dark:shadow-none dark:ring-white/10">
                        <div class="text-sm text-slate-500 dark:text-slate-300">Projects</div>
                        <div class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white" data-counter="6">0</div>
                        <div class="mt-2 text-sm text-slate-600 dark:text-slate-200">Portfolio-ready work</div>
                    </div>
                    <div class="rounded-3xl bg-white p-6 text-center shadow-sm shadow-slate-200/60 ring-1 ring-slate-200/70 dark:bg-white/5 dark:shadow-none dark:ring-white/10">
                        <div class="text-sm text-slate-500 dark:text-slate-300">Career sessions</div>
                        <div class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white" data-counter="12">0</div>
                        <div class="mt-2 text-sm text-slate-600 dark:text-slate-200">CV + interview practice</div>
                    </div>
                    <div class="rounded-3xl bg-white p-6 text-center shadow-sm shadow-slate-200/60 ring-1 ring-slate-200/70 dark:bg-white/5 dark:shadow-none dark:ring-white/10">
                        <div class="text-sm text-slate-500 dark:text-slate-300">Freelancing track</div>
                        <div class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white" data-counter="1">0</div>
                        <div class="mt-2 text-sm text-slate-600 dark:text-slate-200">Client-ready guidance</div>
                    </div>
                    <div class="rounded-3xl bg-white p-6 text-center shadow-sm shadow-slate-200/60 ring-1 ring-slate-200/70 dark:bg-white/5 dark:shadow-none dark:ring-white/10">
                        <div class="text-sm text-slate-500 dark:text-slate-300">Support</div>
                        <div class="mt-2 text-3xl font-semibold text-slate-900 dark:text-white" data-counter="24">0</div>
                        <div class="mt-2 text-sm text-slate-600 dark:text-slate-200">Community + mentor help</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- News -->
        <section id="news" class="border-t border-slate-200/70 dark:border-white/10">
            <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
                <div class="reveal flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-white sm:text-3xl">{{ __('frontend.home_news_title') }}</h2>
                        <p class="mt-2 max-w-2xl text-slate-600 dark:text-slate-200">{{ __('frontend.home_news_subtitle') }}</p>
                    </div>
                    <a href="/contact" class="text-sm font-medium text-sky-700 hover:text-sky-800 dark:text-sky-200 dark:hover:text-sky-100">Get schedule & fees →</a>
                </div>

                <div class="mt-10 grid gap-6 md:grid-cols-3">
                    <article class="reveal rounded-3xl bg-white p-6 shadow-sm shadow-slate-200/60 ring-1 ring-slate-200/70 dark:bg-white/5 dark:shadow-none dark:ring-white/10">
                        <div class="text-xs text-slate-500 dark:text-slate-300">Batch Update</div>
                        <h3 class="mt-2 text-base font-semibold text-slate-900 dark:text-white">New batch enrollment open</h3>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-200">Limited seats with mentor-led support and weekly reviews.</p>
                    </article>
                    <article class="reveal rounded-3xl bg-white p-6 shadow-sm shadow-slate-200/60 ring-1 ring-slate-200/70 dark:bg-white/5 dark:shadow-none dark:ring-white/10">
                        <div class="text-xs text-slate-500 dark:text-slate-300">Workshop</div>
                        <h3 class="mt-2 text-base font-semibold text-slate-900 dark:text-white">Portfolio & LinkedIn session</h3>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-200">Improve your profile and showcase projects professionally.</p>
                    </article>
                    <article class="reveal rounded-3xl bg-white p-6 shadow-sm shadow-slate-200/60 ring-1 ring-slate-200/70 dark:bg-white/5 dark:shadow-none dark:ring-white/10">
                        <div class="text-xs text-slate-500 dark:text-slate-300">Freelancing</div>
                        <h3 class="mt-2 text-base font-semibold text-slate-900 dark:text-white">Client communication Q&A</h3>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-200">Learn proposals, scope, and how to handle real clients.</p>
                    </article>
                </div>
            </div>
        </section>

        <!-- FAQ / CTA -->
        <section id="faq" class="border-t border-slate-200/70 dark:border-white/10">
            <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
                <div class="reveal">
                    <h2 class="text-2xl font-semibold text-slate-900 dark:text-white sm:text-3xl">{{ __('frontend.faq_title') }}</h2>
                    <p class="mt-2 max-w-2xl text-slate-600 dark:text-slate-200">{{ __('frontend.faq_subtitle') }}</p>
                </div>

                <div class="mt-10 grid gap-6 lg:grid-cols-2">
                    <div class="reveal rounded-3xl bg-white p-6 shadow-sm shadow-slate-200/60 ring-1 ring-slate-200/70 dark:bg-white/5 dark:shadow-none dark:ring-white/10">
                        <h3 class="text-base font-semibold text-slate-900 dark:text-white">{{ __('frontend.faq_q1_title') }}</h3>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-200">{{ __('frontend.faq_q1_answer') }}</p>
                    </div>
                    <div class="reveal rounded-3xl bg-white p-6 shadow-sm shadow-slate-200/60 ring-1 ring-slate-200/70 dark:bg-white/5 dark:shadow-none dark:ring-white/10">
                        <h3 class="text-base font-semibold text-slate-900 dark:text-white">{{ __('frontend.faq_q2_title') }}</h3>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-200">{{ __('frontend.faq_q2_answer') }}</p>
                    </div>
                </div>

                <div class="reveal mt-10 rounded-3xl bg-gradient-to-r from-indigo-500/20 via-sky-500/15 to-emerald-500/20 p-8 shadow-sm shadow-slate-200/60 ring-1 ring-slate-200/70 dark:shadow-none dark:ring-white/10">
                    <div class="flex flex-col items-start gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('frontend.faq_cta_title') }}</h3>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-200">{{ __('frontend.faq_cta_subtitle') }}</p>
                        </div>
                        <div class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row">
                            <a href="/courses" class="inline-flex items-center justify-center rounded-2xl bg-white px-6 py-3 text-sm font-semibold text-slate-950 transition hover:bg-slate-100">{{ __('frontend.explore_courses') }}</a>
                            @if (Route::has('register'))
                                <a href="/register" data-auth-trigger="register" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white ring-1 ring-slate-900/10 transition hover:bg-slate-800 dark:bg-white/10 dark:ring-white/10 dark:hover:bg-white/15">{{ __('frontend.enroll_now') }}</a>
                            @else
                                <a href="/contact" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white ring-1 ring-slate-900/10 transition hover:bg-slate-800 dark:bg-white/10 dark:ring-white/10 dark:hover:bg-white/15">{{ __('frontend.contact') }}</a>
                            @endif
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
            var counterEls = Array.prototype.slice.call(document.querySelectorAll('[data-counter]'));

            function animateCount(el) {
                var target = parseInt(el.getAttribute('data-counter'), 10);
                if (!Number.isFinite(target) || el.__counted) return;
                el.__counted = true;

                var duration = 900;
                var start = 0;
                var startTime = null;

                function step(ts) {
                    if (!startTime) startTime = ts;
                    var p = Math.min(1, (ts - startTime) / duration);
                    var eased = 1 - Math.pow(1 - p, 3);
                    var value = Math.round(start + (target - start) * eased);
                    el.textContent = value;
                    if (p < 1) requestAnimationFrame(step);
                }

                requestAnimationFrame(step);
            }

            if (!('IntersectionObserver' in window)) {
                revealEls.forEach(function (el) { el.classList.add('is-visible'); });
                counterEls.forEach(animateCount);
                return;
            }

            var observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        if (entry.target.hasAttribute('data-counter')) animateCount(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -10% 0px' });

            revealEls.forEach(function (el) { observer.observe(el); });
            counterEls.forEach(function (el) { observer.observe(el); });

            // Mentors carousel
            var carousel = document.getElementById('mentorCarousel');
            if (carousel) {
                var prevBtn = document.getElementById('mentorPrev');
                var nextBtn = document.getElementById('mentorNext');
                var reduceMotion = false;

                try {
                    reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                } catch (e) {
                    reduceMotion = false;
                }

                function behavior() {
                    return reduceMotion ? 'auto' : 'smooth';
                }

                function maxScrollLeft() {
                    return Math.max(0, carousel.scrollWidth - carousel.clientWidth);
                }

                function step() {
                    return Math.max(240, carousel.clientWidth);
                }

                function goNext() {
                    var maxLeft = maxScrollLeft();
                    if (carousel.scrollLeft >= maxLeft - 2) {
                        carousel.scrollTo({ left: 0, behavior: behavior() });
                        return;
                    }
                    carousel.scrollBy({ left: step(), behavior: behavior() });
                }

                function goPrev() {
                    var maxLeft = maxScrollLeft();
                    if (carousel.scrollLeft <= 2) {
                        carousel.scrollTo({ left: maxLeft, behavior: behavior() });
                        return;
                    }
                    carousel.scrollBy({ left: -step(), behavior: behavior() });
                }

                if (prevBtn) prevBtn.addEventListener('click', goPrev);
                if (nextBtn) nextBtn.addEventListener('click', goNext);

                var timer = null;
                function stop() {
                    if (timer) window.clearInterval(timer);
                    timer = null;
                }

                function start() {
                    stop();
                    if (reduceMotion) return;
                    timer = window.setInterval(goNext, 3200);
                }

                carousel.addEventListener('mouseenter', stop);
                carousel.addEventListener('mouseleave', start);
                carousel.addEventListener('focusin', stop);
                carousel.addEventListener('focusout', start);
                carousel.addEventListener('touchstart', stop, { passive: true });
                carousel.addEventListener('touchend', start, { passive: true });

                start();
            }

            // What makes us different (vertical ticker)
            var reasonsViewport = document.getElementById('differentReasonsViewport');
            var reasonsTrack = document.getElementById('differentReasonsTrack');
            if (reasonsViewport && reasonsTrack) {
                var reduceReasonsMotion = false;

                try {
                    reduceReasonsMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                } catch (e) {
                    reduceReasonsMotion = false;
                }

                var forceReasonsMotion = reasonsViewport.getAttribute('data-force-motion') === '1';
                if (forceReasonsMotion) reduceReasonsMotion = false;

                var visibleCount = 3;
                var durationMs = 650;
                var intervalMs = 2600;

                var originalItems = Array.prototype.slice.call(reasonsTrack.querySelectorAll('.different-reason'));
                var originalCount = originalItems.length;

                function setStaticViewportHeight() {
                    var itemsNow = reasonsTrack.querySelectorAll('.different-reason');
                    if (!itemsNow || itemsNow.length < 2) return;

                    var r1 = itemsNow[0].getBoundingClientRect();
                    var r2 = itemsNow[1].getBoundingClientRect();
                    var step = Math.round(r2.top - r1.top);
                    if (!step || step <= 0) step = Math.round(r1.height + 16);

                    var viewportHeight = Math.round(r1.height + step * (visibleCount - 1));
                    reasonsViewport.style.height = viewportHeight + 'px';
                }

                if (originalCount > visibleCount) {
                    setStaticViewportHeight();

                    if (reduceReasonsMotion) {
                        var staticResizeTimer = null;
                        window.addEventListener('resize', function () {
                            if (staticResizeTimer) window.clearTimeout(staticResizeTimer);
                            staticResizeTimer = window.setTimeout(setStaticViewportHeight, 120);
                        });
                    }
                }

                if (!reduceReasonsMotion && originalCount > visibleCount) {
                    // Clone first N items for a seamless loop.
                    var cloneCount = Math.min(visibleCount, originalCount);
                    for (var i = 0; i < cloneCount; i++) {
                        var clone = originalItems[i].cloneNode(true);
                        clone.setAttribute('data-clone', '1');
                        reasonsTrack.appendChild(clone);
                    }

                    reasonsTrack.style.transitionProperty = 'transform';
                    reasonsTrack.style.transitionTimingFunction = 'cubic-bezier(0.4, 0, 0.2, 1)';
                    reasonsTrack.style.transitionDuration = durationMs + 'ms';

                    var stepPx = 0;
                    var firstHeight = 0;
                    var index = 0;
                    var timer2 = null;
                    var resetting = false;

                    function measure() {
                        var itemsNow = reasonsTrack.querySelectorAll('.different-reason');
                        if (!itemsNow || itemsNow.length < 2) return;

                        var r1 = itemsNow[0].getBoundingClientRect();
                        var r2 = itemsNow[1].getBoundingClientRect();

                        firstHeight = r1.height;
                        stepPx = Math.round(r2.top - r1.top);

                        if (!stepPx || stepPx <= 0) {
                            // Fallback: item height + 16px (gap-4)
                            stepPx = Math.round(r1.height + 16);
                        }

                        // Show exactly 3 items; the 4th stays hidden until scroll.
                        var viewportHeight = Math.round(firstHeight + stepPx * (visibleCount - 1));
                        reasonsViewport.style.height = viewportHeight + 'px';

                        // Keep current position after resize.
                        reasonsTrack.style.transform = 'translateY(' + (-index * stepPx) + 'px)';
                    }

                    function stopReasons() {
                        if (timer2) window.clearInterval(timer2);
                        timer2 = null;
                    }

                    function resetIfNeeded() {
                        if (index !== originalCount) return;
                        resetting = true;
                        // Jump back to the start after reaching the clones.
                        reasonsTrack.style.transitionProperty = 'none';
                        reasonsTrack.style.transform = 'translateY(0px)';
                        // Force reflow so the browser applies the transform before re-enabling transition.
                        void reasonsTrack.offsetHeight;
                        reasonsTrack.style.transitionProperty = 'transform';
                        index = 0;
                        resetting = false;
                    }

                    function tick() {
                        if (!stepPx || resetting) return;
                        index += 1;
                        reasonsTrack.style.transform = 'translateY(' + (-index * stepPx) + 'px)';

                        if (index === originalCount) {
                            window.setTimeout(resetIfNeeded, durationMs + 60);
                        }
                    }

                    function startReasons() {
                        stopReasons();
                        timer2 = window.setInterval(tick, intervalMs);
                    }

                    reasonsViewport.addEventListener('mouseenter', stopReasons);
                    reasonsViewport.addEventListener('mouseleave', startReasons);
                    reasonsViewport.addEventListener('focusin', stopReasons);
                    reasonsViewport.addEventListener('focusout', startReasons);

                    measure();

                    var resizeTimer = null;
                    window.addEventListener('resize', function () {
                        if (resizeTimer) window.clearTimeout(resizeTimer);
                        resizeTimer = window.setTimeout(measure, 120);
                    });

                    startReasons();
                }
            }
        })();
    </script>
@endpush
