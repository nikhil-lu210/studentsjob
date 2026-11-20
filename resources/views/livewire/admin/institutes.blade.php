@php
    $visible = match ($filter) {
        'pending' => $institutes->where('status', 'pending'),
        'active' => $institutes->where('status', 'active'),
        default => $institutes,
    };
@endphp

<div class="max-w-7xl mx-auto space-y-6">
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <span class="font-mono text-xs text-muted uppercase tracking-wider">Multi-University Infrastructure</span>
                <h1 class="text-2xl font-semibold tracking-tight mt-1 text-ink">All Accredited Institutes</h1>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                @foreach (['all' => 'ALL', 'active' => 'ACTIVE NODES', 'pending' => 'PENDING'] as $key => $label)
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
                <button type="button" wire:click="openModal" class="px-4 py-2 text-xs font-mono bg-ink text-paper rounded-lg shadow-soft hover:bg-neutral-800 transition-colors">
                    + ONBOARD INSTITUTE
                </button>
            </div>
        </div>

        <x-ui.card :padding="false" class="overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-subtle border-b border-hairline font-mono text-[11px] uppercase text-muted">
                            <th class="py-3.5 px-5">Institute &amp; Node ID</th>
                            <th class="py-3.5 px-5">Verified Students</th>
                            <th class="py-3.5 px-5">Partner Employers</th>
                            <th class="py-3.5 px-5">Node Status</th>
                            <th class="py-3.5 px-5 text-right">Root Control</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-hairline text-sm">
                        @foreach ($visible as $institute)
                            <tr class="hover:bg-subtle/60 transition-colors">
                                <td class="py-4 px-5">
                                    <p class="font-semibold text-ink">{{ $institute['name'] }}</p>
                                    <p class="font-mono text-[11px] text-muted mt-0.5">{{ $institute['id'] }}</p>
                                </td>
                                <td class="py-4 px-5 font-mono text-xs">{{ number_format($institute['students']) }}</td>
                                <td class="py-4 px-5 font-mono text-xs">{{ $institute['employers'] }}</td>
                                <td class="py-4 px-5">
                                    @if ($institute['status'] === 'active')
                                        <x-ui.badge tone="ink">ACTIVE NODE</x-ui.badge>
                                    @else
                                        <x-ui.badge tone="subtle">PENDING ACCREDITATION</x-ui.badge>
                                    @endif
                                </td>
                                <td class="py-4 px-5 text-right">
                                    <button
                                        type="button"
                                        wire:click="openDrawer('{{ $institute['id'] }}')"
                                        class="font-mono text-xs underline underline-offset-4 hover:opacity-75"
                                    >
                                        Manage node →
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-ui.card>
    </div>

    <x-portal.modal wire:model="modalOpen" title="Onboard New University / College Node" maxWidth="max-w-lg">
        <div class="space-y-4">
            <div class="space-y-1.5">
                <label class="block font-mono text-[11px] uppercase text-muted">Accredited Institute Name</label>
                <input type="text" class="w-full px-3.5 py-2.5 text-sm bg-paper border border-hairline rounded-lg shadow-soft focus:border-ink focus:outline-none" placeholder="Imperial Academy of Computing" />
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="block font-mono text-[11px] uppercase text-muted">Academic Email Domain</label>
                    <input type="text" class="w-full px-3.5 py-2.5 text-sm bg-paper border border-hairline rounded-lg shadow-soft focus:border-ink focus:outline-none" placeholder="imperial-computing.edu" />
                </div>
                <div class="space-y-1.5">
                    <label class="block font-mono text-[11px] uppercase text-muted">Jurisdiction / City</label>
                    <input type="text" class="w-full px-3.5 py-2.5 text-sm bg-paper border border-hairline rounded-lg shadow-soft focus:border-ink focus:outline-none" placeholder="London, UK" />
                </div>
            </div>
            <div class="space-y-1.5">
                <label class="block font-mono text-[11px] uppercase text-muted">Career Office Director &amp; Email</label>
                <input type="text" class="w-full px-3.5 py-2.5 text-sm bg-paper border border-hairline rounded-lg shadow-soft focus:border-ink focus:outline-none" placeholder="Prof. Marcus Sterling (careers@imperial-computing.edu)" />
            </div>
        </div>
        <x-slot name="footer">
            <div class="flex items-center justify-end gap-3">
                <button type="button" wire:click="$set('modalOpen', false)" class="px-4 py-2.5 text-xs font-mono bg-paper border border-hairline rounded-xl hover:border-ink">
                    CANCEL
                </button>
                <button
                    type="button"
                    class="px-5 py-2.5 text-xs font-mono bg-ink text-paper rounded-xl shadow-soft hover:bg-neutral-800"
                    @click="window.dispatchEvent(new CustomEvent('portal-toast', { detail: { message: '✓ Institute node provisioned under root authority.' } }))"
                >
                    PROVISION INSTITUTE NODE →
                </button>
            </div>
        </x-slot>
    </x-portal.modal>

    @php $selected = $selectedId ? $institutes->firstWhere('id', $selectedId) : null; @endphp
    <x-portal.drawer wire:model="drawerOpen" title="Institute Node Governance">
        @if ($selected)
            <div class="space-y-6">
                @if ($selected['status'] === 'active')
                    <x-ui.badge tone="ink">ACTIVE NODE</x-ui.badge>
                @else
                    <x-ui.badge tone="subtle">PENDING NODE</x-ui.badge>
                @endif
                <div>
                    <p class="font-mono text-xs text-muted uppercase">{{ $selected['id'] }}</p>
                    <h3 class="text-xl font-semibold text-ink mt-1">{{ $selected['name'] }}</h3>
                </div>
                <div class="p-5 bg-subtle border border-hairline rounded-xl space-y-3 text-xs font-mono">
                    <div class="flex justify-between"><span class="text-muted">STUDENTS</span><span class="text-ink">{{ number_format($selected['students']) }}</span></div>
                    <div class="flex justify-between"><span class="text-muted">EMPLOYERS</span><span class="text-ink">{{ $selected['employers'] }}</span></div>
                </div>
            </div>
        @else
            <p class="text-sm text-muted">Select an institute node to manage.</p>
        @endif
    </x-portal.drawer>
</div>