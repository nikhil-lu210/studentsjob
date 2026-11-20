@php
    $partner = $institutes->firstWhere('status', 'active') ?? $institutes->first();
@endphp

<div class="max-w-7xl mx-auto space-y-6">
    <div class="space-y-6">
        <div>
            <span class="font-mono text-xs text-muted uppercase tracking-wider">University partnerships</span>
            <h1 class="text-2xl font-semibold tracking-tight mt-1 text-ink">Institute career office desk</h1>
            <p class="text-xs text-muted mt-2 max-w-2xl leading-relaxed">
                Liaison channel with partner registrars and career coordinators. Messaging and event scheduling will connect here.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <x-ui.card :padding="false" class="lg:col-span-7 overflow-hidden flex flex-col min-h-[22rem]">
                <div class="px-6 py-4 bg-subtle border-b border-hairline flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h2 class="text-sm font-semibold text-ink">{{ $partner['name'] ?? 'Partner institute' }} — employer relations</h2>
                        <p class="font-mono text-[11px] text-muted">Verified liaison desk • placeholder thread</p>
                    </div>
                    <x-ui.badge tone="ink">VERIFIED CHANNEL</x-ui.badge>
                </div>

                <div class="flex-1 p-6 space-y-4 overflow-y-auto bg-paper">
                    <div class="p-4 bg-subtle border border-hairline rounded-xl space-y-1.5">
                        <div class="flex items-center justify-between font-mono text-[10px] text-muted">
                            <span class="text-ink font-semibold">CAREER OFFICE (SYSTEM)</span>
                            <span>PLACEHOLDER</span>
                        </div>
                        <p class="text-xs text-ink leading-relaxed">
                            Welcome to the institute career office desk. Verified role postings and candidate CVs are coordinated through this channel once messaging is enabled.
                        </p>
                    </div>

                    <div class="p-8 border border-dashed border-hairline rounded-xl text-center">
                        <p class="font-mono text-[11px] text-muted uppercase tracking-wider">Desk placeholder</p>
                        <p class="text-xs text-muted mt-2">Compose messages, attach role briefs, and schedule campus events from this workspace.</p>
                    </div>
                </div>

                <form class="p-4 bg-subtle border-t border-hairline flex gap-2.5" wire:submit.prevent>
                    <input
                        type="text"
                        disabled
                        placeholder="Message university career office coordinators…"
                        class="flex-1 px-3.5 py-2.5 text-xs bg-paper border border-hairline rounded-xl shadow-soft opacity-60 cursor-not-allowed"
                    />
                    <button
                        type="button"
                        disabled
                        class="px-4 py-2.5 bg-ink/50 text-paper text-xs font-mono rounded-xl shrink-0 cursor-not-allowed"
                    >
                        SEND →
                    </button>
                </form>
            </x-ui.card>

            <x-ui.card class="lg:col-span-5 self-start space-y-4">
                <span class="font-mono text-xs font-semibold uppercase text-ink">Campus recruitment events</span>
                <p class="text-xs text-muted leading-relaxed">
                    Reserve a verified employer booth or interview room on partner campuses.
                </p>
                <div class="space-y-2.5 text-xs font-mono">
                    <div class="p-3 bg-subtle border border-hairline rounded-xl flex items-center justify-between gap-2">
                        <span class="text-muted">WINTER TECH SYMPOSIUM</span>
                        <button type="button" wire:click="openModal" class="px-3 py-1 bg-ink text-paper rounded-lg hover:bg-neutral-800">
                            RESERVE
                        </button>
                    </div>
                    <div class="p-3 bg-subtle border border-hairline rounded-xl flex items-center justify-between gap-2">
                        <span class="text-muted">GRADUATE SPONSOR FAIR</span>
                        <button type="button" wire:click="openModal" class="px-3 py-1 bg-ink text-paper rounded-lg hover:bg-neutral-800">
                            RESERVE
                        </button>
                    </div>
                </div>
            </x-ui.card>
        </div>
    </div>

    <x-portal.modal wire:model="modalOpen" title="Campus event reservation" maxWidth="max-w-md">
        <p class="text-xs text-muted leading-relaxed">
            Event reservations are sent to the partner institute career office for confirmation. This is a UI placeholder until booking is wired.
        </p>
        <x-slot:footer>
            <div class="flex justify-end gap-3">
                <button type="button" wire:click="$set('modalOpen', false)" class="px-4 py-2 text-xs font-mono border border-hairline rounded-xl hover:border-ink">
                    CLOSE
                </button>
                <button type="button" wire:click="$set('modalOpen', false)" class="px-4 py-2 text-xs font-mono bg-ink text-paper rounded-xl hover:bg-neutral-800">
                    REQUEST BOOTH →
                </button>
            </div>
        </x-slot:footer>
    </x-portal.modal>
</div>