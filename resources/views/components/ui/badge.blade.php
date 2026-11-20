@props([
    'tone' => 'default',
])

@php
    $classes = match ($tone) {
        'ink' => 'bg-ink text-paper',
        'subtle' => 'bg-subtle text-muted border border-hairline',
        default => 'bg-paper text-ink border border-hairline',
    };
@endphp

<span {{ $attributes->class("inline-flex items-center gap-1.5 font-mono text-[10px] px-2 py-0.5 rounded {$classes}") }}>
    {{ $slot }}
</span>
