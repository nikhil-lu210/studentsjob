@php
    $appFilters = [
        'all' => 'ALL',
        'interview' => 'INTERVIEWS',
        'review' => 'UNDER REVIEW',
    ];
    $filteredApps = $applications->filter(fn (array $app) => $filter === 'all' || ($app['filter'] ?? '') === $filter);
@endphp

<div class="max-w-7xl mx-auto space-y-6">
    <div class="max-w-7xl mx-auto space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <span class="font-mono text-xs text-muted uppercase tracking-wider">Verified Submission Ledger</span>
                <h1 class="text-2xl font-semibold tracking-tight mt-1 text-ink">All Submitted Applications</h1>
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach ($appFilters as $key => $label)
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

        <x-ui.card :padding="false" class="overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-subtle border-b border-hairline font-mono text-[11px] uppercase text-muted">
                            <th class="py-3.5 px-5">Role &amp; Employer</th>
                            <th class="py-3.5 px-5">Application ID</th>
                            <th class="py-3.5 px-5">Last Update</th>
                            <th class="py-3.5 px-5 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-hairline text-sm">
                        @forelse ($filteredApps as $app)
                            <tr class="hover:bg-subtle/60 transition-colors">
                                <td class="py-4 px-5">
                                    <p class="font-semibold text-ink text-xs sm:text-sm">{{ $app['role'] }}</p>
                                    <p class="text-xs text-muted mt-0.5">{{ $app['company'] }}</p>
                                </td>
                                <td class="py-4 px-5 font-mono text-xs text-ink">{{ $app['id'] }}</td>
                                <td class="py-4 px-5 font-mono text-xs text-muted">{{ $app['updated'] }}</td>
                                <td class="py-4 px-5 text-right">
                                    <x-ui.badge :tone="$app['filter'] === 'interview' ? 'ink' : 'subtle'">
                                        {{ strtoupper($app['status']) }}
                                    </x-ui.badge>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-12 px-5 text-center font-mono text-xs text-muted">
                                    No applications match this filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-ui.card>
    </div>
</div>