<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-900 leading-tight">My Mentors</h2>
            <p class="mt-1 text-sm text-slate-500">Mentors assigned to your enrolled batches.</p>
        </div>
    </x-slot>

    <div class="rounded-xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="p-4">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                @forelse($mentors as $mentor)
                    <div class="rounded-xl border border-slate-200 bg-white p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="text-base font-semibold text-slate-900">{{ $mentor->name }}</div>
                                @if($mentor->topic)
                                    <div class="mt-1 text-sm text-slate-600">{{ $mentor->topic }}</div>
                                @endif
                            </div>

                            <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-100">
                                Active
                            </span>
                        </div>

                        @if($mentor->bio)
                            <div class="mt-3 text-sm text-slate-700">
                                {{ \Illuminate\Support\Str::limit(strip_tags((string) $mentor->bio), 220) }}
                            </div>
                        @endif

                        @if($mentor->user)
                            <div class="mt-4 rounded-lg bg-slate-50 p-3 ring-1 ring-inset ring-slate-200">
                                <div class="text-xs font-semibold uppercase tracking-wide text-slate-600">Contact</div>
                                <div class="mt-1 text-sm font-semibold text-slate-900">{{ $mentor->user->name }}</div>
                                <div class="text-sm text-slate-600">{{ $mentor->user->email }}</div>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="col-span-full py-10 text-center text-sm text-slate-500">
                        No mentors found for your batches yet.
                    </div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $mentors->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
