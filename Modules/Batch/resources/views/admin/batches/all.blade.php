<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 leading-tight">Batches</h2>
                <p class="mt-1 text-sm text-slate-500">All batches across courses.</p>
            </div>

            @can('create', \Modules\Batch\Models\Batch::class)
                <a href="/dashboard/batches/create"
                    class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                    Add New Batch
                </a>
            @endcan
        </div>
    </x-slot>

    @php
        /** @var string $activeStatus */
        $activeStatus = $activeStatus ?? (string) request()->query('status', 'all');
        $tabs = [
            'all' => 'All',
            'upcoming' => 'Upcoming',
            'running' => 'Running',
            'completed' => 'Completed',
        ];
    @endphp

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

    <div class="mb-4">
        <nav class="inline-flex rounded-lg bg-slate-100 p-1 ring-1 ring-slate-200" aria-label="Batch status tabs">
            @foreach ($tabs as $key => $label)
                @php
                    $isActive = $activeStatus === $key;
                    $classes = $isActive
                        ? 'bg-white text-slate-900 shadow-sm'
                        : 'text-slate-600 hover:text-slate-900';
                @endphp
                <a href="{{ route('dashboard.batches.index', ['status' => $key]) }}"
                    class="{{ $classes }} rounded-md px-4 py-2 text-sm font-semibold">
                    {{ $label }}
                </a>
            @endforeach
        </nav>
    </div>

    <div class="rounded-xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="overflow-x-auto p-4">
            <table id="admin-batches-table" class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">SL</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Batch</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Course</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Mentors</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Students</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Classes</th>
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
                $('#admin-batches-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('dashboard.batches.index', ['status' => $activeStatus]) }}',
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'batch_display', name: 'name' },
                        { data: 'course_title', name: 'course_title', orderable: false },
                        { data: 'status', name: 'status' },
                        { data: 'mentors_count', name: 'mentors_count', searchable: false },
                        { data: 'students_count', name: 'students_count', searchable: false },
                        { data: 'class_schedules_count', name: 'class_schedules_count', searchable: false },
                        { data: 'actions', name: 'actions', orderable: false, searchable: false },
                    ],
                    order: [[1, 'asc']],
                });
            });
        </script>
    @endpush
</x-app-layout>
