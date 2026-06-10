<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-900 leading-tight">My Courses</h2>
            <p class="mt-1 text-sm text-slate-500">Courses you are enrolled in.</p>
        </div>
    </x-slot>

    <div class="rounded-xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Course</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Batches</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($courses as $course)
                        <tr class="hover:bg-slate-50/70">
                            <td class="px-4 py-3">
                                <div class="font-semibold text-slate-900">{{ $course->title }}</div>
                                <div class="mt-1 line-clamp-1 text-xs text-slate-500">{{ \Illuminate\Support\Str::limit(strip_tags((string) $course->description), 90) }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-700">{{ $course->batches_count }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="/dashboard/student/courses/{{ $course->getRouteKey() }}" class="rounded-md border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-10 text-center text-sm text-slate-500">No enrolled courses found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-200 px-4 py-3">
            {{ $courses->links() }}
        </div>
    </div>
</x-app-layout>
