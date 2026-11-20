@php
    $selected = $selectedId ? $jobs->firstWhere('id', $selectedId) : null;
@endphp

<div class="max-w-7xl mx-auto space-y-6">
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <span class="font-mono text-xs text-muted uppercase tracking-wider">Campus Listing Compliance</span>
                <h1 class="text-2xl font-semibold tracking-tight mt-1 text-ink">Submitted Role Vetting</h1>
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach (['all' => 'ALL', 'pending' => 'PENDING VETTING', 'live' => 'VERIFIED & LIVE'] as $key => $label)
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
                <x-ui.card padding class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <p class="font-mono text-[10px] text-muted uppercase">{{ $job['id'] }}</p>
                            @if ($job['visa'])
                                <x-ui.badge tone="ink">VISA SPONSORSHIP</x-ui.badge>
                            @endif
                            @if (! $job['eligible'])
                                <x-ui.badge tone="subtle">COHORT LOCK</x-ui.badge>
                            @endif
                        </div>
                        <h2 class="text-base font-semibold text-ink">{{ $job['title'] }}</h2>
                        <p class="text-xs text-muted">{{ $job['company'] }} · {{ $job['type'] }} · {{ $job['hours'] }} · {{ $job['location'] }}</p>
                        <p class="text-xs text-muted mt-1 max-w-2xl">{{ $job['summary'] }}</p>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <span class="font-mono text-xs text-muted">{{ $job['salary'] }}</span>
                        <button
                            type="button"
                            wire:click="openDrawer('{{ $job['id'] }}')"
                            class="px-3.5 py-2 text-xs font-mono bg-ink text-paper rounded-lg shadow-soft hover:bg-neutral-800"
                        >
                            VET ROLE →
                        </button>
                    </div>
                </x-ui.card>
            @endforeach
        </div>
    </div>

    <x-portal.drawer wire:model="drawerOpen" title="Job Listing Vetting">
        @if ($selected)
            <div class="space-y-6">
                <x-ui.badge tone="subtle">PENDING INSTITUTE REVIEW</x-ui.badge>
                <div>
                    <p class="font-mono text-xs text-muted uppercase">{{ $selected['id'] }}</p>
                    <h3 class="text-xl font-semibold text-ink mt-1">{{ $selected['title'] }}</h3>
                    <p class="text-sm text-muted mt-1">{{ $selected['company'] }}</p>
                </div>
                <div class="p-5 bg-subtle border border-hairline rounded-xl space-y-2 text-xs font-mono">
                    <div class="flex justify-between"><span class="text-muted">TYPE</span><span class="text-ink">{{ $selected['type'] }}</span></div>
                    <div class="flex justify-between"><span class="text-muted">HOURS</span><span class="text-ink">{{ $selected['hours'] }}</span></div>
                    <div class="flex justify-between"><span class="text-muted">LOCATION</span><span class="text-ink">{{ $selected['location'] }}</span></div>
                    <div class="flex justify-between"><span class="text-muted">COMPENSATION</span><span class="text-ink">{{ $selected['salary'] }}</span></div>
                </div>
                <p class="text-sm text-ink/90 leading-relaxed">{{ $selected['summary'] }}</p>
            </div>
        @else
            <p class="text-sm text-muted">Select a role to review compliance alignment.</p>
        @endif
        <x-slot name="footer">
            <div class="flex flex-col sm:flex-row gap-3">
                <button type="button" class="flex-1 py-3 px-4 border border-hairline rounded-xl text-xs font-mono bg-paper hover:border-ink">
                    REQUEST CHANGES
                </button>
                <button
                    type="button"
                    class="flex-1 py-3 px-4 bg-ink text-paper rounded-xl text-xs font-mono hover:bg-neutral-800"
                    @click="window.dispatchEvent(new CustomEvent('portal-toast', { detail: { message: '✓ Role verified and published to campus network.' } }))"
                >
                    PUBLISH ROLE →
                </button>
            </div>
        </x-slot>
    </x-portal.drawer>
</div>