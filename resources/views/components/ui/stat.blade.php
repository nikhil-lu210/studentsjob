@props([
    'label',
    'value',
    'hint' => null,
])

<div {{ $attributes->class('bg-paper border border-hairline rounded-2xl shadow-soft p-4 sm:p-5') }}>
    <p class="font-mono text-[10px] uppercase tracking-wider text-muted">{{ $label }}</p>
    <p class="mt-2 text-2xl font-semibold tracking-tight text-ink">{{ $value }}</p>
    @if ($hint)
        <p class="mt-1 font-mono text-[11px] text-muted">{{ $hint }}</p>
    @endif
</div>
