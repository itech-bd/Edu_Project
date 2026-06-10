@extends('layouts.site')

@section('title', ($mentor->name ?? 'Mentor') . ' • ' . config('app.name', 'iTechBD Ltd'))

@section('content')
<main>
    <section class="border-b border-slate-200/70 dark:border-white/10">
        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            @php
                $mentorUser = $mentor->user;
                $mentorProfile = $mentorUser?->profile;
                $mentorImageUrl = $mentorUser?->profile_image_url;

                $normalizeBio = function (string $value): string {
                    $text = trim($value);
                    if ($text === '') {
                        return '';
                    }

                    // Convert common WYSIWYG HTML into readable plain text with bullets.
                    $text = preg_replace('/<\s*br\s*\/?\s*>/i', "\n", $text) ?? $text;
                    $text = preg_replace('/<\s*\/\s*p\s*>/i', "\n", $text) ?? $text;
                    $text = preg_replace('/\s*<\s*li\b[^>]*>\s*/i', "\n• ", $text) ?? $text;
                    $text = preg_replace('/\s*<\s*\/\s*li\s*>\s*/i', "\n", $text) ?? $text;

                    $text = strip_tags($text);
                    $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

                    // Tidy whitespace/newlines (avoid big gaps between paragraphs/lists).
                    $text = preg_replace("/\n{2,}/", "\n", $text) ?? $text;
                    $text = preg_replace("/[ \t]{2,}/", " ", $text) ?? $text;

                    return trim($text);
                };

                $mentorBioText = $normalizeBio((string) ($mentor->bio ?? ''));
                $profileBioText = $normalizeBio((string) ($mentorProfile?->bio ?? ''));

                $aboutText = $mentorBioText !== ''
                    ? $mentorBioText
                    : $profileBioText;

                $proficiencyLabel = [
                    'beginner' => 'Beginner',
                    'intermediate' => 'Intermediate',
                    'expert' => 'Expert',
                ];
            @endphp

            <div class="reveal flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-semibold text-slate-900 dark:text-white sm:text-4xl">{{ $mentor->name }}</h1>
                    <p class="mt-2 text-slate-600 dark:text-slate-200">{{ $mentor->topic ?? 'Mentor' }}</p>
                </div>
                <div>
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-end">
                        <a href="{{ route('mentors') }}" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white ring-1 ring-slate-900/10 transition hover:bg-slate-800 dark:bg-white/10 dark:ring-white/10 dark:hover:bg-white/15">
                            Back to mentors
                        </a>
                        @if($mentorProfile?->public_url)
                            <a
                                href="{{ route('profile.public.show', ['public_url' => $mentorProfile->public_url]) }}"
                                class="inline-flex items-center justify-center rounded-2xl bg-white px-6 py-3 text-sm font-semibold text-slate-950 ring-1 ring-slate-200/70 transition hover:bg-slate-50 dark:bg-white/10 dark:text-white dark:ring-white/10 dark:hover:bg-white/15"
                                target="_blank"
                                rel="noreferrer"
                            >
                                View CV
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="reveal mt-10 grid gap-8 lg:grid-cols-12">
                <div class="lg:col-span-5">
                    <div class="overflow-hidden rounded-3xl bg-white shadow-sm shadow-slate-200/60 ring-1 ring-slate-200/70 dark:bg-white/5 dark:shadow-none dark:ring-white/10">
                        <div class="flex min-h-[320px] w-full items-center justify-center overflow-hidden bg-slate-100 p-4 dark:bg-slate-950/30 sm:min-h-[420px]">
                            @if (is_string($mentorImageUrl) && $mentorImageUrl !== '')
                                <img
                                    src="{{ $mentorImageUrl }}"
                                    alt="{{ $mentor->name }}"
                                    class="block h-auto max-h-[520px] w-auto max-w-full rounded-2xl object-contain"
                                    loading="eager"
                                    decoding="async"
                                />
                            @else
                                <svg viewBox="0 0 24 24" fill="none" class="h-24 w-24 text-slate-500 dark:text-slate-200/70" aria-hidden="true">
                                    <path d="M12 12a5 5 0 1 0 0-10 5 5 0 0 0 0 10Z" fill="currentColor" opacity="0.85" />
                                    <path d="M3.2 21c2.3-4.3 6.2-6.7 8.8-6.7S18.5 16.7 20.8 21" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" opacity="0.85" />
                                </svg>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-7 space-y-6">
                    <div class="rounded-3xl bg-white p-6 shadow-sm shadow-slate-200/60 ring-1 ring-slate-200/70 dark:bg-white/5 dark:shadow-none dark:ring-white/10">
                        <div class="text-lg font-semibold text-slate-900 dark:text-white">About</div>
                        @if(($aboutText ?? '') !== '')
                            @php
                                $aboutLines = preg_split("/\r\n|\r|\n/", (string) $aboutText) ?: [];
                                $aboutLines = array_values(array_filter(array_map('trim', $aboutLines), fn ($l) => $l !== ''));
                                $isBulletLine = fn (string $line) => str_starts_with($line, '•');
                                $bulletText = fn (string $line) => preg_replace('/^\s*•\s*/u', '', $line) ?? $line;
                                $i = 0;
                            @endphp

                            <div class="mt-4 space-y-2 text-sm leading-6 text-slate-600 dark:text-slate-200">
                                @php
                                    $blocks = [];
                                    $listItems = [];
                                    $currentItem = null;

                                    $flushList = function () use (&$blocks, &$listItems, &$currentItem): void {
                                        if (is_string($currentItem) && trim($currentItem) !== '') {
                                            $listItems[] = trim($currentItem);
                                        }
                                        $currentItem = null;

                                        if (count($listItems) > 0) {
                                            $blocks[] = ['type' => 'ul', 'items' => $listItems];
                                        }
                                        $listItems = [];
                                    };

                                    foreach ($aboutLines as $line) {
                                        if ($isBulletLine($line)) {
                                            if (!empty($listItems) || (is_string($currentItem) && trim($currentItem) !== '')) {
                                                // Continue list; push previous item.
                                                if (is_string($currentItem) && trim($currentItem) !== '') {
                                                    $listItems[] = trim($currentItem);
                                                }
                                                $currentItem = $bulletText($line);
                                            } else {
                                                // Start a new list.
                                                $currentItem = $bulletText($line);
                                            }

                                            continue;
                                        }

                                        if (is_string($currentItem) && trim($currentItem) !== '') {
                                            // This is a continuation line for the current bullet item.
                                            $currentItem = trim($currentItem . ' ' . $line);
                                            continue;
                                        }

                                        // Not a bullet, not in a list: treat as paragraph.
                                        $flushList();
                                        $blocks[] = ['type' => 'p', 'text' => $line];
                                    }

                                    $flushList();
                                @endphp

                                @foreach($blocks as $block)
                                    @if(($block['type'] ?? null) === 'ul')
                                        <ul class="list-disc list-outside pl-5 space-y-1 break-words">
                                            @foreach(($block['items'] ?? []) as $item)
                                                <li>{{ $item }}</li>
                                            @endforeach
                                        </ul>
                                    @elseif(($block['type'] ?? null) === 'p')
                                        <p class="break-words">{{ $block['text'] ?? '' }}</p>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <p class="mt-4 text-sm text-slate-600 dark:text-slate-200">Mentor details will be updated soon.</p>
                        @endif
                    </div>

                    @if($mentorUser && $mentorUser->skills && $mentorUser->skills->count())
                        <div class="rounded-3xl bg-white p-6 shadow-sm shadow-slate-200/60 ring-1 ring-slate-200/70 dark:bg-white/5 dark:shadow-none dark:ring-white/10">
                            <div class="text-lg font-semibold text-slate-900 dark:text-white">Skills</div>
                            <div class="mt-4 flex flex-wrap gap-2">
                                @foreach($mentorUser->skills as $skill)
                                    @php
                                        $level = $skill->pivot?->proficiency_level;
                                        $levelText = $level ? ($proficiencyLabel[$level] ?? $level) : null;
                                    @endphp
                                    <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-sm text-slate-800 ring-1 ring-slate-200/70 dark:bg-white/10 dark:text-white dark:ring-white/10">
                                        <span class="font-medium">{{ $skill->name }}</span>
                                        @if($levelText)
                                            <span class="text-xs text-slate-500 dark:text-white/70">({{ $levelText }})</span>
                                        @endif
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($mentorUser && $mentorUser->experiences && $mentorUser->experiences->count())
                        <div class="rounded-3xl bg-white p-6 shadow-sm shadow-slate-200/60 ring-1 ring-slate-200/70 dark:bg-white/5 dark:shadow-none dark:ring-white/10">
                            <div class="text-lg font-semibold text-slate-900 dark:text-white">Experience</div>
                            <div class="mt-4 space-y-4">
                                @foreach($mentorUser->experiences as $exp)
                                    <div class="rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-200/70 dark:bg-slate-950/40 dark:ring-white/10">
                                        <div class="flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between">
                                            <div>
                                                <div class="font-semibold text-slate-900 dark:text-white">{{ $exp->job_title }}</div>
                                                <div class="text-sm text-slate-600 dark:text-slate-200">{{ $exp->company_name }}</div>
                                            </div>
                                            <div class="text-xs text-slate-500 dark:text-slate-300">
                                                {{ optional($exp->start_date)->format('M Y') }}
                                                —
                                                {{ $exp->end_date ? $exp->end_date->format('M Y') : 'Present' }}
                                            </div>
                                        </div>
                                        @if($exp->description)
                                            <div class="mt-2 text-sm text-slate-600 dark:text-slate-200 whitespace-pre-line">{{ $exp->description }}</div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($mentorUser && $mentorUser->educations && $mentorUser->educations->count())
                        <div class="rounded-3xl bg-white p-6 shadow-sm shadow-slate-200/60 ring-1 ring-slate-200/70 dark:bg-white/5 dark:shadow-none dark:ring-white/10">
                            <div class="text-lg font-semibold text-slate-900 dark:text-white">Education</div>
                            <div class="mt-4 space-y-4">
                                @foreach($mentorUser->educations as $edu)
                                    <div class="rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-200/70 dark:bg-slate-950/40 dark:ring-white/10">
                                        <div class="flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between">
                                            <div>
                                                <div class="font-semibold text-slate-900 dark:text-white">{{ $edu->degree_name }}</div>
                                                <div class="text-sm text-slate-600 dark:text-slate-200">{{ $edu->institute_name }}</div>
                                                @if($edu->board_or_university)
                                                    <div class="text-xs text-slate-500 dark:text-slate-300">{{ $edu->board_or_university }}</div>
                                                @endif
                                            </div>
                                            <div class="text-xs text-slate-500 dark:text-slate-300">
                                                {{ $edu->start_year ?? '—' }} — {{ $edu->end_year ?? '—' }}
                                            </div>
                                        </div>
                                        @if($edu->result_or_grade)
                                            <div class="mt-2 text-sm text-slate-600 dark:text-slate-200">Result: {{ $edu->result_or_grade }}</div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div id="contact" class="rounded-3xl bg-slate-50 p-6 ring-1 ring-slate-200/70 dark:bg-slate-950/40 dark:ring-white/10">
                        <div class="text-sm font-semibold text-slate-900 dark:text-white">Need to talk with this mentor?</div>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-200">Enroll in a course to get weekly mentor support and project reviews.</p>
                        <div class="mt-4">
                            <a href="/courses" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-6 py-3 text-sm font-semibold text-white ring-1 ring-slate-900/10 transition hover:bg-slate-800 dark:bg-white/10 dark:ring-white/10 dark:hover:bg-white/15">Explore courses</a>
                        </div>
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
