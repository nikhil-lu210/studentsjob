@props([
    'breadcrumbPrefix' => 'PORTAL',
    'breadcrumbParent' => 'Overview',
    'breadcrumbChild' => 'Dashboard Summary',
    'pill' => null,
    'userName' => null,
    'userEmail' => null,
    'userInitials' => 'U',
])

<header class="sticky top-0 z-30 bg-paper/95 backdrop-blur-md border-b border-hairline shadow-soft">
    <div class="h-16 px-4 sm:px-6 flex items-center justify-between gap-3">
        <div class="flex items-center gap-3 min-w-0">
            <button
                type="button"
                class="lg:hidden p-2.5 rounded-lg border border-hairline bg-paper shadow-soft text-ink"
                @click="sidebarOpen = true"
                aria-label="Open Sidebar"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16" />
                </svg>
            </button>

            <div class="min-w-0">
                <div class="font-mono text-[10px] uppercase tracking-widest text-muted">{{ $breadcrumbPrefix }}</div>
                <div class="flex items-center gap-2 min-w-0">
                    <span class="font-medium text-ink truncate">{{ $breadcrumbParent }}</span>
                    <span class="text-hairline hidden sm:inline">/</span>
                    <span class="font-mono text-xs text-muted hidden md:inline truncate">{{ $breadcrumbChild }}</span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2 sm:gap-3">
            <div class="hidden md:block relative">
                <input
                    type="search"
                    placeholder="Search…"
                    class="w-56 lg:w-72 h-10 rounded-lg border border-hairline bg-paper px-3 text-sm shadow-soft focus:outline-none focus:border-ink"
                />
            </div>

            @if ($pill)
                <span class="hidden sm:inline-flex font-mono text-[10px] px-2.5 py-1 rounded-lg border border-hairline bg-subtle text-ink">
                    {{ $pill }}
                </span>
            @endif

            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                <button type="button" class="relative p-2.5 rounded-lg border border-hairline bg-paper shadow-soft" @click="open = !open" aria-label="Notifications">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5m6 0a3 3 0 11-6 0" />
                    </svg>
                    <span class="absolute top-2 right-2 w-2 h-2 rounded-full bg-ink ring-2 ring-paper"></span>
                </button>
                <div x-show="open" x-cloak class="absolute right-0 mt-2 w-80 bg-paper border border-hairline rounded-xl shadow-dropdown overflow-hidden">
                    <div class="px-4 py-3 border-b border-hairline flex items-center justify-between">
                        <span class="text-sm font-semibold">Notifications</span>
                        <span class="font-mono text-[10px] px-1.5 py-0.5 bg-ink text-paper rounded">2 NEW</span>
                    </div>
                    <div class="divide-y divide-hairline max-h-80 overflow-y-auto text-sm">
                        <div class="px-4 py-3">
                            <p class="font-medium text-ink">Application update</p>
                            <p class="font-mono text-[11px] text-muted mt-1">Interview invite received</p>
                        </div>
                        <div class="px-4 py-3">
                            <p class="font-medium text-ink">Seal status</p>
                            <p class="font-mono text-[11px] text-muted mt-1">Work-right attestation active</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                <button type="button" class="flex items-center gap-2 px-2 py-1.5 rounded-lg border border-hairline bg-paper shadow-soft" @click="open = !open">
                    <div class="w-7 h-7 rounded-lg bg-ink text-paper font-mono text-xs font-semibold flex items-center justify-center">
                        {{ $userInitials }}
                    </div>
                    <div class="hidden sm:block text-left min-w-0">
                        <span class="block text-xs font-semibold leading-none text-ink truncate max-w-[120px]">{{ $userName }}</span>
                    </div>
                </button>
                <div x-show="open" x-cloak class="absolute right-0 mt-2 w-64 bg-paper border border-hairline rounded-xl shadow-dropdown overflow-hidden">
                    <div class="px-4 py-3 border-b border-hairline">
                        <p class="text-sm font-semibold text-ink">{{ $userName }}</p>
                        <p class="font-mono text-[11px] text-muted truncate">{{ $userEmail }}</p>
                    </div>
                    <a href="{{ route('profile.edit') }}" wire:navigate class="block px-4 py-3 text-sm hover:bg-subtle">Account settings</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-3 text-sm hover:bg-subtle border-t border-hairline">Sign out</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
