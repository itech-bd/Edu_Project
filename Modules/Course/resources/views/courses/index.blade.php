<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 leading-tight">Courses</h2>
                <p class="mt-1 text-sm text-slate-500">Create and manage courses.</p>
            </div>

            @can('addCourse')
                <a href="/dashboard/courses/create" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                    Add Course
                </a>
            @endcan
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

    <div class="rounded-xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="overflow-x-auto p-4">
            <table id="courses-table" class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">SL</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Title</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Fee</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Batches</th>
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
                $('#courses-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('dashboard.courses.index') }}',
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                        { data: 'title', name: 'title' },
                        { data: 'fee', name: 'old_price', orderable: false, searchable: false },
                        { data: 'status_badge', name: 'status', orderable: false, searchable: false },
                        { data: 'batches_count', name: 'batches_count', searchable: false },
                        { data: 'actions', name: 'actions', orderable: false, searchable: false },
                    ],
                    order: [[1, 'asc']],
                });
            });
        </script>
    @endpush
</x-app-layout>
