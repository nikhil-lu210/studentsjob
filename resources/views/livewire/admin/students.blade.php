@php
    $visible = match ($filter) {
        'term-time' => $students->where('filter', 'term-time'),
        'alumni' => $students->where('filter', 'alumni'),
        default => $students,
    };
@endphp

<div class="max-w-7xl mx-auto space-y-6">
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <span class="font-mono text-xs text-muted uppercase tracking-wider">Cross-Institute Identity Graph</span>
                <h1 class="text-2xl font-semibold tracking-tight mt-1 text-ink">Students &amp; Alumni Directory</h1>
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach (['all' => 'ALL VERIFIED', 'term-time' => 'TERM-TIME', 'alumni' => 'ALUMNI / PSW'] as $key => $label)
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

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <x-ui.stat label="Term-Time Enrolled" :value="(string) $students->where('filter', 'term-time')->count()" hint="≤20h/wk guardrail active" />
            <x-ui.stat label="Alumni / PSW" :value="(string) $students->where('filter', 'alumni')->count()" hint="Unrestricted work-right cohort" />
            <x-ui.stat label="Total Network Users" :value="(string) $students->count()" hint="SIS-attested identities" />
        </div>

        <x-ui.card :padding="false" class="overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-subtle border-b border-hairline font-mono text-[11px] uppercase text-muted">
                            <th class="py-3.5 px-5">User</th>
                            <th class="py-3.5 px-5">Cohort</th>
                            <th class="py-3.5 px-5">Work Authorization</th>
                            <th class="py-3.5 px-5 text-right">Seal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-hairline text-sm">
                        @foreach ($visible as $student)
                            <tr class="hover:bg-subtle/60">
                                <td class="py-4 px-5">
                                    <p class="font-semibold text-ink">{{ $student['name'] }}</p>
                                    <p class="font-mono text-[11px] text-muted">{{ $student['id'] }}</p>
                                </td>
                                <td class="py-4 px-5 text-xs text-muted">{{ $student['cohort'] }}</td>
                                <td class="py-4 px-5 font-mono text-xs">{{ $student['hours'] }}</td>
                                <td class="py-4 px-5 text-right">
                                    <x-ui.badge tone="ink">VERIFIED</x-ui.badge>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-ui.card>
    </div>
</div>