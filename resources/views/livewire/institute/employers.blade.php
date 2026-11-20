@php
    $visible = match ($filter) {
        'pending' => $employers->where('status', 'pending'),
        'approved' => $employers->where('status', 'approved'),
        default => $employers,
    };
    $selected = $selectedId ? $employers->firstWhere('id', $selectedId) : null;
@endphp

<div class="max-w-7xl mx-auto space-y-6">
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <span class="font-mono text-xs text-muted uppercase tracking-wider">Institutional Onboarding</span>
                <h1 class="text-2xl font-semibold tracking-tight mt-1 text-ink">Employer KYB Organizations</h1>
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach (['all' => 'ALL', 'pending' => 'PENDING KYB', 'approved' => 'APPROVED'] as $key => $label)
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
                            <th class="py-3.5 px-5">Organization &amp; KYB ID</th>
                            <th class="py-3.5 px-5">KYB Status</th>
                            <th class="py-3.5 px-5">Active Listings</th>
                            <th class="py-3.5 px-5">Status</th>
                            <th class="py-3.5 px-5 text-right">Governance</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-hairline text-sm">
                        @foreach ($visible as $employer)
                            <tr class="hover:bg-subtle/60 transition-colors">
                                <td class="py-4 px-5">
                                    <p class="font-semibold text-ink">{{ $employer['name'] }}</p>
                                    <p class="font-mono text-[11px] text-muted mt-0.5">{{ $employer['id'] }}</p>
                                </td>
                                <td class="py-4 px-5 font-mono text-xs text-muted">{{ $employer['kyb'] }}</td>
                                <td class="py-4 px-5 font-mono text-xs">{{ $employer['jobs'] }}</td>
                                <td class="py-4 px-5">
                                    @if ($employer['status'] === 'approved')
                                        <x-ui.badge tone="subtle">APPROVED</x-ui.badge>
                                    @else
                                        <x-ui.badge tone="ink">PENDING</x-ui.badge>
                                    @endif
                                </td>
                                <td class="py-4 px-5 text-right">
                                    <button
                                        type="button"
                                        wire:click="openDrawer('{{ $employer['id'] }}')"
                                        class="font-mono text-xs underline underline-offset-4 hover:opacity-75"
                                    >
                                        Inspect KYB →
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-ui.card>
    </div>

    <x-portal.drawer wire:model="drawerOpen" title="Employer KYB Inspection">
        @if ($selected)
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    @if ($selected['status'] === 'approved')
                        <x-ui.badge tone="subtle">APPROVED & VERIFIED</x-ui.badge>
                    @else
                        <x-ui.badge tone="ink">PENDING KYB</x-ui.badge>
                    @endif
                </div>
                <div>
                    <p class="font-mono text-xs text-muted uppercase tracking-wider">REG: {{ $selected['id'] }}</p>
                    <h3 class="text-xl font-semibold tracking-tight text-ink mt-1">{{ $selected['name'] }}</h3>
                </div>
                <div class="p-5 bg-subtle border border-hairline rounded-xl space-y-3 text-xs">
                    <div class="flex items-center justify-between gap-2">
                        <span class="font-mono text-muted">KYB REVIEW</span>
                        <span class="font-mono font-medium text-ink">{{ $selected['kyb'] }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <span class="font-mono text-muted">CAMPUS LISTINGS</span>
                        <span class="font-mono font-medium text-ink">{{ $selected['jobs'] }} roles</span>
                    </div>
                </div>
                <p class="text-sm text-muted leading-relaxed">
                    Verify corporate registration, sponsorship license, and faculty targeting before granting campus posting rights.
                </p>
            </div>
        @else
            <p class="text-sm text-muted">Select an organization to inspect KYB documentation.</p>
        @endif
        <x-slot name="footer">
            <div class="flex flex-col sm:flex-row gap-3">
                <button
                    type="button"
                    class="flex-1 py-3 px-4 bg-paper border border-hairline rounded-xl text-xs font-mono text-ink hover:border-ink"
                    @click="window.dispatchEvent(new CustomEvent('portal-toast', { detail: { message: 'Employer flagged for compliance review.' } }))"
                >
                    FLAG / REJECT
                </button>
                <button
                    type="button"
                    class="flex-1 py-3 px-4 bg-ink text-paper rounded-xl text-xs font-mono hover:bg-neutral-800"
                    @click="window.dispatchEvent(new CustomEvent('portal-toast', { detail: { message: '✓ Employer KYB approved for campus network.' } }))"
                >
                    APPROVE KYB →
                </button>
            </div>
        </x-slot>
    </x-portal.drawer>
</div>