@props([
    'href' => null,
    'subtitle' => 'Academic Career Network',
    'size' => 'md',
])

@php
    $href = $href ?? route('home');
    $mark = $size === 'sm' ? 'w-8 h-8' : 'w-9 h-9';
    $title = $size === 'sm' ? 'text-sm' : 'text-base';
@endphp

<a href="{{ $href }}" {{ $attributes->class('flex items-center gap-3 group') }} wire:navigate>
    <div class="{{ $mark }} rounded-lg bg-ink text-paper flex items-center justify-center shadow-soft transition-transform duration-200 group-hover:scale-105">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
            <rect x="3" y="3" width="18" height="18" rx="4" />
            <path d="M8 12.5L10.8 15.3L16.5 9" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </div>
    <div class="flex flex-col">
        <span class="font-semibold tracking-tight {{ $title }} leading-none">studentsjob.work</span>
        <span class="font-mono text-[10px] text-muted tracking-widest uppercase mt-1">{{ $subtitle }}</span>
    </div>
</a>
