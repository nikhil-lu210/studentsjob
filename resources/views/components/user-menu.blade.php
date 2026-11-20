@props([
    'compact' => false,
])

@php
    $user = auth()->user();
@endphp

@if ($user)
    <div {{ $attributes->class('relative') }} x-data="{ open: false }" @click.outside="open = false">
        <button
            type="button"
            @click="open = !open"
            class="flex items-center gap-2.5 px-2 py-1.5 rounded-lg border border-hairline bg-paper shadow-soft hover:border-ink transition-all"
            aria-haspopup="menu"
            :aria-expanded="open"
        >
            <div class="w-8 h-8 rounded-lg bg-ink text-paper font-mono text-xs font-semibold flex items-center justify-center shrink-0">
                {{ $user->initials() }}
            </div>
            @unless ($compact)
                <div class="hidden sm:block text-left min-w-0">
                    <span class="block text-xs font-semibold leading-none text-ink truncate max-w-[140px]">{{ $user->name }}</span>
                    <span class="block font-mono text-[10px] text-muted mt-1 truncate max-w-[140px]">{{ $user->email }}</span>
                </div>
                <svg class="hidden sm:block w-3.5 h-3.5 text-muted shrink-0 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            @endunless
        </button>

        <div
            x-show="open"
            x-cloak
            x-transition
            class="absolute right-0 mt-2 w-64 bg-paper border border-hairline rounded-xl shadow-dropdown overflow-hidden z-50"
            role="menu"
        >
            <div class="px-4 py-3 border-b border-hairline">
                <p class="text-sm font-semibold text-ink truncate">{{ $user->name }}</p>
                <p class="font-mono text-[11px] text-muted truncate">{{ $user->email }}</p>
            </div>
            <a href="{{ route('dashboard') }}" class="block px-4 py-3 text-sm text-ink hover:bg-subtle" role="menuitem">
                Go to dashboard
            </a>
            <a href="{{ route('profile.edit') }}" class="block px-4 py-3 text-sm text-ink hover:bg-subtle" role="menuitem">
                Account settings
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-3 text-sm text-ink hover:bg-subtle border-t border-hairline" role="menuitem">
                    Sign out
                </button>
            </form>
        </div>
    </div>
@endif
