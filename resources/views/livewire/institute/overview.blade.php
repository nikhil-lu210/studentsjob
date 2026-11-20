@php
    $pendingEmployers = $employers->where('status', 'pending');
    $pendingJobs = $jobs->take(2);
@endphp

<div class="max-w-7xl mx-auto space-y-6">
    <div class="space-y-8">
        <x-ui.card class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="space-y-1.5">
                <div class="inline-flex items-center gap-2 font-mono text-[11px] text-muted">
                    <span class="w-1.5 h-1.5 rounded-full bg-ink"></span>
                    <span>POLYTECHNIC INSTITUTE // CAREER OFFICE &amp; LEGAL COMPLIANCE GOVERNANCE</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight text-ink">
                    Institutional Verification Control
                </h1>
                <p class="text-xs sm:text-sm text-muted max-w-2xl leading-relaxed">
                    Approve employer KYB credentials, vet job postings for cohort alignment, and enforce statutory student work-right rules before any CV reaches an employer.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <button
                    type="button"
                    class="px-4 py-2.5 text-xs font-mono bg-paper border border-hairline rounded-xl shadow-soft hover:border-ink transition-all"
                    @click="window.dispatchEvent(new CustomEvent('portal-toast', { detail: { message: '↻ Registrar SIS sync queued for Polytechnic Institute.' } }))"
                >
                    ↻ SYNC REGISTRAR SIS
                </button>
                <a
                    href="{{ route('institute.employers', ['filter' => 'pending']) }}"
                    wire:navigate
                    class="px-4 py-2.5 text-xs font-mono bg-ink text-paper rounded-xl shadow-soft hover:bg-neutral-800 transition-all"
                >
                    REVIEW PENDING QUEUE →
                </a>
            </div>
        </x-ui.card>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            <x-ui.stat label="Pending KYB Reviews" :value="str_pad((string) $pendingEmployers->count(), 2, '0', STR_PAD_LEFT)" hint="Employers awaiting campus approval" />
            <x-ui.stat label="Jobs Awaiting Vetting" :value="str_pad((string) $pendingJobs->count(), 2, '0', STR_PAD_LEFT)" hint="Checked for cohort & legal alignment" />
            <x-ui.stat label="Verified Students & Alumni" value="4,820" hint="Active work-right attestations issued" />
            <x-ui.stat label="Ineligible Apps Blocked" value="143" hint="Prevented unauthorized work submissions" />
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
            <div class="xl:col-span-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold tracking-tight text-ink">Employer KYB Queue</h2>
                        <p class="text-xs text-muted">Verify business registration &amp; sponsorship licenses</p>
                    </div>
                    <a href="{{ route('institute.employers') }}" wire:navigate class="font-mono text-xs underline underline-offset-4 hover:opacity-75">
                        All employers →
                    </a>
                </div>
                <div class="space-y-3">
                    @forelse ($pendingEmployers as $employer)
                        <x-ui.card padding class="flex items-start justify-between gap-4 hover:shadow-card-hover transition-shadow">
                            <div>
                                <p class="font-mono text-[10px] text-muted uppercase">{{ $employer['id'] }}</p>
                                <p class="text-sm font-semibold text-ink mt-0.5">{{ $employer['name'] }}</p>
                                <p class="text-xs text-muted mt-1">{{ $employer['kyb'] }} · {{ $employer['jobs'] }} open roles</p>
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                <x-ui.badge tone="ink">PENDING KYB</x-ui.badge>
                                <button type="button" wire:click="openDrawer('{{ $employer['id'] }}')" class="font-mono text-[11px] underline underline-offset-4 hover:opacity-75">
                                    Inspect →
                                </button>
                            </div>
                        </x-ui.card>
                    @empty
                        <x-ui.card>
                            <p class="text-sm text-muted">No employers awaiting KYB review.</p>
                        </x-ui.card>
                    @endforelse
                </div>
            </div>

            <div class="xl:col-span-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold tracking-tight text-ink">Job Vetting Queue</h2>
                        <p class="text-xs text-muted">Approve roles before visibility to students &amp; alumni</p>
                    </div>
                    <a href="{{ route('institute.jobs') }}" wire:navigate class="font-mono text-xs underline underline-offset-4 hover:opacity-75">
                        All roles →
                    </a>
                </div>
                <div class="space-y-3">
                    @foreach ($pendingJobs as $job)
                        <x-ui.card padding class="flex items-start justify-between gap-4">
                            <div>
                                <p class="font-mono text-[10px] text-muted uppercase">{{ $job['id'] }}</p>
                                <p class="text-sm font-semibold text-ink mt-0.5">{{ $job['title'] }}</p>
                                <p class="text-xs text-muted mt-1">{{ $job['company'] }} · {{ $job['type'] }} · {{ $job['hours'] }}</p>
                            </div>
                            <x-ui.badge tone="subtle">AWAITING VET</x-ui.badge>
                        </x-ui.card>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>