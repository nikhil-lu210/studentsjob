@props([
    'item',
    'active' => null,
])

@php
    $key = $item['key'] ?? '';
    $label = $item['label'] ?? '';
    $route = $item['route'] ?? null;
    $children = $item['children'] ?? [];
    $isActive = $active === $key || collect($children)->contains(fn ($child) => ($child['key'] ?? '') === $active);
    $hasChildren = count($children) > 0;
@endphp

<div x-data="{ open: @js($isActive && $hasChildren) }" class="space-y-1">
    @if ($hasChildren)
        <button
            type="button"
            @click="open = !open"
            @class([
                'nav-main-btn w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all',
                'bg-ink text-paper shadow-soft' => $isActive,
                'text-ink hover:bg-subtle' => ! $isActive,
            ])
        >
            <span class="flex items-center gap-3">
                <span class="w-1.5 h-1.5 rounded-full {{ $isActive ? 'bg-paper' : 'bg-hairline' }}"></span>
                <span>{{ $label }}</span>
            </span>
            <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <div x-show="open" class="ml-4 pl-3 border-l border-hairline space-y-1">
            @foreach ($children as $child)
                @php
                    $childActive = $active === ($child['key'] ?? '');
                    $childRoute = $child['route'] ?? $route;
                    $query = $child['query'] ?? [];
                @endphp
                <a
                    href="{{ $childRoute ? route($childRoute, $query) : '#' }}"
                    wire:navigate
                    @class([
                        'w-full flex items-center justify-between px-3 py-2 rounded-lg text-xs font-medium transition-colors',
                        'text-ink bg-subtle' => $childActive,
                        'text-muted hover:text-ink hover:bg-subtle' => ! $childActive,
                    ])
                >
                    <span>{{ $child['label'] ?? '' }}</span>
                    @if (! empty($child['badge']))
                        <span class="font-mono text-[10px]">{{ $child['badge'] }}</span>
                    @endif
                </a>
            @endforeach
        </div>
    @else
        <a
            href="{{ $route ? route($route) : '#' }}"
            wire:navigate
            @class([
                'nav-main-btn w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all',
                'bg-ink text-paper shadow-soft' => $isActive,
                'text-ink hover:bg-subtle' => ! $isActive,
            ])
        >
            <span class="flex items-center gap-3">
                <span class="w-1.5 h-1.5 rounded-full {{ $isActive ? 'bg-paper' : 'bg-hairline' }}"></span>
                <span>{{ $label }}</span>
            </span>
        </a>
    @endif
</div>
