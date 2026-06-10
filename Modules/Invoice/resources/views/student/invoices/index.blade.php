<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-900 leading-tight">Invoices</h2>
            <p class="mt-1 text-sm text-slate-500">Download or print your invoices.</p>
        </div>
    </x-slot>

    <div class="mb-4 flex flex-wrap items-center gap-2">
        @php
            $tabs = [
                ['label' => 'All', 'value' => null],
                ['label' => 'Paid', 'value' => 'paid'],
                ['label' => 'Pending', 'value' => 'pending'],
                ['label' => 'Cancelled', 'value' => 'cancelled'],
            ];
        @endphp

        @foreach($tabs as $tab)
            @php
                $isActive = ($activeStatus === $tab['value']) || ($activeStatus === null && $tab['value'] === null);
                $href = $tab['value'] ? url('/dashboard/student/invoices?status='.$tab['value']) : url('/dashboard/student/invoices');
            @endphp
            <a href="{{ $href }}"
               class="rounded-lg px-3 py-1.5 text-sm font-semibold ring-1 ring-inset transition {{ $isActive ? 'bg-indigo-600 text-white ring-indigo-600' : 'bg-white text-slate-700 ring-slate-200 hover:bg-slate-50' }}">
                {{ $tab['label'] }}
            </a>
        @endforeach
    </div>

    <div class="rounded-xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Invoice</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Course</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Batch</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Amount</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-50/70">
                            <td class="px-4 py-3">
                                <div class="font-semibold text-slate-900">#INV-{{ $order->id }}</div>
                                <div class="mt-1 text-xs text-slate-500">{{ optional($order->created_at)->format('d M Y') }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-700">
                                {{ $order->course?->title ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-700">
                                {{ $order->batch?->name ?? '—' }}
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $badge = match ($order->status) {
                                        'paid' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
                                        'pending' => 'bg-amber-50 text-amber-700 ring-amber-200',
                                        'cancelled' => 'bg-rose-50 text-rose-700 ring-rose-200',
                                        default => 'bg-slate-50 text-slate-700 ring-slate-200',
                                    };
                                @endphp
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-semibold ring-1 ring-inset {{ $badge }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm font-semibold text-slate-900">
                                {{ $order->currency }} {{ number_format((float) $order->amount, 2) }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ url('/dashboard/student/invoices/'.$order->getRouteKey()) }}" class="rounded-md border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">View</a>
                                    <a href="{{ url('/dashboard/student/invoices/'.$order->getRouteKey().'/download') }}" class="rounded-md border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">Download PDF</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-sm text-slate-500">No invoices found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-200 px-4 py-3">
            {{ $orders->links() }}
        </div>
    </div>
</x-app-layout>
