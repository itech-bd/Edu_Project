<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-slate-900 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <p class="mt-1 text-sm text-slate-500">Welcome back, {{ Auth::user()->name }}.</p>
        </div>
    </x-slot>

    @php
        $user = Auth::user();
        $isAdmin = $user && method_exists($user, 'hasRole') ? $user->hasRole('admin') : false;
        $roleNames = $user && method_exists($user, 'getRoleNames') ? $user->getRoleNames() : collect();
    @endphp

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="text-sm font-medium text-slate-500">Your Roles</div>
            <div class="mt-3 flex flex-wrap gap-2">
                @forelse($roleNames as $role)
                    <span class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-1 text-xs font-semibold text-indigo-700 ring-1 ring-inset ring-indigo-100">{{ $role }}</span>
                @empty
                    <span class="text-sm text-slate-500">No roles assigned</span>
                @endforelse
            </div>
        </div>

        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="text-sm font-medium text-slate-500">Quick Actions</div>
            <div class="mt-4 space-y-2">
                <a href="/profile" class="block rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Update Profile</a>
                @if($isAdmin)
                    <a href="/users" class="block rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Manage Users</a>
                    <a href="/roles" class="block rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Manage Roles</a>
                    <a href="/permissions" class="block rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Manage Permissions</a>
                    <a href="{{ route('dashboard.contact-messages.index') }}" class="block rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Contact Messages</a>
                @endif
            </div>
        </div>

        <div class="rounded-xl bg-gradient-to-br from-indigo-600 to-violet-600 p-6 text-white shadow-sm">
            <div class="text-sm font-medium text-white/80">Status</div>
            <div class="mt-2 text-2xl font-semibold">You're logged in</div>
            <div class="mt-1 text-sm text-white/80">Everything looks good.</div>
        </div>
    </div>
</x-app-layout>
