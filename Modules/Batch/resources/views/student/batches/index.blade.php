<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-900 leading-tight">My Batches</h2>
            <p class="mt-1 text-sm text-slate-500">Batches you are enrolled in.</p>
        </div>
    </x-slot>

    <div class="rounded-xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Batch</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Course</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Mentors</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Classes</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($batches as $batch)
                        <tr class="hover:bg-slate-50/70">
                            <td class="px-4 py-3">
                                <div class="font-semibold text-slate-900">{{ $batch->name }}</div>
                                <div class="mt-1 text-xs text-slate-500">{{ ucfirst($batch->status) }} • {{ $batch->class_time }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-700">{{ $batch->course?->title }}</td>
                            <td class="px-4 py-3 text-sm text-slate-700">{{ $batch->mentors_count }}</td>
                            <td class="px-4 py-3 text-sm text-slate-700">{{ $batch->class_schedules_count }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="/dashboard/student/batches/{{ $batch->getRouteKey() }}" class="rounded-md border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">Open</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-sm text-slate-500">No enrolled batches.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-200 px-4 py-3">
            {{ $batches->links() }}
        </div>
    </div>
</x-app-layout>
