@php
    $role = auth()->user()->role;
    $navLinkClasses = static fn (bool $active) => $active
        ? 'flex items-center rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white shadow-sm'
        : 'flex items-center rounded-xl px-4 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900';
@endphp

<aside class="w-full border-b border-slate-200 bg-white lg:min-h-screen lg:w-72 lg:border-b-0 lg:border-r">
    <div class="flex h-full flex-col px-5 py-6">
        <div class="mb-8">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-900 text-sm font-bold text-white">
                    PR
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-900">Procurement MVP</p>
                    <p class="text-xs text-slate-500">Factory control dashboard</p>
                </div>
            </a>
        </div>

        <nav class="space-y-2">
            <a href="{{ route('dashboard') }}" class="{{ $navLinkClasses(request()->routeIs('dashboard')) }}">
                Dashboard
            </a>

            <a href="{{ route('purchase-requests.index') }}" class="{{ $navLinkClasses(request()->routeIs('purchase-requests.index') || request()->routeIs('purchase-requests.show')) }}">
                Purchase Requests
            </a>

            @if (in_array($role, ['requester', 'admin']))
                <a href="{{ route('purchase-requests.create') }}" class="{{ $navLinkClasses(request()->routeIs('purchase-requests.create')) }}">
                    New Purchase Request
                </a>
            @endif

            @if (in_array($role, ['purchasing_officer', 'admin']))
                <a href="{{ route('purchase-requests.index') }}" class="{{ $navLinkClasses(false) }}">
                    RFQ Preparation
                </a>
            @endif

            <a href="{{ route('profile.edit') }}" class="{{ $navLinkClasses(request()->routeIs('profile.*')) }}">
                Profile
            </a>
        </nav>

        <div class="mt-8 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
            <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Logged In As</p>
            <p class="mt-2 text-sm font-semibold text-slate-900">{{ auth()->user()->name }}</p>
            <p class="text-sm text-slate-600">{{ auth()->user()->email }}</p>
            <p class="mt-2 text-xs uppercase tracking-[0.18em] text-slate-500">
                {{ str_replace('_', ' ', $role) }}
            </p>
        </div>

        <div class="mt-auto pt-6">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                    Log Out
                </button>
            </form>
        </div>
    </div>
</aside>
