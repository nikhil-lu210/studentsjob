@props([
    'title' => null,
    'width' => 'max-w-xl',
])

@php
    $model = $attributes->wire('model');
@endphp

<div
    x-data="{ open: @entangle($model).live }"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-[70]"
    @keydown.escape.window="open = false"
    {{ $attributes->whereDoesntStartWith('wire:model') }}
>
    <div class="absolute inset-0 bg-black/35 backdrop-blur-[1px]" @click="open = false"></div>
    <aside
        x-show="open"
        x-transition:enter="transition transform ease-out duration-200"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition transform ease-in duration-150"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="absolute inset-y-0 right-0 w-full {{ $width }} bg-paper border-l border-hairline shadow-modal flex flex-col"
    >
        <div class="h-16 px-5 border-b border-hairline flex items-center justify-between shrink-0">
            <div>
                @if ($title)
                    <h2 class="text-sm font-semibold text-ink">{{ $title }}</h2>
                @endif
                {{ $header ?? '' }}
            </div>
            <button type="button" class="p-2 rounded-lg border border-hairline text-muted hover:text-ink hover:border-ink" @click="open = false" aria-label="Close drawer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-5">
            {{ $slot }}
        </div>
        @isset($footer)
            <div class="border-t border-hairline p-4 bg-subtle">
                {{ $footer }}
            </div>
        @endisset
    </aside>
</div>
