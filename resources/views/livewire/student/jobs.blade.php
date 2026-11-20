@php
    $filterOptions = [
        'all' => 'ALL',
        'full-time' => 'FULL-TIME',
        'part-time' => 'PART-TIME (≤20H)',
        'visa' => 'SPONSORSHIP',
        'saved' => 'SAVED',
    ];
    $filteredJobs = $jobs->filter(function (array $job) use ($filter) {
        return match ($filter) {
            'all' => true,
            'saved' => in_array('saved', $job['filter'] ?? [], true),
            default => in_array($filter, $job['filter'] ?? [], true),
        };
    });
    $drawerJob = $selectedId ? $jobs->firstWhere('id', $selectedId) : null;
@endphp

<div class="max-w-7xl mx-auto space-y-6">
    <div class="max-w-7xl mx-auto space-y-6">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <span class="font-mono text-xs text-muted uppercase tracking-wider">Campus-Vetted Feed</span>
                <h1 class="text-2xl font-semibold tracking-tight mt-1 text-ink">All Verified Jobs</h1>
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach ($filterOptions as $key => $label)
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
                        @if ($key === 'all')
                            ({{ str_pad((string) $jobs->count(), 2, '0', STR_PAD_LEFT) }})
                        @endif
                    </button>
                @endforeach
            </div>
        </div>

        <x-ui.card class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center justify-between">
            <p class="text-sm text-muted font-mono text-[11px] flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-ink"></span>
                SHOWING ELIGIBLE ROLES FIRST — LOCKED ROLES REQUIRE SPONSORSHIP OR FULL-TIME RIGHTS
            </p>
        </x-ui.card>

        <div class="space-y-3.5">
            @forelse ($filteredJobs as $job)
                @php
                    $hasApplied = $applications->contains(fn ($app) => $app['role'] === $job['title']);
                    $eligible = (bool) ($job['eligible'] ?? false);
                @endphp
                <article
                    @class([
                        'p-5 sm:p-6 bg-paper border border-hairline rounded-2xl shadow-soft transition-all flex flex-col lg:flex-row lg:items-center justify-between gap-4',
                        'hover:shadow-card hover:border-ink/40 cursor-pointer' => $eligible,
                        'opacity-60 cursor-not-allowed' => ! $eligible,
                    ])
                    @if ($eligible)
                        wire:click="openDrawer('{{ $job['id'] }}')"
                        role="button"
                        tabindex="0"
                    @endif
                >
                    <div class="space-y-2 max-w-2xl">
                        <div class="flex flex-wrap items-center gap-2">
                            <x-ui.badge :tone="$job['visa'] ? 'ink' : 'subtle'">{{ strtoupper($job['type']) }}</x-ui.badge>
                            @if (! $eligible)
                                <x-ui.badge tone="subtle">🔒 LOCKED</x-ui.badge>
                            @endif
                            <span class="font-mono text-[11px] text-muted">{{ $job['institute'] }}</span>
                        </div>
                        <h3 class="text-lg font-semibold text-ink">{{ $job['title'] }}</h3>
                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-muted">
                            <span class="font-medium text-ink">{{ $job['company'] }}</span>
                            <span>•</span>
                            <span>{{ $job['location'] }}</span>
                            <span>•</span>
                            <span class="font-mono text-ink">{{ $job['salary'] }}</span>
                        </div>
                        @if (! $eligible)
                            <p class="text-[11px] font-mono text-muted">Not eligible under current term-time work-right seal.</p>
                        @endif
                    </div>

                    <div class="flex items-center justify-between lg:justify-end gap-3 pt-3 lg:pt-0 border-t lg:border-t-0 border-hairline shrink-0">
                        @if (in_array('saved', $job['filter'] ?? [], true))
                            <span class="px-3 py-2 text-xs font-mono rounded-lg border border-hairline bg-subtle text-ink">★ SAVED</span>
                        @endif
                        @if ($hasApplied)
                            <x-ui.badge tone="subtle">✓ APPLIED</x-ui.badge>
                        @elseif ($eligible)
                            <x-ui.badge tone="ink">VIEW &amp; APPLY →</x-ui.badge>
                        @else
                            <x-ui.badge tone="subtle">INELIGIBLE</x-ui.badge>
                        @endif
                    </div>
                </article>
            @empty
                <x-ui.card class="text-center space-y-3 py-12">
                    <p class="font-mono text-xs text-muted">No verified jobs match your current filter.</p>
                    <button type="button" wire:click="$set('filter', 'all')" class="px-4 py-2 bg-ink text-paper text-xs font-mono rounded-lg">
                        SHOW ALL VERIFIED JOBS
                    </button>
                </x-ui.card>
            @endforelse
        </div>
    </div>

    <x-portal.drawer wire:model="drawerOpen" :title="$drawerJob['title'] ?? 'Job detail'">
        @if ($drawerJob)
            <div class="space-y-6">
                <div class="flex flex-wrap items-center gap-2 font-mono text-xs pb-4 border-b border-hairline">
                    <x-ui.badge :tone="$drawerJob['visa'] ? 'ink' : 'subtle'">{{ strtoupper($drawerJob['type']) }}</x-ui.badge>
                    <span class="text-muted">{{ $drawerJob['id'] }}</span>
                </div>
                <div>
                    <p class="font-mono text-xs text-muted uppercase tracking-wider">{{ $drawerJob['company'] }}</p>
                    <p class="text-sm text-muted mt-2">{{ $drawerJob['location'] }} • {{ $drawerJob['hours'] }}</p>
                </div>
                <x-ui.card class="space-y-3 text-xs font-mono">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-muted">INSTITUTE VETTING</span>
                        <span class="text-ink text-right">{{ $drawerJob['institute'] }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-muted">YOUR ELIGIBILITY</span>
                        <span class="text-ink text-right">✓ 100% LEGALLY ELIGIBLE</span>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-muted">COMPENSATION</span>
                        <span class="text-ink text-right">{{ $drawerJob['salary'] }}</span>
                    </div>
                </x-ui.card>
                <div class="space-y-2">
                    <h3 class="font-mono text-xs uppercase tracking-wider text-muted">Role Overview</h3>
                    <p class="text-sm text-ink/90 leading-relaxed">{{ $drawerJob['summary'] }}</p>
                </div>
                <div class="p-4 bg-subtle border border-hairline rounded-xl space-y-1.5 text-xs font-mono">
                    <p class="text-ink font-semibold">ATTACHED AUTOMATICALLY ON APPLY:</p>
                    <p class="text-muted">1. Verified CV (Career Office stamped)</p>
                    <p class="text-muted">2. Work-Right Attestation (SJW-POLY-2026-8492)</p>
                </div>
            </div>
            <x-slot:footer>
                <div class="flex items-center gap-3">
                    <button type="button" class="px-4 py-3 bg-paper border border-hairline rounded-xl shadow-soft hover:border-ink text-xs font-mono text-ink transition-colors">
                        SAVE ROLE
                    </button>
                    <button type="button" class="flex-1 py-3 px-4 bg-ink text-paper text-sm font-medium rounded-xl shadow-soft hover:bg-neutral-800 transition-colors font-mono text-xs">
                        Submit 1-Click Verified Application →
                    </button>
                </div>
            </x-slot:footer>
        @endif
    </x-portal.drawer>
</div>