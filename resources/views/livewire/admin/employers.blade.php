@php
    $visible = match ($filter) {
        'kyb', 'pending' => $employers->where('status', 'pending'),
        'verified', 'approved' => $employers->where('status', 'approved'),
        default => $employers,
    };
@endphp

<div class="max-w-7xl mx-auto space-y-6">
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <span class="font-mono text-xs text-muted uppercase tracking-wider">Global Corporate Network</span>
                <h1 class="text-2xl font-semibold tracking-tight mt-1 text-ink">Companies &amp; Employer KYB</h1>
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach (['all' => 'ALL', 'kyb' => 'KYB QUEUE', 'verified' => 'VERIFIED'] as $key => $label)
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
                            <th class="py-3.5 px-5">Organization</th>
                            <th class="py-3.5 px-5">KYB Status</th>
                            <th class="py-3.5 px-5">Listings</th>
                            <th class="py-3.5 px-5">Network Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-hairline text-sm">
                        @foreach ($visible as $employer)
                            <tr>
                                <td class="py-4 px-5">
                                    <p class="font-semibold text-ink">{{ $employer['name'] }}</p>
                                    <p class="font-mono text-[11px] text-muted">{{ $employer['id'] }}</p>
                                </td>
                                <td class="py-4 px-5 font-mono text-xs text-muted">{{ $employer['kyb'] }}</td>
                                <td class="py-4 px-5 font-mono text-xs">{{ $employer['jobs'] }}</td>
                                <td class="py-4 px-5">
                                    @if ($employer['status'] === 'approved')
                                        <x-ui.badge tone="ink">APPROVED</x-ui.badge>
                                    @else
                                        <x-ui.badge tone="subtle">PENDING</x-ui.badge>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-ui.card>
    </div>
</div>