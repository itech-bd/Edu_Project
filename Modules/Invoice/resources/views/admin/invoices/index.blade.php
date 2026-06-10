<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold text-slate-900 leading-tight">All Invoices</h2>
                <p class="mt-1 text-sm text-slate-500">Financial status only (Pending / Completed)</p>
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

    <div class="py-8">
        <div class="mx-auto max-w-7xl">
            <div class="mb-4 flex flex-wrap items-center gap-2">
                @php
                    $filters = [
                        '' => 'All',
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                    ];
                @endphp

                @foreach ($filters as $value => $label)
                    @php
                        $isActive = ($activeStatus === $value) || ($value === '' && $activeStatus === null);
                        $href = route('dashboard.admin.invoices.index');
                        if ($value !== '') {
                            $href .= '?status=' . $value;
                        }
                    @endphp

                    <a
                        href="{{ $href }}"
                        class="rounded-md px-3 py-2 text-sm font-semibold {{ $isActive ? 'bg-indigo-600 text-white' : 'bg-white text-slate-700 ring-1 ring-inset ring-slate-200 hover:bg-slate-50' }}"
                    >
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-200">
                <div id="admin-invoices-table-error" class="hidden border-b border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-800">
                    Failed to load invoices. Please refresh the page.
                </div>
                <div class="overflow-x-auto">
                    <table id="admin-invoices-table" class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Invoice</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Student</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Course</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Batch</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Status</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600">Total</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Date</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
        <script>
            $(function () {
                const activeStatus = @json($activeStatus);

                $.fn.dataTable.ext.errMode = 'none';

                const table = $('#admin-invoices-table')
                    .on('error.dt', function () {
                        $('#admin-invoices-table-error').removeClass('hidden');
                    })
                    .DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('dashboard.admin.invoices.index') }}',
                        data: function (d) {
                            if (activeStatus) {
                                d.status = activeStatus;
                            }
                        }
                    },
                    columns: [
                        { data: 'invoice', name: 'id' },
                        { data: 'student', name: 'student', orderable: false },
                        { data: 'course', name: 'course', orderable: false },
                        { data: 'batch', name: 'batch', orderable: false },
                        { data: 'status', name: 'status', orderable: false, searchable: false },
                        {
                            data: 'total',
                            name: 'total',
                            orderable: false,
                            searchable: false,
                            className: 'text-right'
                        },
                        { data: 'date', name: 'date', orderable: false, searchable: false },
                        {
                            data: 'actions',
                            name: 'actions',
                            orderable: false,
                            searchable: false,
                            className: 'text-right'
                        },
                    ],
                    order: [[0, 'desc']],
                });
            });
        </script>
    @endpush
</x-app-layout>
