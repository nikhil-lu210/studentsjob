@php
    $pillClass = fn (string $key) => $filter === $key
        ? 'px-3.5 py-2 text-xs font-mono rounded-lg bg-ink text-paper border border-ink shadow-soft'
        : 'px-3.5 py-2 text-xs font-mono rounded-lg bg-paper text-ink border border-hairline shadow-soft hover:border-ink';

    $jobLive = fn (array $job) => (bool) ($job['eligible'] ?? false);

    $filtered = $jobs->filter(function (array $job) use ($filter, $jobLive) {
        return match ($filter) {
            'live' => $jobLive($job),
            'pending' => ! $jobLive($job),
            default => true,
        };
    });

    $counts = [
        'all' => $jobs->count(),
        'live' => $jobs->filter(fn (array $job) => $jobLive($job))->count(),
        'pending' => $jobs->reject(fn (array $job) => $jobLive($job))->count(),
    ];
@endphp

<div class="max-w-7xl mx-auto space-y-6">
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <span class="font-mono text-xs text-muted uppercase tracking-wider">Campus-vetted listings</span>
                <h1 class="text-2xl font-semibold tracking-tight mt-1 text-ink">Posted roles</h1>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <button type="button" wire:click="$set('filter', 'all')" @class([$pillClass('all')])>
                    ALL ({{ $counts['all'] }})
                </button>
                <button type="button" wire:click="$set('filter', 'live')" @class([$pillClass('live')])>
                    LIVE &amp; VERIFIED ({{ $counts['live'] }})
                </button>
                <button type="button" wire:click="$set('filter', 'pending')" @class([$pillClass('pending')])>
                    PENDING ({{ $counts['pending'] }})
                </button>
                <button
                    type="button"
                    wire:click="openModal"
                    class="px-4 py-2 text-xs font-mono bg-ink text-paper rounded-lg shadow-soft hover:bg-neutral-800 transition-colors"
                >
                    + POST NEW ROLE
                </button>
            </div>
        </div>

        <div class="space-y-3.5">
            @forelse ($filtered as $job)
                <article wire:key="job-{{ $job['id'] }}" class="p-5 sm:p-6 bg-paper border border-hairline rounded-2xl shadow-soft flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div class="space-y-2 max-w-2xl">
                        <div class="flex flex-wrap items-center gap-2">
                            <x-ui.badge :tone="$jobLive($job) ? 'ink' : 'subtle'">
                                {{ $jobLive($job) ? 'LIVE • VERIFIED' : 'PENDING REVIEW' }}
                            </x-ui.badge>
                            <x-ui.badge tone="subtle">{{ strtoupper($job['type']) }}</x-ui.badge>
                            <span class="font-mono text-[11px] text-muted">{{ $job['institute'] }}</span>
                        </div>
                        <h3 class="text-lg font-semibold text-ink">{{ $job['title'] }}</h3>
                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-muted">
                            <span>{{ $job['location'] }}</span>
                            <span>•</span>
                            <span class="font-mono text-ink">{{ $job['salary'] }}</span>
                            <span>•</span>
                            <span>{{ $job['hours'] }}</span>
                        </div>
                        <p class="text-xs text-muted leading-relaxed">{{ $job['summary'] }}</p>
                    </div>

                    <div class="flex items-center justify-between lg:justify-end gap-3 pt-3 lg:pt-0 border-t lg:border-t-0 border-hairline shrink-0">
                        <div class="text-right mr-2">
                            <p class="font-mono text-sm font-semibold text-ink">{{ $job['company'] }}</p>
                            <p class="font-mono text-[10px] text-muted">{{ $job['id'] }}</p>
                        </div>
                        <button
                            type="button"
                            wire:click="openDrawer('{{ $job['id'] }}')"
                            class="px-4 py-2 text-xs font-mono bg-ink text-paper rounded-lg hover:bg-neutral-800 transition-colors"
                        >
                            DETAILS →
                        </button>
                    </div>
                </article>
            @empty
                <x-ui.card>
                    <p class="font-mono text-xs text-muted text-center py-8">No roles match this filter.</p>
                </x-ui.card>
            @endforelse
        </div>
    </div>

    @php
        $selected = $selectedId ? $jobs->firstWhere('id', $selectedId) : null;
    @endphp

    <x-portal.drawer wire:model="drawerOpen" width="max-w-lg" :title="$selected['title'] ?? 'Role details'">
        @if ($selected)
            <div class="space-y-4 text-sm">
                <div class="flex flex-wrap gap-2">
                    <x-ui.badge :tone="$jobLive($selected) ? 'ink' : 'subtle'">
                        {{ $jobLive($selected) ? 'LIVE' : 'PENDING' }}
                    </x-ui.badge>
                    <x-ui.badge tone="subtle">{{ $selected['type'] }}</x-ui.badge>
                </div>
                <dl class="space-y-2 text-xs">
                    <div class="flex justify-between gap-4 border-b border-hairline pb-2">
                        <dt class="font-mono text-muted">INSTITUTE</dt>
                        <dd class="font-medium text-ink text-right">{{ $selected['institute'] }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 border-b border-hairline pb-2">
                        <dt class="font-mono text-muted">LOCATION</dt>
                        <dd class="font-medium text-ink text-right">{{ $selected['location'] }}</dd>
                    </div>
                    <div class="flex justify-between gap-4 border-b border-hairline pb-2">
                        <dt class="font-mono text-muted">COMPENSATION</dt>
                        <dd class="font-mono text-ink text-right">{{ $selected['salary'] }}</dd>
                    </div>
                </dl>
                <p class="text-muted leading-relaxed">{{ $selected['summary'] }}</p>
            </div>
        @else
            <p class="font-mono text-xs text-muted">Select a posting to view details.</p>
        @endif
    </x-portal.drawer>

    <x-portal.modal wire:model="modalOpen" title="Publish institute-verified role" maxWidth="max-w-xl">
        <form id="employer-post-role" class="space-y-4" wire:submit.prevent="$set('modalOpen', false)">
            <div class="flex items-center gap-2 font-mono text-xs text-muted mb-2">
                <span class="w-2 h-2 rounded-full bg-ink"></span>
                <span>Roles require institute career-office approval before going live.</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="block font-mono text-[11px] uppercase text-muted">Role title</label>
                    <input type="text" required class="w-full px-3.5 py-2.5 text-sm bg-paper border border-hairline rounded-lg shadow-soft focus:border-ink focus:outline-none" placeholder="Quantitative Risk Analyst" />
                </div>
                <div class="space-y-1.5">
                    <label class="block font-mono text-[11px] uppercase text-muted">Employment category</label>
                    <select class="w-full px-3.5 py-2.5 text-sm bg-paper border border-hairline rounded-lg focus:border-ink focus:outline-none">
                        <option>Full-Time</option>
                        <option>Part-Time (≤20 hrs/week)</option>
                        <option>Sponsorship</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="block font-mono text-[11px] uppercase text-muted">Location &amp; mode</label>
                    <input type="text" required class="w-full px-3.5 py-2.5 text-sm bg-paper border border-hairline rounded-lg focus:border-ink focus:outline-none" placeholder="Hybrid / On-campus" />
                </div>
                <div class="space-y-1.5">
                    <label class="block font-mono text-[11px] uppercase text-muted">Compensation</label>
                    <input type="text" required class="w-full px-3.5 py-2.5 text-sm bg-paper border border-hairline rounded-lg focus:border-ink focus:outline-none" placeholder="£32k–36k / yr" />
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="block font-mono text-[11px] uppercase text-muted">Target partner institute</label>
                <select class="w-full px-3.5 py-2.5 text-sm bg-paper border border-hairline rounded-lg focus:border-ink focus:outline-none">
                    @foreach ($institutes as $institute)
                        <option value="{{ $institute['id'] }}">{{ $institute['name'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-1.5">
                <label class="block font-mono text-[11px] uppercase text-muted">Role overview</label>
                <textarea rows="3" required class="w-full px-3.5 py-2.5 text-sm bg-paper border border-hairline rounded-lg focus:border-ink focus:outline-none" placeholder="Describe responsibilities and eligible degrees…"></textarea>
            </div>
        </form>

        <x-slot:footer>
            <div class="flex items-center justify-end gap-3">
                <button type="button" wire:click="$set('modalOpen', false)" class="px-4 py-2.5 text-xs font-mono border border-hairline rounded-xl hover:border-ink">
                    CANCEL
                </button>
                <button type="submit" form="employer-post-role" class="px-5 py-2.5 text-xs font-mono bg-ink text-paper rounded-xl hover:bg-neutral-800">
                    SUBMIT FOR INSTITUTE VERIFICATION →
                </button>
            </div>
        </x-slot:footer>
    </x-portal.modal>
</div>