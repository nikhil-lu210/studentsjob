@props([
    'label' => 'Work-Right Seal',
    'status' => 'ACTIVE',
    'detail' => null,
])

<div {{ $attributes->class('p-3.5 rounded-xl bg-subtle border border-hairline shadow-soft space-y-2') }}>
    <div class="flex items-center justify-between gap-2">
        <span class="font-mono text-[10px] uppercase tracking-wider text-muted">{{ $label }}</span>
        <x-ui.badge tone="ink">
            <span class="w-1.5 h-1.5 rounded-full bg-paper"></span>
            {{ $status }}
        </x-ui.badge>
    </div>
    @if ($detail)
        <p class="text-xs font-semibold text-ink truncate">{{ $detail }}</p>
    @endif
    {{ $slot }}
</div>
