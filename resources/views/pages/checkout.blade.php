@extends('layouts.site')

@section('title', __('frontend.checkout') . ' • ' . config('app.name', 'iTechBD Ltd'))

@section('content')
<main>
    <section class="border-b border-slate-200/70 dark:border-white/10">
        <div class="mx-auto max-w-5xl px-4 py-12 sm:px-6 lg:px-8">
            <a href="/courses/{{ $course->getRouteKey() }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900 dark:text-slate-200 dark:hover:text-white">← {{ __('frontend.view_details') }}</a>

            <div class="mt-4 grid grid-cols-1 gap-8 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    <h1 class="text-3xl font-semibold text-slate-900 dark:text-white sm:text-4xl">{{ __('frontend.checkout') }}</h1>
                    <p class="mt-3 text-slate-600 dark:text-slate-200">{{ __('frontend.checkout_subtitle') }}</p>

                    @php
                        $hasOnlineOfflinePricing = !is_null($course->online_old_price)
                            || !is_null($course->online_discount_price)
                            || !is_null($course->offline_old_price)
                            || !is_null($course->offline_discount_price);

                        $onlineAmount = (float) ($course->online_discount_price ?? $course->online_old_price ?? $amount);
                        $offlineAmount = (float) ($course->offline_discount_price ?? $course->offline_old_price ?? $amount);

                        $onlineOldAmount = !is_null($course->online_discount_price) ? (float) $course->online_old_price : null;
                        $offlineOldAmount = !is_null($course->offline_discount_price) ? (float) $course->offline_old_price : null;
                    @endphp

                    <div class="mt-8 rounded-3xl bg-white p-6 ring-1 ring-slate-200/70 shadow-sm shadow-slate-200/60 dark:bg-white/5 dark:ring-white/10 dark:shadow-none sm:p-8"
                         @if($hasOnlineOfflinePricing)
                         x-data="{
                             batchType: '{{ old('batch_type', 'online') }}',
                             onlineAmount: {{ $onlineAmount }},
                             offlineAmount: {{ $offlineAmount }},
                             onlineOldAmount: {{ is_null($onlineOldAmount) ? 'null' : $onlineOldAmount }},
                             offlineOldAmount: {{ is_null($offlineOldAmount) ? 'null' : $offlineOldAmount }},
                             get displayAmount() {
                                 return this.batchType === 'offline' ? this.offlineAmount : this.onlineAmount;
                             },
                             get displayOldAmount() {
                                 return this.batchType === 'offline' ? this.offlineOldAmount : this.onlineOldAmount;
                             },
                             formatNum(n) {
                                 if (n === null || n === undefined) return null;
                                 return Number(n).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                             }
                         }"
                         @endif>
                        <div class="text-sm font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-200">{{ __('frontend.selected_course') }}</div>
                        <div class="mt-2 text-xl font-semibold text-slate-900 dark:text-white">{{ $course->title }}</div>

                        @if($hasOnlineOfflinePricing)
                            <div class="mt-6">
                                <div class="text-sm font-semibold text-slate-700 dark:text-slate-200 mb-3">{{ __('frontend.select_batch_type') }}</div>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="relative flex cursor-pointer flex-col gap-1 rounded-2xl border-2 p-4 transition"
                                           :class="{
                                               'border-sky-500 bg-sky-50 dark:bg-sky-900/30': batchType === 'online',
                                               'border-slate-200 bg-slate-50 dark:border-white/10 dark:bg-white/5': batchType !== 'online'
                                           }">
                                        <input type="radio" name="batch_type" value="online" x-model="batchType" class="sr-only" />
                                        <div class="font-semibold text-slate-900 dark:text-white">{{ __('frontend.online') }}</div>
                                        <div class="text-sm text-slate-600 dark:text-slate-300">{{ __('frontend.online_class_desc') }}</div>
                                        <div class="mt-2">
                                            <template x-if="onlineOldAmount !== null">
                                                <span class="text-xs text-slate-400 line-through" x-text="formatNum(onlineOldAmount)"></span>
                                            </template>
                                            <span class="text-lg font-bold text-sky-700 dark:text-sky-400" x-text="'৳ ' + formatNum(onlineAmount)"></span>
                                        </div>
                                    </label>

                                    <label class="relative flex cursor-pointer flex-col gap-1 rounded-2xl border-2 p-4 transition"
                                           :class="{
                                               'border-amber-500 bg-amber-50 dark:bg-amber-900/30': batchType === 'offline',
                                               'border-slate-200 bg-slate-50 dark:border-white/10 dark:bg-white/5': batchType !== 'offline'
                                           }">
                                        <input type="radio" name="batch_type" value="offline" x-model="batchType" class="sr-only" />
                                        <div class="font-semibold text-slate-900 dark:text-white">{{ __('frontend.offline') }}</div>
                                        <div class="text-sm text-slate-600 dark:text-slate-300">{{ __('frontend.offline_class_desc') }}</div>
                                        <div class="mt-2">
                                            <template x-if="offlineOldAmount !== null">
                                                <span class="text-xs text-slate-400 line-through" x-text="formatNum(offlineOldAmount)"></span>
                                            </template>
                                            <span class="text-lg font-bold text-amber-700 dark:text-amber-400" x-text="'৳ ' + formatNum(offlineAmount)"></span>
                                        </div>
                                    </label>
                                </div>
                                @error('batch_type')
                                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mt-6">
                                <div class="rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-200/70 dark:bg-white/5 dark:ring-white/10">
                                    <div class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-white/70">{{ __('frontend.checkout_status') }}</div>
                                    <div class="mt-1 text-sm font-semibold text-slate-900 dark:text-white">{{ __('frontend.checkout_pending') }}</div>
                                    <div class="mt-1 text-sm text-slate-600 dark:text-slate-200">{{ __('frontend.checkout_pending_help') }}</div>
                                </div>
                            </div>
                        @else
                            <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div class="rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-200/70 dark:bg-white/5 dark:ring-white/10">
                                    <div class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-white/70">{{ __('frontend.course_fee') }}</div>
                                    <div class="mt-1 text-2xl font-semibold text-slate-900 dark:text-white">{{ number_format((float) $amount, 2) }}</div>
                                </div>

                                <div class="rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-200/70 dark:bg-white/5 dark:ring-white/10">
                                    <div class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-white/70">{{ __('frontend.checkout_status') }}</div>
                                    <div class="mt-1 text-sm font-semibold text-slate-900 dark:text-white">{{ __('frontend.checkout_pending') }}</div>
                                    <div class="mt-1 text-sm text-slate-600 dark:text-slate-200">{{ __('frontend.checkout_pending_help') }}</div>
                                </div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('checkout.store', $course) }}" class="mt-8 space-y-4">
                            @csrf

                            @if($hasOnlineOfflinePricing)
                                <input type="hidden" name="batch_type" :value="batchType">
                            @endif

                            @if($course->relationLoaded('batches') && $course->batches->count())
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-200">Select batch</label>
                                    <select name="batch_id" class="mt-2 w-full rounded-2xl border-slate-300 bg-white text-slate-900 dark:border-white/10 dark:bg-slate-900 dark:text-slate-100 dark:[color-scheme:dark]">
                                        <option value="" class="text-slate-900 dark:text-slate-100">-- Select a batch --</option>
                                        @foreach($course->batches as $batch)
                                            @php($isJoined = isset($joinedBatchIds) && in_array((int) $batch->id, (array) $joinedBatchIds, true))
                                            <option value="{{ $batch->id }}"
                                                    class="text-slate-900 dark:text-slate-100"
                                                    @selected((string) old('batch_id') === (string) $batch->id)
                                                    @disabled($isJoined)>
                                                {{ $batch->name }} — starts {{ optional($batch->start_date)->format('d M, Y') }} ({{ ucfirst($batch->status) }})@if($isJoined) — Already joined @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('batch_id')
                                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            @else
                                {{-- No batch available notice --}}
                                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 dark:border-amber-400/30 dark:bg-amber-400/10">
                                    <div class="flex items-start gap-3">
                                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-amber-600 dark:text-amber-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-semibold text-amber-800 dark:text-amber-300">{{ __('frontend.no_batch_available') }}</p>
                                            <p class="mt-1 text-sm text-amber-700 dark:text-amber-400">{{ __('frontend.no_batch_available_body') }}</p>
                                            <a href="/contact"
                                               class="mt-3 inline-flex items-center gap-1.5 rounded-full bg-amber-600 px-4 py-2 text-xs font-semibold text-white hover:bg-amber-700 dark:bg-amber-500 dark:hover:bg-amber-600">
                                                {{ __('frontend.no_batch_contact_cta') }}
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                                    <path d="M4.5 10h11M10.5 4l5.5 6-5.5 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <button type="submit"
                                    @if(!($course->relationLoaded('batches') && $course->batches->count())) disabled @endif
                                    class="inline-flex w-full items-center justify-center rounded-2xl px-6 py-3 text-sm font-semibold transition
                                        {{ ($course->relationLoaded('batches') && $course->batches->count())
                                            ? 'bg-slate-900 text-white hover:bg-slate-800 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-100'
                                            : 'cursor-not-allowed bg-slate-200 text-slate-400 dark:bg-slate-700 dark:text-slate-500' }}">
                                {{ __('frontend.confirm_order') }}
                            </button>
                        </form>

                        <div class="mt-4 text-xs text-slate-500 dark:text-slate-300">
                            {{ __('frontend.checkout_note') }}
                        </div>
                    </div>
                </div>

                <aside class="lg:col-span-1">
                    <div class="sticky top-6 space-y-6">
                        <div class="rounded-3xl bg-white p-6 ring-1 ring-slate-200/70 shadow-sm shadow-slate-200/60 dark:bg-white/5 dark:ring-white/10 dark:shadow-none">
                            <div class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('frontend.need_details') }}</div>
                            <div class="mt-1 text-sm text-slate-600 dark:text-slate-200">{{ __('frontend.course_details_help') }}</div>
                            <div class="mt-5">
                                <a href="/contact" class="inline-flex w-full items-center justify-center rounded-2xl bg-sky-700 px-6 py-3 text-sm font-semibold text-white hover:bg-sky-800">
                                    {{ __('frontend.contact') }}
                                </a>
                            </div>
                        </div>

                        <div class="rounded-3xl bg-white p-6 ring-1 ring-slate-200/70 shadow-sm shadow-slate-200/60 dark:bg-white/5 dark:ring-white/10 dark:shadow-none">
                            <div class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('frontend.secure_checkout') }}</div>
                            <ul class="mt-3 space-y-2 text-sm text-slate-600 dark:text-slate-200">
                                <li>• {{ __('frontend.secure_checkout_item_1') }}</li>
                                <li>• {{ __('frontend.secure_checkout_item_2') }}</li>
                                <li>• {{ __('frontend.secure_checkout_item_3') }}</li>
                            </ul>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</main>
@endsection
