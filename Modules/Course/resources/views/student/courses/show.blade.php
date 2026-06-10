<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-900 leading-tight">{{ $course->title }}</h2>
            <p class="mt-1 text-sm text-slate-500">Your enrolled batches for this course.</p>
        </div>
    </x-slot>

    <div class="rounded-xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="border-b border-slate-200 px-6 py-4">
            <div class="text-sm font-semibold text-slate-900">Batches</div>
        </div>

        <div class="divide-y divide-slate-200">
            @forelse($course->batches as $batch)
                <div class="px-6 py-4 flex items-center justify-between gap-4">
                    <div>
                        <div class="font-semibold text-slate-900">{{ $batch->name }}</div>
                        <div class="mt-1 text-xs text-slate-500">{{ $batch->status }} • {{ $batch->class_time }}</div>
                    </div>
                    <a href="/dashboard/student/batches/{{ $batch->getRouteKey() }}" class="rounded-md border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">Open batch</a>
                </div>
            @empty
                <div class="px-6 py-8 text-sm text-slate-500">No enrolled batches.</div>
            @endforelse
        </div>
    </div>
</x-app-layout>
