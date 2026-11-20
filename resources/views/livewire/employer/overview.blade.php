@php
    $org = $employers->firstWhere('status', 'approved') ?? $employers->first();
    $interviewCount = $candidates->where('stage', 'interview')->count();
    $activeInstitutes = $institutes->where('status', 'active')->count();
    $liveJobs = $jobs->filter(fn (array $job) => $job['eligible'] ?? false);

    $stageLabel = fn (string $stage) => match ($stage) {
        'new' => 'New Verified CV',
        'shortlisted' => 'Shortlisted',
        'interview' => 'Interview Stage',
        default => ucfirst($stage),
    };

    $stageBadgeTone = fn (string $stage) => $stage === 'interview' ? 'ink' : 'subtle';
@endphp

<div class="max-w-7xl mx-auto space-y-6">
    <div class="space-y-8">
        {{-- KYB strip & quick actions --}}
        <x-ui.card class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 font-mono text-[11px] text-muted">
                    <span class="w-1.5 h-1.5 rounded-full bg-ink"></span>
                    <span>EMPLOYER KYB &amp; SPONSORSHIP: {{ strtoupper($org['kyb'] ?? 'VERIFIED') }}</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight text-ink">
                    {{ $org['name'] ?? 'Your Organization' }}
                </h1>
                <p class="text-xs sm:text-sm text-muted max-w-2xl leading-relaxed">
                    Every CV in your inbox is institute-verified for degree status and statutory work eligibility—compliance friction removed upstream.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                @if (Route::has('employer.candidates'))
                    <a
                        href="{{ route('employer.candidates') }}"
                        wire:navigate
                        class="px-4 py-2.5 text-xs font-mono bg-paper border border-hairline rounded-xl shadow-soft hover:border-ink transition-all"
                    >
                        REVIEW VERIFIED CVs
                    </a>
                @endif
                <button
                    type="button"
                    wire:click="openModal"
                    class="px-4 py-2.5 text-xs font-mono bg-ink text-paper rounded-xl shadow-soft hover:bg-neutral-800 transition-all"
                >
                    + POST VERIFIED ROLE
                </button>
            </div>
        </x-ui.card>

        {{-- KPI stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            <x-ui.stat label="Eligible CVs Received" :value="str_pad((string) $candidates->count(), 2, '0', STR_PAD_LEFT)" hint="Registrar work-right verified" />
            <x-ui.stat label="Posted Roles" :value="str_pad((string) $jobs->count(), 2, '0', STR_PAD_LEFT)" :hint="$liveJobs->count() . ' live • ' . ($jobs->count() - $liveJobs->count()) . ' in review'" />
            <x-ui.stat label="Interviews Active" :value="str_pad((string) $interviewCount, 2, '0', STR_PAD_LEFT)" hint="Pipeline in interview stage" />
            <x-ui.stat label="Approved Institutes" :value="(string) $activeInstitutes" hint="Partner career offices" />
        </div>

        {{-- Recent candidates + roles snapshot --}}
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
            <div class="xl:col-span-7 space-y-4">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-semibold tracking-tight text-ink">Latest Verified Candidates</h2>
                        <p class="text-xs text-muted">Pre-screened for legal right to work</p>
                    </div>
                    @if (Route::has('employer.candidates'))
                        <a href="{{ route('employer.candidates') }}" wire:navigate class="font-mono text-xs underline underline-offset-4 hover:opacity-75 text-ink">
                            Open full CV pool →
                        </a>
                    @endif
                </div>

                <div class="space-y-3">
                    @forelse ($candidates->take(3) as $candidate)
                        <button
                            type="button"
                            wire:click="openDrawer('{{ $candidate['id'] }}')"
                            class="w-full text-left p-5 bg-paper border border-hairline rounded-2xl shadow-soft hover:shadow-card hover:border-ink/40 transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-4"
                        >
                            <div class="space-y-1.5">
                                <div class="flex flex-wrap items-center gap-2">
                                    <x-ui.badge :tone="$stageBadgeTone($candidate['stage'])">
                                        {{ strtoupper($stageLabel($candidate['stage'])) }}
                                    </x-ui.badge>
                                    <span class="font-mono text-[11px] text-muted">✓ {{ $candidate['seal'] }}</span>
                                </div>
                                <h3 class="text-base font-semibold text-ink">
                                    {{ $candidate['name'] }}
                                    <span class="font-normal text-xs text-muted">• {{ $candidate['program'] }}</span>
                                </h3>
                                <p class="text-xs text-muted">Match score: <span class="text-ink font-medium">{{ $candidate['match'] }}</span></p>
                            </div>
                            <span class="font-mono text-[11px] px-3.5 py-2 rounded-lg bg-ink text-paper shrink-0">
                                INSPECT CV →
                            </span>
                        </button>
                    @empty
                        <x-ui.card>
                            <p class="font-mono text-xs text-muted text-center py-6">No verified candidates yet.</p>
                        </x-ui.card>
                    @endforelse
                </div>
            </div>

            <div class="xl:col-span-5 space-y-6">
                <x-ui.card :padding="false" class="overflow-hidden">
                    <div class="px-5 py-4 bg-subtle border-b border-hairline flex items-center justify-between">
                        <span class="font-mono text-xs font-semibold uppercase text-ink">Your Verified Roles</span>
                        @if (Route::has('employer.jobs'))
                            <a href="{{ route('employer.jobs') }}" wire:navigate class="font-mono text-[11px] text-muted hover:text-ink underline underline-offset-4">
                                Manage all
                            </a>
                        @endif
                    </div>
                    <div class="divide-y divide-hairline">
                        @foreach ($jobs->take(3) as $job)
                            <div class="p-4 flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-ink truncate">{{ $job['title'] }}</p>
                                    <p class="text-[11px] text-muted truncate mt-0.5">{{ $job['type'] }} • {{ $job['location'] }}</p>
                                    <p class="font-mono text-[10px] text-muted mt-1">{{ $job['institute'] }}</p>
                                </div>
                                <x-ui.badge :tone="($job['eligible'] ?? false) ? 'ink' : 'subtle'">
                                    {{ ($job['eligible'] ?? false) ? 'LIVE' : 'REVIEW' }}
                                </x-ui.badge>
                            </div>
                        @endforeach
                    </div>
                </x-ui.card>

                <x-seal.work-right
                    label="Legal pre-screening"
                    status="ZERO NOISE"
                    detail="Ineligible applicants blocked before inbox delivery"
                >
                    <p class="text-xs text-muted leading-relaxed">
                        KYB seal on file for {{ $org['name'] ?? 'your organization' }}. Compliance filter: strict.
                    </p>
                </x-seal.work-right>
            </div>
        </div>
    </div>

    @php
        $selected = $selectedId ? $candidates->firstWhere('id', $selectedId) : null;
    @endphp

    <x-portal.drawer wire:model="drawerOpen" width="max-w-lg" :title="$selected['name'] ?? 'Verified candidate'">
        @if ($selected)
            <x-slot:header>
                <div class="flex items-center gap-2">
                    <x-ui.badge tone="ink">{{ strtoupper($stageLabel($selected['stage'])) }}</x-ui.badge>
                    <span class="font-mono text-xs text-muted">{{ $selected['id'] }}</span>
                </div>
            </x-slot:header>

            <div class="space-y-5">
                <div>
                    <p class="font-mono text-xs text-muted uppercase tracking-wider">{{ $selected['program'] }}</p>
                    <p class="text-sm text-muted mt-1">Match {{ $selected['match'] }}</p>
                </div>

                <div class="p-4 bg-subtle border border-hairline rounded-xl space-y-2 text-xs">
                    <div class="flex items-center justify-between gap-2">
                        <span class="font-mono text-muted">LEGAL WORK RIGHT</span>
                        <span class="font-mono font-medium text-ink">{{ $selected['seal'] }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-2">
                        <span class="font-mono text-muted">REGISTRAR STATUS</span>
                        <span class="font-mono font-semibold text-ink">✓ AUTHENTICATED</span>
                    </div>
                </div>
            </div>

            <x-slot:footer>
                <div class="flex flex-col sm:flex-row gap-3 w-full">
                    <button type="button" wire:click="$set('drawerOpen', false)" class="flex-1 py-3 px-4 bg-paper border border-hairline rounded-xl text-xs font-mono text-ink hover:border-ink">
                        CLOSE
                    </button>
                    <button type="button" wire:click="$set('drawerOpen', false)" class="flex-1 py-3 px-4 bg-ink text-paper text-xs font-mono rounded-xl hover:bg-neutral-800">
                        INVITE TO INTERVIEW →
                    </button>
                </div>
            </x-slot:footer>
        @else
            <p class="font-mono text-xs text-muted">Select a candidate to view details.</p>
        @endif
    </x-portal.drawer>

    <x-portal.modal wire:model="modalOpen" title="Publish institute-verified role" maxWidth="max-w-xl">
        <form id="overview-post-role" class="space-y-4" wire:submit.prevent="$set('modalOpen', false)">
            <p class="text-xs text-muted">Submitted roles are routed to partner institute career offices for verification before going live.</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="block font-mono text-[11px] uppercase text-muted">Role title</label>
                    <input type="text" class="w-full px-3.5 py-2.5 text-sm bg-paper border border-hairline rounded-lg shadow-soft focus:border-ink focus:outline-none" placeholder="Junior Data Analyst" />
                </div>
                <div class="space-y-1.5">
                    <label class="block font-mono text-[11px] uppercase text-muted">Employment category</label>
                    <select class="w-full px-3.5 py-2.5 text-sm bg-paper border border-hairline rounded-lg focus:border-ink focus:outline-none">
                        <option>Full-Time</option>
                        <option>Part-Time (≤20h/wk)</option>
                        <option>Sponsorship</option>
                    </select>
                </div>
            </div>
        </form>
        <x-slot:footer>
            <div class="flex items-center justify-end gap-3">
                <button type="button" wire:click="$set('modalOpen', false)" class="px-4 py-2.5 text-xs font-mono border border-hairline rounded-xl hover:border-ink">
                    CANCEL
                </button>
                <button type="submit" form="overview-post-role" class="px-5 py-2.5 text-xs font-mono bg-ink text-paper rounded-xl hover:bg-neutral-800">
                    SUBMIT FOR VERIFICATION →
                </button>
            </div>
        </x-slot:footer>
    </x-portal.modal>
</div>