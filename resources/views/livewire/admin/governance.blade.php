<div class="max-w-7xl mx-auto space-y-6">
    <div class="space-y-6">
        <div>
            <span class="font-mono text-xs text-muted uppercase tracking-wider">Global Policy Enforcement</span>
            <h1 class="text-2xl font-semibold tracking-tight mt-1 text-ink">Platform Governance &amp; Root Audit Log</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <div class="lg:col-span-5 self-start overflow-hidden rounded-2xl border border-hairline bg-paper shadow-card">
                <div class="px-6 py-4 bg-ink text-paper flex items-center justify-between">
                    <span class="font-mono text-xs">GLOBAL COMPLIANCE POLICIES</span>
                    <span class="font-mono text-[10px] text-neutral-300">ENFORCED</span>
                </div>
                <div class="p-6 space-y-4 text-xs">
                    <label class="flex items-start justify-between gap-4 p-3.5 bg-subtle border border-hairline rounded-xl cursor-pointer">
                        <div>
                            <p class="font-semibold text-ink">Strict Term-Time Hour Cap (≤20h/wk)</p>
                            <p class="text-muted mt-0.5">Block enrolled student visa holders from applying to full-time roles.</p>
                        </div>
                        <input
                            type="checkbox"
                            checked
                            class="mt-0.5 shrink-0"
                            @change="window.dispatchEvent(new CustomEvent('portal-toast', { detail: { message: '✓ Global term-time hour guardrail policy updated.' } }))"
                        />
                    </label>
                    <label class="flex items-start justify-between gap-4 p-3.5 bg-subtle border border-hairline rounded-xl cursor-pointer">
                        <div>
                            <p class="font-semibold text-ink">Mandatory Employer KYB Gate</p>
                            <p class="text-muted mt-0.5">Require corporate registration &amp; sponsorship proof before posting.</p>
                        </div>
                        <input
                            type="checkbox"
                            checked
                            class="mt-0.5 shrink-0"
                            @change="window.dispatchEvent(new CustomEvent('portal-toast', { detail: { message: '✓ Mandatory Employer KYB policy updated.' } }))"
                        />
                    </label>
                    <label class="flex items-start justify-between gap-4 p-3.5 bg-subtle border border-hairline rounded-xl cursor-pointer">
                        <div>
                            <p class="font-semibold text-ink">Automated Registrar SIS Attestation</p>
                            <p class="text-muted mt-0.5">Attach cryptographic work-right seal to every submitted student CV.</p>
                        </div>
                        <input
                            type="checkbox"
                            checked
                            class="mt-0.5 shrink-0"
                            @change="window.dispatchEvent(new CustomEvent('portal-toast', { detail: { message: '✓ Registrar SIS attestation policy updated.' } }))"
                        />
                    </label>
                </div>
            </div>

            <div class="lg:col-span-7 overflow-hidden rounded-2xl border border-hairline bg-paper shadow-card">
                <div class="px-6 py-4 bg-subtle border-b border-hairline flex items-center justify-between">
                    <span class="font-mono text-xs font-semibold uppercase">Immutable Root Audit Ledger</span>
                    <button
                        type="button"
                        class="font-mono text-[11px] underline underline-offset-4 hover:opacity-75"
                        @click="window.dispatchEvent(new CustomEvent('portal-toast', { detail: { message: 'Root signing key copied.' } }))"
                    >
                        Copy Root Key
                    </button>
                </div>
                <div class="divide-y divide-hairline">
                    @foreach ($auditLogs as $log)
                        <div class="px-6 py-4 hover:bg-subtle/50 transition-colors">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="font-mono text-[11px] text-muted">{{ $log['when'] }}</span>
                                <x-ui.badge tone="subtle">ROOT / {{ strtoupper($log['actor']) }}</x-ui.badge>
                            </div>
                            <p class="text-sm font-medium text-ink mt-1.5">{{ $log['action'] }}</p>
                            <p class="text-xs text-muted mt-0.5">{{ $log['target'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>