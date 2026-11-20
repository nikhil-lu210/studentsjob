<div class="max-w-7xl mx-auto space-y-6">
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <span class="font-mono text-xs text-muted uppercase tracking-wider">Multi-Institute Role Index</span>
                <h1 class="text-2xl font-semibold tracking-tight mt-1 text-ink">Global Job Network</h1>
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach (['all' => 'ALL ROLES', 'live' => 'LIVE', 'pending' => 'PENDING'] as $key => $label)
                    <button
                        type="button"
                        wire:click="$set('filter', '{{ $key }}')"
                        @class([
                            'px-3.5 py-2 text-xs font-mono rounded-lg border shadow-soft transition-colors',
                            'bg-ink text-paper border-ink' => $filter === $key,
                            'bg-paper text-ink border-hairline hover:border-ink' => $filter !== $key,
                        ])
                    >
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="space-y-3.5">
            @foreach ($jobs as $job)
                <x-ui.card padding class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="font-mono text-[10px] text-muted uppercase">{{ $job['id'] }}</span>
                            <x-ui.badge tone="subtle">{{ $job['institute'] }}</x-ui.badge>
                        </div>
                        <h2 class="text-base font-semibold text-ink mt-1">{{ $job['title'] }}</h2>
                        <p class="text-xs text-muted mt-1">{{ $job['company'] }} · {{ $job['type'] }} · {{ $job['location'] }}</p>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <span class="font-mono text-xs text-muted">{{ $job['salary'] }}</span>
                        @if ($job['eligible'])
                            <x-ui.badge tone="ink">LIVE</x-ui.badge>
                        @else
                            <x-ui.badge tone="subtle">ON HOLD</x-ui.badge>
                        @endif
                    </div>
                </x-ui.card>
            @endforeach
        </div>
    </div>
</div>