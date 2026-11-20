<div class="max-w-7xl mx-auto space-y-6">
    <div class="max-w-7xl mx-auto space-y-6">
        <div>
            <span class="font-mono text-xs text-muted uppercase tracking-wider">Institutional Support</span>
            <h1 class="text-2xl font-semibold tracking-tight mt-1 text-ink">Career Office &amp; Verification Desk</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <x-ui.card :padding="false" class="lg:col-span-7 overflow-hidden flex flex-col">
                <div class="px-6 py-4 bg-subtle border-b border-hairline flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h2 class="text-sm font-semibold text-ink">Polytechnic Career &amp; Visa Compliance Office</h2>
                        <p class="font-mono text-[11px] text-muted mt-0.5">Advisor: Dr. Elena Vance • careers-office@polytechnic.edu</p>
                    </div>
                    <x-ui.badge tone="ink">
                        <span class="w-1.5 h-1.5 rounded-full bg-paper"></span>
                        ONLINE
                    </x-ui.badge>
                </div>

                <div class="p-6 space-y-4 max-h-80 overflow-y-auto flex-1">
                    <div class="p-4 bg-subtle border border-hairline rounded-xl space-y-1.5">
                        <div class="flex items-center justify-between font-mono text-[10px] text-muted">
                            <span class="text-ink font-semibold">DR. ELENA VANCE (CAREER OFFICE)</span>
                            <span>YESTERDAY, 14:20</span>
                        </div>
                        <p class="text-xs text-ink leading-relaxed">
                            Your MSc enrollment and Autumn semester work-eligibility attestation have been verified.
                            You can now apply directly to all institute-vetted part-time roles on the portal.
                        </p>
                    </div>
                </div>

                <div class="p-4 bg-subtle border-t border-hairline flex gap-2.5">
                    <input
                        type="text"
                        placeholder="Ask your institute career advisor about sponsorship or CV review..."
                        class="flex-1 px-3.5 py-2.5 text-xs bg-paper border border-hairline rounded-xl shadow-soft focus:border-ink focus:outline-none"
                    />
                    <button type="button" class="px-4 py-2.5 bg-ink text-paper text-xs font-mono rounded-xl shadow-soft hover:bg-neutral-800 transition-colors shrink-0">
                        SEND →
                    </button>
                </div>
            </x-ui.card>

            <x-ui.card class="lg:col-span-5 space-y-4 self-start">
                <span class="font-mono text-xs font-semibold uppercase">Contact &amp; Advisory</span>
                <p class="text-xs text-muted leading-relaxed">
                    Book a 20-minute CV audit or post-study work permit consultation with your faculty advisor.
                </p>
                <div class="p-4 bg-subtle border border-hairline rounded-xl space-y-3 text-xs">
                    <div class="flex items-center justify-between font-mono">
                        <span class="text-muted">EMAIL</span>
                        <a href="mailto:careers-office@polytechnic.edu" class="text-ink hover:underline">careers-office@polytechnic.edu</a>
                    </div>
                    <div class="flex items-center justify-between font-mono">
                        <span class="text-muted">PHONE</span>
                        <span class="text-ink">+44 (0)20 7946 0958</span>
                    </div>
                    <div class="flex items-center justify-between font-mono">
                        <span class="text-muted">DESK HOURS</span>
                        <span class="text-ink">Mon–Fri 09:00–17:00</span>
                    </div>
                </div>
                <div class="space-y-2.5 text-xs font-mono">
                    <div class="p-3 bg-subtle border border-hairline rounded-xl flex items-center justify-between">
                        <span>MON, NOV 23 • 10:30</span>
                        <button type="button" class="px-3 py-1 bg-ink text-paper rounded-lg">BOOK</button>
                    </div>
                    <div class="p-3 bg-subtle border border-hairline rounded-xl flex items-center justify-between">
                        <span>WED, NOV 25 • 15:00</span>
                        <button type="button" class="px-3 py-1 bg-ink text-paper rounded-lg">BOOK</button>
                    </div>
                </div>
            </x-ui.card>
        </div>
    </div>
</div>