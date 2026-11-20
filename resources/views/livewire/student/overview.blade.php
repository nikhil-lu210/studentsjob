@php
    $eligibleCount = $jobs->where('eligible', true)->count();
    $recommended = $jobs->where('eligible', true)->take(3);
    $recentApps = $applications->take(3);
    $interviewCount = $applications->where('filter', 'interview')->count();
    $reviewCount = $applications->where('filter', 'review')->count();
    $userName = auth()->user()?->name ?? 'Student';
@endphp

<div class="max-w-7xl mx-auto space-y-6">
    <div class="max-w-7xl mx-auto space-y-8">
        <x-ui.card class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="space-y-1.5">
                <div class="inline-flex items-center gap-2 font-mono text-[11px] text-muted">
                    <span class="w-1.5 h-1.5 rounded-full bg-ink"></span>
                    <span>REGISTRAR &amp; WORK-ELIGIBILITY STATUS: VERIFIED</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight text-ink">
                    Welcome back, {{ $userName }}
                </h1>
                <p class="text-xs sm:text-sm text-muted max-w-2xl leading-relaxed">
                    Your enrollment and statutory work authorization are pre-verified by
                    <strong class="font-medium text-ink">Polytechnic Institute</strong>.
                    Every eligible role matches your legal right to work.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <a
                    href="{{ route('student.credentials') }}"
                    wire:navigate
                    class="px-4 py-2.5 text-xs font-mono bg-paper border border-hairline rounded-xl shadow-soft hover:border-ink transition-all"
                >
                    VIEW WORK-RIGHT ID
                </a>
                <a
                    href="{{ route('student.jobs') }}"
                    wire:navigate
                    class="px-4 py-2.5 text-xs font-mono bg-ink text-paper rounded-xl shadow-soft hover:bg-neutral-800 transition-all"
                >
                    BROWSE ALL JOBS →
                </a>
            </div>
        </x-ui.card>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            <x-ui.stat
                label="Eligible Roles"
                :value="str_pad((string) $eligibleCount, 2, '0', STR_PAD_LEFT)"
                hint="Institute-vetted openings for your degree"
            />
            <x-ui.stat
                label="Active Applications"
                :value="str_pad((string) $applications->count(), 2, '0', STR_PAD_LEFT)"
                :hint="$interviewCount . ' interview invite' . ($interviewCount === 1 ? '' : 's') . ' • ' . $reviewCount . ' in employer review'"
            />
            <x-ui.stat label="Legal Work Cap" value="20h/wk" hint="Term-time student visa limit verified" />
            <x-ui.stat label="Verified CV Views" value="14" hint="Viewed by approved campus employers" />
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
            <div class="xl:col-span-7 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold tracking-tight text-ink">Recommended Verified Roles</h2>
                        <p class="text-xs text-muted">Pre-screened for your academic cohort and legal work permit</p>
                    </div>
                    <a href="{{ route('student.jobs') }}" wire:navigate class="font-mono text-xs underline underline-offset-4 hover:opacity-75 text-ink">
                        View all ({{ $jobs->count() }}) →
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse ($recommended as $job)
                        @php
                            $hasApplied = $applications->contains(fn ($app) => $app['role'] === $job['title']);
                        @endphp
                        <button
                            type="button"
                            wire:click="openDrawer('{{ $job['id'] }}')"
                            class="w-full text-left p-5 bg-paper border border-hairline rounded-2xl shadow-soft hover:shadow-card hover:border-ink/40 transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-4"
                        >
                            <div class="space-y-1.5 min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <x-ui.badge :tone="$job['visa'] ? 'ink' : 'subtle'">{{ strtoupper($job['type']) }}</x-ui.badge>
                                    <span class="font-mono text-[11px] text-muted">{{ $job['hours'] }}</span>
                                </div>
                                <h3 class="text-base font-semibold text-ink">{{ $job['title'] }}</h3>
                                <p class="text-xs text-muted">{{ $job['company'] }} • {{ $job['location'] }}</p>
                            </div>
                            <div class="shrink-0">
                                @if ($hasApplied)
                                    <x-ui.badge tone="subtle">✓ APPLIED</x-ui.badge>
                                @else
                                    <x-ui.badge tone="ink">REVIEW →</x-ui.badge>
                                @endif
                            </div>
                        </button>
                    @empty
                        <x-ui.card padding="true" class="text-center">
                            <p class="font-mono text-xs text-muted">No eligible roles at this time.</p>
                        </x-ui.card>
                    @endforelse
                </div>
            </div>

            <div class="xl:col-span-5 space-y-6">
                <x-ui.card :padding="false" class="overflow-hidden">
                    <div class="px-5 py-4 bg-subtle border-b border-hairline flex items-center justify-between">
                        <span class="font-mono text-xs font-semibold uppercase text-ink">Recent Applications</span>
                        <a
                            href="{{ route('student.applications') }}"
                            wire:navigate
                            class="font-mono text-[11px] text-muted hover:text-ink underline underline-offset-4"
                        >
                            Manage all
                        </a>
                    </div>
                    <div class="divide-y divide-hairline">
                        @foreach ($recentApps as $app)
                            <div class="p-4 flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-ink truncate">{{ $app['role'] }}</p>
                                    <p class="text-[11px] text-muted truncate mt-0.5">{{ $app['company'] }}</p>
                                    <p class="font-mono text-[10px] text-muted mt-1">{{ $app['id'] }} • {{ $app['updated'] }}</p>
                                </div>
                                <x-ui.badge :tone="$app['filter'] === 'interview' ? 'ink' : 'subtle'">
                                    {{ strtoupper($app['status']) }}
                                </x-ui.badge>
                            </div>
                        @endforeach
                    </div>
                </x-ui.card>

                <x-ui.card class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="font-mono text-[11px] uppercase text-muted">Institutional Seal</span>
                        <x-ui.badge tone="ink">HASH VERIFIED</x-ui.badge>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-ink">Automatic Legal Screening Active</h3>
                        <p class="text-xs text-muted mt-1 leading-relaxed">
                            Employers receive your CV with an institute-signed attestation confirming enrollment and work eligibility.
                        </p>
                    </div>
                    <x-seal.work-right status="ACTIVE" detail="Polytechnic Institute • Term-Time ≤20h" />
                </x-ui.card>
            </div>
        </div>
    </div>

    @php $drawerJob = $selectedId ? $jobs->firstWhere('id', $selectedId) : null; @endphp
    <x-portal.drawer wire:model="drawerOpen" :title="$drawerJob['title'] ?? 'Role detail'">
        @if ($drawerJob)
            <div class="space-y-5">
                <div class="flex flex-wrap items-center gap-2 font-mono text-xs">
                    <x-ui.badge :tone="$drawerJob['visa'] ? 'ink' : 'subtle'">{{ strtoupper($drawerJob['type']) }}</x-ui.badge>
                    <span class="text-muted">{{ $drawerJob['id'] }}</span>
                </div>
                <div>
                    <p class="font-mono text-xs text-muted uppercase">{{ $drawerJob['company'] }}</p>
                    <p class="text-sm text-muted mt-1">{{ $drawerJob['location'] }} • {{ $drawerJob['salary'] }}</p>
                </div>
                <x-ui.card class="space-y-2 text-xs">
                    <div class="flex justify-between gap-2 font-mono">
                        <span class="text-muted">INSTITUTE</span>
                        <span class="text-ink text-right">{{ $drawerJob['institute'] }}</span>
                    </div>
                    <div class="flex justify-between gap-2 font-mono">
                        <span class="text-muted">YOUR ELIGIBILITY</span>
                        <span class="text-ink">✓ 100% LEGALLY ELIGIBLE</span>
                    </div>
                    <div class="flex justify-between gap-2 font-mono">
                        <span class="text-muted">HOURS</span>
                        <span class="text-ink">{{ $drawerJob['hours'] }}</span>
                    </div>
                </x-ui.card>
                <p class="text-sm text-ink/90 leading-relaxed">{{ $drawerJob['summary'] }}</p>
            </div>
            <x-slot:footer>
                <button
                    type="button"
                    class="w-full py-3 px-4 bg-ink text-paper text-sm font-medium rounded-xl shadow-soft hover:bg-neutral-800 transition-colors font-mono text-xs"
                >
                    Submit 1-Click Verified Application →
                </button>
            </x-slot:footer>
        @endif
    </x-portal.drawer>
</div>