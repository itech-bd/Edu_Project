@extends('layouts.site')

@section('title', 'News • ' . config('app.name', 'iTechBD Ltd'))

@section('content')
<main>
    <section class="border-b border-slate-200/70 dark:border-white/10">
        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            @php
                $hero = $cmsSectionsByKey->get('hero');
            @endphp

            <div class="reveal flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h1 class="text-3xl font-semibold text-slate-900 dark:text-white sm:text-4xl">{{ optional($hero)->title ?: __('frontend.home_news_title') }}</h1>
                    <p class="mt-3 max-w-2xl text-slate-600 dark:text-slate-200">{{ optional($hero)->content ?: __('frontend.home_news_subtitle') }}</p>
                </div>
            </div>

            <div class="reveal mt-10 overflow-hidden rounded-3xl bg-white p-6 ring-1 ring-slate-200/70 shadow-sm shadow-slate-200/60 dark:bg-white/5 dark:ring-white/10 dark:shadow-none">
                <div class="overflow-x-auto">
                    <table id="news-table" class="min-w-full">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Title</th>
                                <th>Excerpt</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@push('scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script>
    (function () {
        if (!window.jQuery || !jQuery.fn || !jQuery.fn.DataTable) {
            return;
        }

        $('#news-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('news.data') }}',
            pageLength: 10,
            order: [[0, 'desc']],
            columns: [
                { data: 'date', name: 'published_at' },
                {
                    data: 'title',
                    name: 'title',
                    render: function (data, type, row) {
                        if (type === 'display') {
                            var url = row.actions || '#';
                            return '<a href="' + url + '" class="font-semibold text-slate-900 hover:underline">' + (data || '') + '</a>';
                        }
                        return data;
                    }
                },
                {
                    data: 'excerpt',
                    name: 'excerpt',
                    orderable: false,
                    render: function (data, type) {
                        if (type !== 'display') return data;
                        var txt = (data || '').toString();
                        if (txt.length > 140) {
                            txt = txt.slice(0, 140) + '…';
                        }
                        return '<span class="text-slate-600">' + txt.replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</span>';
                    }
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    render: function (data, type) {
                        if (type !== 'display') return data;
                        var url = data || '#';
                        return '<a href="' + url + '" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-800">Read</a>';
                    }
                }
            ]
        });
    })();
</script>
@endpush
