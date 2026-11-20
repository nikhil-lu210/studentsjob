@props([
    'portalLabel' => 'Portal',
    'contextTitle' => null,
    'contextMeta' => null,
    'contextVariant' => 'default',
    'nav' => [],
    'active' => null,
])

@php
    $contextCardClass = $contextVariant === 'root'
        ? 'p-3.5 rounded-xl bg-ink text-paper border border-ink shadow-soft space-y-2'
        : 'p-3.5 rounded-xl bg-subtle border border-hairline shadow-soft space-y-2';
@endphp

<aside
    id="sidebar"
    x-bind:class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    aria-label="Primary Sidebar Navigation"
    class="fixed inset-y-0 left-0 z-50 w-72 bg-paper border-r border-hairline shadow-card lg:shadow-none flex flex-col justify-between transform transition-transform duration-200 ease-out"
>
    <div class="flex flex-col flex-1 overflow-y-auto">
        <div class="h-16 px-5 border-b border-hairline flex items-center justify-between shrink-0">
            <x-brand :subtitle="$portalLabel" size="sm" class="min-w-0" />
            <button
                type="button"
                @click="sidebarOpen = false"
                aria-label="Close Sidebar"
                class="lg:hidden p-2 rounded-lg border border-hairline text-muted hover:text-ink hover:border-ink transition-colors"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        @if ($contextTitle || $contextMeta)
            <div class="p-4 border-b border-hairline">
                <div class="{{ $contextCardClass }}">
                    <div class="flex items-center justify-between gap-2">
                        <span @class([
                            'font-mono text-[10px] uppercase tracking-wider',
                            'text-white/70' => $contextVariant === 'root',
                            'text-muted' => $contextVariant !== 'root',
                        ])>
                            {{ $contextVariant === 'root' ? 'Master Clearance' : 'Institute Verified' }}
                        </span>
                        <span @class([
                            'inline-flex items-center gap-1.5 font-mono text-[10px] px-2 py-0.5 rounded',
                            'bg-paper text-ink' => $contextVariant === 'root',
                            'bg-ink text-paper' => $contextVariant !== 'root',
                        ])>
                            <span @class([
                                'w-1.5 h-1.5 rounded-full',
                                'bg-ink' => $contextVariant === 'root',
                                'bg-paper' => $contextVariant !== 'root',
                            ])></span>
                            ACTIVE
                        </span>
                    </div>
                    @if ($contextTitle)
                        <p class="text-xs font-semibold truncate">{{ $contextTitle }}</p>
                    @endif
                    @if ($contextMeta)
                        <p @class([
                            'font-mono text-[11px] truncate',
                            'text-white/70' => $contextVariant === 'root',
                            'text-muted' => $contextVariant !== 'root',
                        ])>{{ $contextMeta }}</p>
                    @endif
                </div>
            </div>
        @endif

        <nav class="p-3.5 space-y-1.5 flex-1">
            <div class="px-2.5 pb-1 font-mono text-[10px] uppercase tracking-widest text-muted">
                Navigation
            </div>

            @foreach ($nav as $item)
                <x-portal.nav-item :item="$item" :active="$active" />
            @endforeach
        </nav>
    </div>

    <div class="p-4 border-t border-hairline space-y-3">
        <div class="px-1 font-mono text-[10px] text-muted uppercase tracking-wider">Session</div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium text-ink border border-hairline hover:border-ink transition-all">
                <span>Sign out</span>
                <span class="font-mono text-[10px] text-muted">ESC</span>
            </button>
        </form>
    </div>
</aside>
