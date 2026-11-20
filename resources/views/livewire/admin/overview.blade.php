@php
    $pendingEmployers = $employers->where('status', 'pending');
    $activeInstitutes = $institutes->where('status', 'active');
@endphp

<div class="max-w-7xl mx-auto space-y-6">
    <div class="space-y-8">
        <x-ui.card class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="space-y-1.5">
                <div class="inline-flex items-center gap-2 font-mono text-[11px] text-muted">
                    <span class="w-1.5 h-1.5 rounded-full bg-ink"></span>
                    <span>STUDENTSJOB.WORK // MASTER PLATFORM INFRASTRUCTURE &amp; GOVERNANCE</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight text-ink">
                    Global Network Command Center
                </h1>
                <p class="text-xs sm:text-sm text-muted max-w-2xl leading-relaxed">
                    Full administrative oversight across all accredited universities, enrolled students, alumni cohorts, corporate employers, and statutory work-eligibility enforcement.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <button type="button" wire:click="openModal" class="px-4 py-2.5 text-xs font-mono bg-paper border border-hairline rounded-xl shadow-soft hover:border-ink transition-all">
                    + ONBOARD INSTITUTE
                </button>
                <a href="{{ route('admin.employers', ['filter' => 'pending']) }}" wire:navigate class="px-4 py-2.5 text-xs font-mono bg-ink text-paper rounded-xl shadow-soft hover:bg-neutral-800 transition-all">
                    AUDIT GLOBAL QUEUES →
                </a>
            </div>
        </x-ui.card>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            <x-ui.stat label="Accredited Institutes" :value="str_pad((string) $institutes->count(), 2, '0', STR_PAD_LEFT)" :hint="$activeInstitutes->count().' active nodes · '.$institutes->where('status', 'pending')->count().' pending review'" />
            <x-ui.stat label="Students & Alumni" value="18,450" hint="Across all partner universities" />
            <x-ui.stat label="Registered Companies" :value="str_pad((string) $employers->count(), 2, '0', STR_PAD_LEFT)" hint="100% legal work-right compliant" />
            <x-ui.stat label="Ineligible CVs Blocked" value="412" hint="Auto-screened across domain" />
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
            <div class="xl:col-span-7 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold tracking-tight text-ink">Institute Network Nodes</h2>
                        <p class="text-xs text-muted">Manage university domains, student cohorts, and career office admins</p>
                    </div>
                    <a href="{{ route('admin.institutes') }}" wire:navigate class="font-mono text-xs underline underline-offset-4 hover:opacity-75">
                        Manage all institutes →
                    </a>
                </div>
                <div class="space-y-3">
                    @foreach ($institutes as $institute)
                        <x-ui.card padding class="flex items-center justify-between gap-4">
                            <div>
                                <p class="font-mono text-[10px] text-muted uppercase">{{ $institute['id'] }}</p>
                                <p class="text-sm font-semibold text-ink mt-0.5">{{ $institute['name'] }}</p>
                                <p class="text-xs text-muted mt-1">{{ $institute['students'] }} students · {{ $institute['employers'] }} employers</p>
                            </div>
                            @if ($institute['status'] === 'active')
                                <x-ui.badge tone="ink">ACTIVE NODE</x-ui.badge>
                            @else
                                <x-ui.badge tone="subtle">PENDING</x-ui.badge>
                            @endif
                        </x-ui.card>
                    @endforeach
                </div>
            </div>

            <div class="xl:col-span-5 space-y-6">
                <x-ui.card :padding="false" class="overflow-hidden">
                    <div class="px-5 py-4 bg-subtle border-b border-hairline flex items-center justify-between">
                        <span class="font-mono text-xs font-semibold uppercase">Global KYB &amp; Vetting Queue</span>
                        <a href="{{ route('admin.employers') }}" wire:navigate class="font-mono text-[11px] text-muted hover:text-ink underline underline-offset-4">
                            Review all
                        </a>
                    </div>
                    <div class="divide-y divide-hairline">
                        @foreach ($pendingEmployers as $employer)
                            <div class="px-5 py-4">
                                <p class="text-sm font-medium text-ink">{{ $employer['name'] }}</p>
                                <p class="font-mono text-[11px] text-muted mt-0.5">{{ $employer['kyb'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </x-ui.card>

                <x-ui.card class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="font-mono text-[11px] uppercase text-muted">Root Signing Authority</span>
                        <x-ui.badge tone="ink">SHA-256</x-ui.badge>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-ink">Master Attestation Ledger Active</h3>
                        <p class="text-xs text-muted mt-1 leading-relaxed">
                            All institute work-right certificates and employer KYB seals are anchored under the root domain authority of <strong class="font-medium text-ink">studentsjob.work</strong>.
                        </p>
                    </div>
                    <div class="p-3 bg-subtle border border-hairline rounded-xl flex items-center justify-between gap-2 font-mono text-[11px]">
                        <span class="truncate text-muted">ROOT: SJW-MASTER-2026-0001</span>
                        <button
                            type="button"
                            class="px-2.5 py-1 bg-paper border border-hairline rounded-lg hover:border-ink text-ink shrink-0"
                            @click="window.dispatchEvent(new CustomEvent('portal-toast', { detail: { message: 'Root signing key copied.' } }))"
                        >
                            COPY KEY
                        </button>
                    </div>
                </x-ui.card>
            </div>
        </div>
    </div>

    <x-portal.modal wire:model="modalOpen" title="Onboard New University / College Node" maxWidth="max-w-lg">
        <form class="space-y-4" @submit.prevent="window.dispatchEvent(new CustomEvent('portal-toast', { detail: { message: 'Institute node provisioning queued.' } }))">
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
        </form>
        <x-slot name="footer">
            <div class="flex items-center justify-end gap-3">
                <button type="button" wire:click="$set('modalOpen', false)" class="px-4 py-2.5 text-xs font-mono bg-paper border border-hairline rounded-xl hover:border-ink">
                    CANCEL
                </button>
                <button type="button" class="px-5 py-2.5 text-xs font-mono bg-ink text-paper rounded-xl shadow-soft hover:bg-neutral-800" @click="window.dispatchEvent(new CustomEvent('portal-toast', { detail: { message: '✓ Institute node provisioned under root authority.' } }))">
                    PROVISION INSTITUTE NODE →
                </button>
            </div>
        </x-slot>
    </x-portal.modal>
</div>