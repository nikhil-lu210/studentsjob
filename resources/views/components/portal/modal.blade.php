@props([
    'title' => null,
    'maxWidth' => 'max-w-lg',
])

<div
    x-data="{ open: @entangle($attributes->wire('model')).live }"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-[80] flex items-center justify-center p-4"
    @keydown.escape.window="open = false"
>
    <div class="absolute inset-0 bg-black/40 backdrop-blur-[1px]" @click="open = false"></div>
    <div
        x-show="open"
        x-transition
        class="relative w-full {{ $maxWidth }} bg-paper border border-hairline rounded-2xl shadow-modal overflow-hidden"
    >
        <div class="px-5 py-4 border-b border-hairline flex items-center justify-between">
            <h2 class="text-sm font-semibold text-ink">{{ $title }}</h2>
            <button type="button" class="p-2 rounded-lg border border-hairline text-muted hover:text-ink" @click="open = false" aria-label="Close modal">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <div class="p-5">
            {{ $slot }}
        </div>
        @isset($footer)
            <div class="px-5 py-4 border-t border-hairline bg-subtle">
                {{ $footer }}
            </div>
        @endisset
    </div>
</div>
