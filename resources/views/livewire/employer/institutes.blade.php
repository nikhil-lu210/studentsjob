@php
    $org = $employers->firstWhere('status', 'approved') ?? $employers->first();
    $showVault = in_array($filter, ['kyb', 'all'], true);
@endphp

<div class="max-w-7xl mx-auto space-y-6">
    <div class="space-y-6">
        <div>
            <span class="font-mono text-xs text-muted uppercase tracking-wider">Institutional trust &amp; compliance</span>
            <h1 class="text-2xl font-semibold tracking-tight mt-1 text-ink">
                @if ($filter === 'kyb')
                    KYB &amp; sponsor vault
                @else
                    Partner academic network
                @endif
            </h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <div @class(['space-y-4', 'lg:col-span-7' => $showVault, 'lg:col-span-12' => ! $showVault])>
                <x-ui.card :padding="false" class="divide-y divide-hairline overflow-hidden">
                    <div class="px-6 py-4 bg-subtle flex items-center justify-between">
                        <span class="font-mono text-xs font-semibold uppercase text-ink">Connected institute career offices</span>
                        <x-ui.badge tone="subtle">{{ $institutes->where('status', 'active')->count() }} APPROVED</x-ui.badge>
                    </div>

                    @foreach ($institutes as $institute)
                        <div wire:key="institute-{{ $institute['id'] }}" class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <h3 class="text-sm font-semibold text-ink">{{ $institute['name'] }}</h3>
                                <p class="text-xs text-muted mt-0.5">
                                    {{ number_format($institute['students']) }} students • {{ $institute['employers'] }} vetted employers
                                </p>
                            </div>
                            <x-ui.badge :tone="$institute['status'] === 'active' ? 'ink' : 'subtle'">
                                {{ $institute['status'] === 'active' ? 'FULL ACCESS' : strtoupper($institute['status']) }}
                            </x-ui.badge>
                        </div>
                    @endforeach
                </x-ui.card>
            </div>

            @if ($showVault)
                <div class="lg:col-span-5">
                    <x-ui.card :padding="false" class="overflow-hidden">
                        <div class="px-6 py-4 bg-ink text-paper flex items-center justify-between">
                            <span class="font-mono text-xs">EMPLOYER KYB &amp; SPONSOR VAULT</span>
                            <span class="font-mono text-[10px] text-neutral-300">VERIFIED</span>
                        </div>

                        <div class="p-6 space-y-4 text-xs">
                            <div class="p-3.5 bg-subtle border border-hairline rounded-xl space-y-1">
                                <span class="font-mono text-[10px] text-muted uppercase">Registered entity</span>
                                <p class="font-semibold text-ink">{{ $org['name'] ?? 'Organization' }}</p>
                                <p class="font-mono text-[11px] text-muted">Status: {{ strtoupper($org['status'] ?? 'pending') }}</p>
                            </div>

                            <div class="p-3.5 bg-subtle border border-hairline rounded-xl space-y-1">
                                <span class="font-mono text-[10px] text-muted uppercase">KYB accreditation</span>
                                <p class="font-semibold text-ink">{{ $org['kyb'] ?? 'In review' }}</p>
                                <p class="text-muted">Active job postings: {{ $org['jobs'] ?? 0 }}</p>
                            </div>

                            <div class="p-3.5 bg-subtle border border-hairline rounded-xl flex items-center justify-between gap-2 font-mono text-[11px]">
                                <span class="truncate text-ink">HASH: {{ $org['id'] ?? 'ORG-000' }}-SJW-2026</span>
                                <button type="button" class="px-2.5 py-1 bg-paper border border-hairline rounded-lg hover:border-ink shrink-0">
                                    COPY
                                </button>
                            </div>

                            <button
                                type="button"
                                wire:click="openDrawer('{{ $org['id'] ?? 'ORG-01' }}')"
                                class="w-full py-2.5 text-xs font-mono border border-hairline rounded-xl hover:border-ink"
                            >
                                VIEW AUDIT TRAIL →
                            </button>
                        </div>
                    </x-ui.card>
                </div>
            @endif
        </div>
    </div>

    <x-portal.drawer wire:model="drawerOpen" title="Compliance audit trail" width="max-w-lg">
        <p class="text-xs text-muted leading-relaxed">
            KYB vault documents and sponsor accreditation history will appear here once document storage is connected.
        </p>
        <div class="mt-4 p-3.5 bg-subtle border border-hairline rounded-xl font-mono text-[11px] text-ink">
            Entity: {{ $org['name'] ?? '—' }} • {{ strtoupper($org['kyb'] ?? 'pending') }}
        </div>
    </x-portal.drawer>
</div>