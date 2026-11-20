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
                <span class="font-mono text-xs text-muted uppercase tracking-wider">SIS &amp; Statutory Work-Permit Sync</span>
                <h1 class="text-2xl font-semibold tracking-tight mt-1 text-ink">Verified Student &amp; Alumni Registry</h1>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                @foreach (['all' => 'ALL COHORTS', 'term-time' => 'TERM-TIME (≤20H)', 'alumni' => 'POST-STUDY & ALUMNI'] as $key => $label)
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
                            <th class="py-3.5 px-5">Student / Alumni</th>
                            <th class="py-3.5 px-5">Cohort</th>
                            <th class="py-3.5 px-5">Legal Work Authorization</th>
                            <th class="py-3.5 px-5">Attestation Hash</th>
                            <th class="py-3.5 px-5 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-hairline text-sm">
                        @foreach ($visible as $student)
                            <tr class="hover:bg-subtle/60 transition-colors">
                                <td class="py-4 px-5">
                                    <p class="font-semibold text-ink">{{ $student['name'] }}</p>
                                    <p class="font-mono text-[11px] text-muted mt-0.5">{{ $student['id'] }}</p>
                                </td>
                                <td class="py-4 px-5 text-muted text-xs">{{ $student['cohort'] }}</td>
                                <td class="py-4 px-5 font-mono text-xs">{{ $student['hours'] }}</td>
                                <td class="py-4 px-5 font-mono text-[11px] text-muted truncate max-w-[140px]">
                                    SHA-{{ substr(md5($student['id']), 0, 12) }}
                                </td>
                                <td class="py-4 px-5 text-right">
                                    <x-ui.badge tone="ink">ATTESTED</x-ui.badge>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-ui.card>
    </div>
</div>