@php
    $student = $students->first();
    $userName = auth()->user()?->name ?? ($student['name'] ?? 'Alex Mercer');
    $userEmail = auth()->user()?->email ?? 'alex.mercer@polytechnic.edu';
    $studentId = $student['id'] ?? 'STU-8492';
    $hoursCap = $student['hours'] ?? '≤20h/wk';
    $showCv = in_array($filter, ['all', 'cv'], true);
    $showHours = in_array($filter, ['all', 'hours'], true);
@endphp

<div class="max-w-7xl mx-auto space-y-6">
    <div class="max-w-7xl mx-auto space-y-6">
        <div>
            <span class="font-mono text-xs text-muted uppercase tracking-wider">Registrar &amp; Immigration Compliance</span>
            <h1 class="text-2xl font-semibold tracking-tight mt-1 text-ink">Legal Work-Right &amp; CV Passport</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <x-ui.card :padding="false" class="lg:col-span-7 overflow-hidden" id="certificate">
                <div class="px-6 py-4 bg-ink text-paper flex items-center justify-between">
                    <div class="flex items-center gap-2 font-mono text-xs">
                        <span class="w-2 h-2 rounded-full bg-paper"></span>
                        <span>OFFICIAL WORK-ELIGIBILITY ATTESTATION</span>
                    </div>
                    <span class="font-mono text-[11px] text-neutral-300">2026–2027</span>
                </div>
                <div class="p-6 sm:p-7 space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-hairline">
                        <div>
                            <p class="font-mono text-[11px] text-muted uppercase">Verified Candidate</p>
                            <h2 class="text-xl font-semibold text-ink mt-0.5">{{ $userName }}</h2>
                            <p class="font-mono text-xs text-muted mt-0.5">{{ $userEmail }}</p>
                        </div>
                        <div class="px-3.5 py-2 bg-subtle border border-hairline rounded-xl font-mono text-xs text-ink">
                            ID: {{ $studentId }}
                        </div>
                    </div>

                    <x-seal.work-right
                        label="Institute Work-Right Seal"
                        status="ACTIVE"
                        :detail="$hoursCap . ' • Polytechnic Institute'"
                    >
                        <p class="font-mono text-[10px] text-muted mt-2">CERT: SJW-POLY-2026-8492</p>
                    </x-seal.work-right>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div class="p-4 bg-subtle border border-hairline rounded-xl space-y-1">
                            <span class="font-mono text-[10px] text-muted uppercase">Issuing Institute</span>
                            <p class="font-semibold text-ink">Polytechnic Institute</p>
                            <p class="text-muted">Faculty of Computer Science</p>
                        </div>
                        <div class="p-4 bg-subtle border border-hairline rounded-xl space-y-1">
                            <span class="font-mono text-[10px] text-muted uppercase">Statutory Authorization</span>
                            <p class="font-semibold text-ink">Student Visa (Term-Time)</p>
                            <p class="text-muted">Max 20 hrs/week • Full-time in recess</p>
                        </div>
                        <div class="p-4 bg-subtle border border-hairline rounded-xl space-y-1">
                            <span class="font-mono text-[10px] text-muted uppercase">Verification Hash</span>
                            <p class="font-mono font-semibold text-ink">SJW-POLY-2026-8492</p>
                            <p class="text-muted">Auto-attached to all applications</p>
                        </div>
                        <div class="p-4 bg-subtle border border-hairline rounded-xl space-y-1">
                            <span class="font-mono text-[10px] text-muted uppercase">Cohort</span>
                            <p class="font-semibold text-ink">{{ $student['cohort'] ?? 'Term-Time' }}</p>
                            <p class="text-muted">{{ $hoursCap }} verified weekly cap</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 pt-2">
                        <button type="button" class="px-4 py-2.5 bg-ink text-paper text-xs font-mono rounded-xl shadow-soft hover:bg-neutral-800 transition-colors">
                            COPY VERIFICATION CODE
                        </button>
                        <button type="button" class="px-4 py-2.5 bg-paper border border-hairline text-ink text-xs font-mono rounded-xl shadow-soft hover:border-ink transition-colors">
                            DOWNLOAD ATTESTATION PDF
                        </button>
                    </div>
                </div>
            </x-ui.card>

            <div class="lg:col-span-5 space-y-6">
                @if ($showCv)
                    <x-ui.card class="space-y-4" id="cv">
                        <div class="flex items-center justify-between">
                            <span class="font-mono text-xs font-semibold uppercase">Verified CV &amp; Transcript</span>
                            <x-ui.badge tone="subtle">ACTIVE</x-ui.badge>
                        </div>
                        <div class="p-4 bg-subtle border border-hairline rounded-xl flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-ink truncate">{{ str_replace(' ', '_', $userName) }}_CV_2026_Verified.pdf</p>
                                <p class="font-mono text-[10px] text-muted mt-0.5">Stamped by Career Office • 420 KB</p>
                            </div>
                            <span class="font-mono text-[11px] text-ink shrink-0">✓</span>
                        </div>
                        <button type="button" class="w-full py-2.5 px-4 bg-paper border border-hairline rounded-xl shadow-soft hover:border-ink text-xs font-mono text-ink transition-colors">
                            UPLOAD NEW CV REVISION
                        </button>
                    </x-ui.card>
                @endif

                @if ($showHours)
                    <x-ui.card class="space-y-4" id="hours">
                        <div class="flex items-center justify-between">
                            <span class="font-mono text-xs font-semibold uppercase">Term-Time Hour Cap</span>
                            <span class="font-mono text-xs font-semibold text-ink">0 / 20 HRS</span>
                        </div>
                        <p class="text-xs text-muted leading-relaxed">
                            Students on academic study permits may work up to 20 hours per week during active lecture weeks.
                            The portal blocks overlapping part-time contracts that exceed statutory limits.
                        </p>
                        <div class="w-full h-2 bg-subtle border border-hairline rounded-full overflow-hidden">
                            <div class="w-[5%] h-full bg-ink"></div>
                        </div>
                        <p class="font-mono text-[11px] text-muted">STATUS: ELIGIBLE FOR PART-TIME &amp; GRADUATE ROLES</p>
                    </x-ui.card>
                @endif
            </div>
        </div>
    </div>
</div>