@php
    $stageLabel = fn (string $stage) => match ($stage) {
        'new' => 'New Verified CV',
        'shortlisted' => 'Shortlisted',
        'interview' => 'Interview Stage',
        default => ucfirst($stage),
    };

    $pillClass = fn (string $key) => $filter === $key
        ? 'px-3.5 py-2 text-xs font-mono rounded-lg bg-ink text-paper border border-ink shadow-soft'
        : 'px-3.5 py-2 text-xs font-mono rounded-lg bg-paper text-ink border border-hairline shadow-soft hover:border-ink';

    $filtered = $candidates->filter(function (array $candidate) use ($filter) {
        if ($filter === 'all') {
            return true;
        }

        return $candidate['stage'] === $filter;
    });

    $counts = [
        'all' => $candidates->count(),
        'new' => $candidates->where('stage', 'new')->count(),
        'shortlisted' => $candidates->where('stage', 'shortlisted')->count(),
        'interview' => $candidates->where('stage', 'interview')->count(),
    ];

    $selected = $selectedId ? $candidates->firstWhere('id', $selectedId) : null;
@endphp

<div class="max-w-7xl mx-auto space-y-6">
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <span class="font-mono text-xs text-muted uppercase tracking-wider">Registrar-verified talent</span>
                <h1 class="text-2xl font-semibold tracking-tight mt-1 text-ink">Eligible CV inbox</h1>
            </div>

            <div class="flex flex-wrap gap-2">
                <button type="button" wire:click="$set('filter', 'all')" @class([$pillClass('all')])>
                    ALL ({{ $counts['all'] }})
                </button>
                <button type="button" wire:click="$set('filter', 'new')" @class([$pillClass('new')])>
                    NEW ({{ $counts['new'] }})
                </button>
                <button type="button" wire:click="$set('filter', 'shortlisted')" @class([$pillClass('shortlisted')])>
                    SHORTLISTED ({{ $counts['shortlisted'] }})
                </button>
                <button type="button" wire:click="$set('filter', 'interview')" @class([$pillClass('interview')])>
                    INTERVIEWS ({{ $counts['interview'] }})
                </button>
            </div>
        </div>

        <div class="p-4 bg-paper border border-hairline rounded-2xl shadow-soft flex flex-col sm:flex-row gap-3 items-stretch sm:items-center justify-between">
            <div class="relative flex-1 flex items-center">
                <svg class="w-4 h-4 text-muted ml-2 mr-2.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="11" cy="11" r="8" />
                    <path d="M21 21l-4.35-4.35" stroke-linecap="round" />
                </svg>
                <input
                    type="search"
                    placeholder="Search candidates, programs, or work-right seals…"
                    class="w-full text-sm bg-transparent placeholder:text-muted focus:outline-none"
                    disabled
                />
            </div>
            <div class="flex items-center gap-2 font-mono text-[11px] text-ink bg-subtle px-3 py-2 rounded-lg border border-hairline shrink-0">
                <span class="w-1.5 h-1.5 rounded-full bg-ink"></span>
                <span>100% STATUTORY WORK-RIGHT VERIFIED</span>
            </div>
        </div>

        <x-ui.card :padding="false" class="overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-subtle border-b border-hairline font-mono text-[11px] uppercase text-muted">
                            <th class="py-3.5 px-5">Candidate</th>
                            <th class="py-3.5 px-5">Program</th>
                            <th class="py-3.5 px-5">Work-right seal</th>
                            <th class="py-3.5 px-5">Stage</th>
                            <th class="py-3.5 px-5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-hairline text-sm">
                        @forelse ($filtered as $candidate)
                            <tr
                                wire:key="candidate-{{ $candidate['id'] }}"
                                wire:click="openDrawer('{{ $candidate['id'] }}')"
                                class="hover:bg-subtle/70 transition-colors cursor-pointer"
                            >
                                <td class="py-4 px-5">
                                    <p class="font-semibold text-ink text-xs sm:text-sm">{{ $candidate['name'] }}</p>
                                    <p class="text-xs text-muted mt-0.5 font-mono">{{ $candidate['id'] }}</p>
                                </td>
                                <td class="py-4 px-5 text-xs text-ink">{{ $candidate['program'] }}</td>
                                <td class="py-4 px-5">
                                    <x-ui.badge tone="subtle">✓ {{ $candidate['seal'] }}</x-ui.badge>
                                </td>
                                <td class="py-4 px-5">
                                    <x-ui.badge :tone="$candidate['stage'] === 'interview' ? 'ink' : 'subtle'">
                                        {{ strtoupper($stageLabel($candidate['stage'])) }}
                                    </x-ui.badge>
                                </td>
                                <td class="py-4 px-5 text-right">
                                    <button
                                        type="button"
                                        wire:click.stop="openDrawer('{{ $candidate['id'] }}')"
                                        class="font-mono text-[11px] px-3 py-1.5 bg-paper border border-hairline rounded-lg hover:border-ink transition-colors"
                                    >
                                        VIEW CV →
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-10 px-5 text-center font-mono text-xs text-muted">
                                    No verified candidates match this filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-ui.card>
    </div>

    <x-portal.drawer wire:model="drawerOpen" width="max-w-lg">
        @if ($selected)
            <x-slot:header>
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <x-ui.badge tone="ink">{{ strtoupper($stageLabel($selected['stage'])) }}</x-ui.badge>
                        <span class="font-mono text-xs text-muted">{{ $selected['id'] }}</span>
                    </div>
                    <h2 class="text-lg font-semibold text-ink">{{ $selected['name'] }}</h2>
                </div>
            </x-slot:header>

            <div class="space-y-5">
                <p class="font-mono text-xs text-muted uppercase tracking-wider">{{ $selected['program'] }}</p>

                <div class="p-5 bg-subtle border border-hairline rounded-xl shadow-soft space-y-3 text-xs">
                    <div class="flex items-center justify-between gap-2">
                        <span class="font-mono text-muted">LEGAL WORK RIGHT</span>
                        <span class="font-mono font-medium text-ink text-right">{{ $selected['seal'] }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <span class="font-mono text-muted">MATCH SCORE</span>
                        <span class="font-mono font-medium text-ink">{{ $selected['match'] }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <span class="font-mono text-muted">REGISTRAR STATUS</span>
                        <span class="font-mono font-semibold text-ink">✓ AUTHENTICATED</span>
                    </div>
                </div>

                <p class="text-sm text-ink/90 leading-relaxed">
                    Institute-stamped CV available for download. All eligibility checks passed before delivery to your inbox.
                </p>
            </div>

            <x-slot:footer>
                <div class="flex flex-col sm:flex-row gap-3 w-full">
                    <button type="button" wire:click="$set('drawerOpen', false)" class="flex-1 py-3 px-4 bg-paper border border-hairline rounded-xl text-xs font-mono hover:border-ink">
                        SHORTLIST
                    </button>
                    <button type="button" wire:click="$set('drawerOpen', false)" class="flex-1 py-3 px-4 bg-ink text-paper text-xs font-mono rounded-xl hover:bg-neutral-800">
                        INVITE TO INTERVIEW →
                    </button>
                </div>
            </x-slot:footer>
        @else
            <p class="font-mono text-xs text-muted">Select a row to inspect the verified CV.</p>
        @endif
    </x-portal.drawer>
</div>