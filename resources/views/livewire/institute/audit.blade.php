<div class="max-w-7xl mx-auto space-y-6">
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <span class="font-mono text-xs text-muted uppercase tracking-wider">Immutable Institutional Record</span>
                <h1 class="text-2xl font-semibold tracking-tight mt-1 text-ink">Compliance &amp; Enforcement Audit Ledger</h1>
            </div>
            <button
                type="button"
                class="px-4 py-2 text-xs font-mono bg-paper border border-hairline rounded-xl shadow-soft hover:border-ink self-start sm:self-auto"
                @click="window.dispatchEvent(new CustomEvent('portal-toast', { detail: { message: 'Audit Merkle hash copied to clipboard.' } }))"
            >
                COPY AUDIT MERKLE HASH
            </button>
        </div>

        <x-ui.card :padding="false" class="divide-y divide-hairline overflow-hidden">
            @foreach ($auditLogs as $log)
                <div class="px-5 sm:px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-subtle/50 transition-colors">
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="font-mono text-[11px] text-muted">{{ $log['when'] }}</span>
                            <x-ui.badge tone="subtle">{{ strtoupper($log['actor']) }}</x-ui.badge>
                        </div>
                        <p class="text-sm font-medium text-ink mt-1.5">{{ $log['action'] }}</p>
                        <p class="text-xs text-muted mt-0.5">{{ $log['target'] }}</p>
                    </div>
                    <span class="font-mono text-[10px] text-muted shrink-0">LOG-{{ substr(md5($log['when'].$log['target']), 0, 8) }}</span>
                </div>
            @endforeach
        </x-ui.card>
    </div>
</div>