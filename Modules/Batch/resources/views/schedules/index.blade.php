<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 leading-tight">Class Schedule</h2>
                <p class="mt-1 text-sm text-slate-500">Batch: <span class="font-semibold">{{ $batch->name }}</span></p>
            </div>

            <div class="flex items-center gap-2">
                @can('addClassSchedule')
                    <a href="/dashboard/batches/{{ $batch->getRouteKey() }}/schedules/create" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">Add Class</a>
                @endcan
            </div>
        </div>
    </x-slot>

    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
        <style>
            .dataTables_wrapper .dataTables_length select {
                padding-right: 2.0rem !important;
                padding-left: 0.75rem !important;
                background-position: right 0.5rem center !important;
                background-repeat: no-repeat !important;
            }
        </style>
    @endpush

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-emerald-50 p-4 text-sm text-emerald-800 ring-1 ring-emerald-100">{{ session('success') }}</div>
    @endif

    <div class="mb-4 rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h3 class="text-sm font-semibold text-slate-900">Live class link</h3>
                @if(isset($nextSchedule) && $nextSchedule)
                    <p class="mt-1 text-sm text-slate-500">
                        Next class: {{ $nextSchedule->class_date?->format('d M Y') }} • {{ $nextSchedule->topic }}
                    </p>
                @else
                    <p class="mt-1 text-sm text-slate-500">No class scheduled yet.</p>
                @endif
            </div>

            <div class="flex items-center gap-2">
                @if($batch->live_class_link)
                    <a href="{{ $batch->live_class_link }}"
                       target="_blank"
                       rel="noreferrer"
                       class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                        Join live class
                    </a>
                @else
                    <span class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-600">
                        Live link not set
                    </span>
                @endif

                @can('updateLiveClassLink', $batch)
                    <a href="/dashboard/batches/{{ $batch->getRouteKey() }}/live-link"
                       class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Edit live link
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <div class="rounded-xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="overflow-x-auto p-4">
            <table id="schedules-table" class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">SL</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Topic</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Recording link</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white"></tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
        <script>
            $(function () {
                $('#schedules-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('dashboard.batches.schedules.index', $batch) }}',
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'class_date_display', name: 'class_date' },
                        { data: 'topic', name: 'topic' },
                        { data: 'recording_link', name: 'recording_link', orderable: false, searchable: false },
                        { data: 'actions', name: 'actions', orderable: false, searchable: false },
                    ],
                    order: [[1, 'asc']],
                });
            });
        </script>
    @endpush
</x-app-layout>
