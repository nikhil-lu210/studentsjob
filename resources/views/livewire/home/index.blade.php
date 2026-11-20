@php
    $allJobsCount = count(\App\Support\Demo\DemoCatalog::jobs());

    $filterTabClass = fn (string $key) => $filter === $key
        ? 'px-4 py-2 text-xs font-mono rounded-lg bg-ink text-paper border border-ink shadow-soft transition-all'
        : 'px-4 py-2 text-xs font-mono rounded-lg bg-paper text-ink border border-hairline shadow-soft hover:border-ink transition-all';

    $jobTypeKey = fn (array $job) => ($job['visa'] ?? false) ? 'Sponsorship' : $job['type'];

    $jobTypeBadge = fn (array $job) => match ($jobTypeKey($job)) {
        'Sponsorship' => 'SPONSORSHIP',
        'Part-Time' => 'PART-TIME',
        default => 'FULL-TIME',
    };

    $jobEligibility = fn (array $job) => match (true) {
        ($job['visa'] ?? false) && ! ($job['eligible'] ?? true) => 'Visa Sponsorship Eligible',
        ($job['visa'] ?? false) => 'Licensed Visa Sponsor',
        ($job['eligible'] ?? false) => 'Student Visa Compliant (' . ($job['hours'] ?? 'Term-time') . ')',
        default => 'Work-Right Verification Required',
    };
@endphp

<div
    class="bg-paper text-ink selection:bg-ink selection:text-paper min-h-screen"
    x-data="{
        mobileOpen: false,
        search: '',
        jobDrawerOpen: @entangle('jobDrawerOpen').live,
    }"
    x-effect="document.body.classList.toggle('overflow-hidden', jobDrawerOpen)"
    @keydown.escape.window="$wire.set('jobDrawerOpen', false)"
>
    {{-- TOP INSTITUTIONAL TRUST BAR --}}
    <div class="border-b border-hairline bg-subtle text-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2 flex flex-col sm:flex-row items-center justify-between gap-2">
            <div class="flex items-center gap-2 font-mono text-[11px] tracking-tight text-muted">
                <span class="inline-block w-2 h-2 rounded-full bg-ink"></span>
                <span>INSTITUTE-MANAGED NETWORK // 100% VERIFIED LISTINGS &amp; LEGAL WORK-ELIGIBILITY</span>
            </div>
            <div class="font-mono text-[11px] text-ink flex items-center gap-3">
                <span>studentsjob.work</span>
                <span class="text-hairline">|</span>
                <a href="{{ route('login') }}" class="underline underline-offset-4 hover:opacity-70 transition-opacity">
                    Institute Career Office →
                </a>
            </div>
        </div>
    </div>

    {{-- STICKY MINIMAL NAVIGATION HEADER --}}
    <header class="sticky top-0 z-40 bg-paper/95 backdrop-blur-md border-b border-hairline shadow-soft">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <x-brand />

            <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-muted">
                <a href="#opportunities" class="hover:text-ink transition-colors">Verified Jobs</a>
                <a href="#how-it-works" class="hover:text-ink transition-colors">How It Works</a>
                <a href="#eligibility" class="hover:text-ink transition-colors">Legal Eligibility</a>
            </nav>

            <div class="hidden md:flex items-center gap-3">
                @auth
                    <x-user-menu />
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-ink bg-paper border border-hairline rounded-lg shadow-soft hover:border-ink transition-all">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium bg-ink text-paper rounded-lg shadow-soft hover:bg-neutral-800 transition-all">
                        Register
                    </a>
                @endauth
            </div>

            <div class="md:hidden flex items-center gap-2">
                @auth
                    <x-user-menu compact />
                @endauth
                <button
                    type="button"
                    aria-label="Toggle Navigation"
                    class="p-2.5 rounded-lg border border-hairline bg-paper shadow-soft text-ink hover:border-ink transition-colors"
                    @click="mobileOpen = !mobileOpen"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16" />
                    </svg>
                </button>
            </div>
        </div>

        <div x-show="mobileOpen" x-cloak class="md:hidden border-t border-hairline bg-paper px-4 pt-4 pb-6 space-y-4 shadow-card">
            <div class="flex flex-col space-y-2 text-sm font-medium">
                <a href="#opportunities" @click="mobileOpen = false" class="py-2 px-2 rounded-md hover:bg-subtle text-ink">Verified Jobs</a>
                <a href="#how-it-works" @click="mobileOpen = false" class="py-2 px-2 rounded-md hover:bg-subtle text-ink">How It Works</a>
                <a href="#eligibility" @click="mobileOpen = false" class="py-2 px-2 rounded-md hover:bg-subtle text-ink">Legal Eligibility</a>
            </div>
            @guest
                <div class="pt-3 border-t border-hairline flex flex-col gap-2.5">
                    <a href="{{ route('login') }}" @click="mobileOpen = false" class="w-full py-2.5 text-center text-sm font-medium text-ink bg-paper border border-ink rounded-lg shadow-soft">
                        Login
                    </a>
                    <a href="{{ route('register') }}" @click="mobileOpen = false" class="w-full py-2.5 text-center text-sm font-medium bg-ink text-paper rounded-lg shadow-soft">
                        Register
                    </a>
                </div>
            @endguest
        </div>
    </header>

    {{-- HERO SECTION --}}
    <section class="border-b border-hairline bg-gradient-to-b from-subtle/70 to-paper">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 sm:py-20 lg:py-24">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-7 space-y-7">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full border border-hairline bg-paper shadow-soft text-xs font-mono">
                        <span class="w-1.5 h-1.5 rounded-full bg-ink"></span>
                        <span>MANAGED BY ACCREDITED UNIVERSITIES &amp; COLLEGES</span>
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-[54px] font-semibold tracking-tight leading-[1.08] text-ink">
                        Verified jobs for students &amp; alumni. Zero unverified noise.
                    </h1>

                    <p class="text-base sm:text-lg text-muted max-w-2xl leading-relaxed font-normal">
                        Every full-time, part-time, and visa-sponsored role is vetted by your institute. Employers connect exclusively with candidates whose academic enrollment and legal right to work are pre-verified.
                    </p>

                    <div class="pt-1 space-y-4">
                        <form
                            class="flex flex-col sm:flex-row gap-2.5 max-w-2xl p-2 bg-paper border border-hairline rounded-xl shadow-card focus-within:border-ink transition-colors"
                            @submit.prevent="document.getElementById('opportunities')?.scrollIntoView({ behavior: 'smooth' })"
                        >
                            <div class="relative flex-1 flex items-center">
                                <svg class="w-4 h-4 text-muted ml-3 shrink-0 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <circle cx="11" cy="11" r="8" />
                                    <path d="M21 21l-4.35-4.35" stroke-linecap="round" />
                                </svg>
                                <input
                                    type="text"
                                    x-model="search"
                                    placeholder="Search role, field of study, or location..."
                                    class="w-full px-3 py-2.5 text-sm bg-transparent placeholder:text-muted focus:outline-none"
                                />
                            </div>
                            <button type="submit" class="px-6 py-2.5 bg-ink text-paper text-sm font-medium rounded-lg shadow-soft hover:bg-neutral-800 transition-colors whitespace-nowrap">
                                Find Verified Jobs
                            </button>
                        </form>

                        <div class="flex flex-wrap items-center gap-2 text-xs">
                            <span class="font-mono text-muted mr-1">BROWSE:</span>
                            <button type="button" wire:click="setFilter('all')" class="px-3.5 py-1.5 rounded-lg bg-paper border border-hairline shadow-soft hover:border-ink transition-all font-medium">
                                All Roles
                            </button>
                            <button type="button" wire:click="setFilter('full-time')" class="px-3.5 py-1.5 rounded-lg bg-paper border border-hairline shadow-soft hover:border-ink transition-all font-medium">
                                Full-Time
                            </button>
                            <button type="button" wire:click="setFilter('part-time')" class="px-3.5 py-1.5 rounded-lg bg-paper border border-hairline shadow-soft hover:border-ink transition-all font-medium">
                                Part-Time
                            </button>
                            <button type="button" wire:click="setFilter('visa')" class="px-3.5 py-1.5 rounded-lg bg-paper border border-hairline shadow-soft hover:border-ink transition-all font-medium">
                                Job Sponsorship
                            </button>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-5">
                    <div class="border border-hairline rounded-2xl bg-paper shadow-card overflow-hidden">
                        <div class="px-6 py-4 border-b border-hairline bg-subtle flex items-center justify-between">
                            <span class="font-mono text-xs font-medium tracking-wider uppercase">Institutional Guarantee</span>
                            <span class="font-mono text-[11px] px-2.5 py-0.5 bg-ink text-paper rounded-md">VERIFIED</span>
                        </div>

                        <div class="divide-y divide-hairline">
                            <div class="p-6 flex items-start gap-4">
                                <div class="w-7 h-7 rounded-lg bg-subtle border border-hairline flex items-center justify-center font-mono text-xs font-medium shrink-0">01</div>
                                <div>
                                    <h2 class="text-sm font-semibold text-ink">Institute-Approved Employers</h2>
                                    <p class="text-xs text-muted mt-1 leading-relaxed">
                                        Every employer is reviewed by university career offices before posting roles to students or alumni.
                                    </p>
                                </div>
                            </div>
                            <div class="p-6 flex items-start gap-4">
                                <div class="w-7 h-7 rounded-lg bg-subtle border border-hairline flex items-center justify-center font-mono text-xs font-medium shrink-0">02</div>
                                <div>
                                    <h2 class="text-sm font-semibold text-ink">Legal Work-Right Pre-Screening</h2>
                                    <p class="text-xs text-muted mt-1 leading-relaxed">
                                        Student visa hour limits, post-study permits, and sponsorship eligibility are verified automatically.
                                    </p>
                                </div>
                            </div>
                            <div class="p-6 flex items-start gap-4">
                                <div class="w-7 h-7 rounded-lg bg-subtle border border-hairline flex items-center justify-center font-mono text-xs font-medium shrink-0">03</div>
                                <div>
                                    <h2 class="text-sm font-semibold text-ink">Eligible CVs Only for Employers</h2>
                                    <p class="text-xs text-muted mt-1 leading-relaxed">
                                        Hiring teams receive authenticated applications strictly from legally eligible candidates.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="px-6 py-3.5 bg-subtle border-t border-hairline flex items-center justify-between text-[11px] font-mono text-muted">
                            <span>REGISTRAR &amp; CAREER OFFICE SYNC</span>
                            <span class="text-ink font-medium">ACTIVE</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- PARTNER INSTITUTES BAR --}}
    <section class="border-b border-hairline bg-paper">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <span class="font-mono text-[11px] uppercase tracking-widest text-muted shrink-0">
                    Trusted Academic Network //
                </span>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 md:gap-10 w-full md:w-auto text-xs sm:text-sm font-medium text-ink/80">
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-ink"></span>
                        <span>Polytechnic Institute</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-ink"></span>
                        <span>School of Economics</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-ink"></span>
                        <span>Faculty of Engineering</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-ink"></span>
                        <span>Graduate Research Hub</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- LIVE VERIFIED OPPORTUNITIES SECTION --}}
    <section id="opportunities" class="border-b border-hairline py-16 sm:py-20 bg-subtle/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 pb-8">
                <div>
                    <span class="font-mono text-xs text-muted uppercase tracking-wider">Campus-Verified Feed</span>
                    <h2 class="text-2xl sm:text-3xl font-semibold tracking-tight mt-1">Verified Opportunities</h2>
                </div>

                <div class="flex flex-wrap gap-2" role="tablist">
                    <button type="button" wire:click="setFilter('all')" @class([$filterTabClass('all')])>
                        ALL ROLES ({{ $allJobsCount }})
                    </button>
                    <button type="button" wire:click="setFilter('full-time')" @class([$filterTabClass('full-time')])>
                        FULL-TIME
                    </button>
                    <button type="button" wire:click="setFilter('part-time')" @class([$filterTabClass('part-time')])>
                        PART-TIME
                    </button>
                    <button type="button" wire:click="setFilter('visa')" @class([$filterTabClass('visa')])>
                        JOB SPONSORSHIP
                    </button>
                </div>
            </div>

            @if ($filter !== 'all')
                <div class="py-3 px-4 mb-5 bg-paper border border-hairline rounded-xl shadow-soft flex items-center justify-between text-xs font-mono">
                    <span>
                        Showing {{ $jobs->count() }} verified role{{ $jobs->count() === 1 ? '' : 's' }}
                        @if ($filter === 'full-time') [Full-Time] @endif
                        @if ($filter === 'part-time') [Part-Time] @endif
                        @if ($filter === 'visa') [Sponsorship] @endif
                    </span>
                    <button type="button" wire:click="setFilter('all')" class="underline underline-offset-4 hover:opacity-70">
                        Clear filter
                    </button>
                </div>
            @endif

            <div
                x-show="search.trim() !== ''"
                x-cloak
                class="py-3 px-4 mb-5 bg-paper border border-hairline rounded-xl shadow-soft flex items-center justify-between text-xs font-mono"
            >
                <span x-text="`Filtering feed by “${search.trim()}”`"></span>
                <button type="button" @click="search = ''" class="underline underline-offset-4 hover:opacity-70">Clear search</button>
            </div>

            @if ($jobs->isNotEmpty())
                <div class="space-y-3.5">
                    @foreach ($jobs as $job)
                        @php
                            $searchBlob = strtolower(implode(' ', [
                                $job['title'],
                                $job['company'],
                                $job['location'],
                                $jobEligibility($job),
                                $job['type'],
                                $job['institute'],
                            ]));
                            $isSponsorship = $jobTypeKey($job) === 'Sponsorship';
                        @endphp
                        <article
                            wire:key="job-{{ $job['id'] }}"
                            wire:click="openJob('{{ $job['id'] }}')"
                            data-search="{{ $searchBlob }}"
                            x-show="!search.trim() || $el.dataset.search.includes(search.trim().toLowerCase())"
                            class="group p-5 sm:p-6 bg-paper border border-hairline rounded-2xl shadow-soft hover:shadow-card-hover hover:border-ink/40 transition-all duration-200 cursor-pointer flex flex-col lg:flex-row lg:items-center justify-between gap-4"
                        >
                            <div class="space-y-2 max-w-2xl">
                                <div class="flex flex-wrap items-center gap-2.5">
                                    <span @class([
                                        'font-mono text-[11px] px-2.5 py-0.5 rounded-md border',
                                        'bg-ink text-paper border-ink' => $isSponsorship,
                                        'bg-subtle text-ink border-hairline' => ! $isSponsorship,
                                    ])>
                                        {{ $jobTypeBadge($job) }}
                                    </span>
                                    <span class="font-mono text-[11px] text-muted">
                                        Verified by {{ $job['institute'] }}
                                    </span>
                                </div>
                                <h3 class="text-lg font-semibold text-ink group-hover:underline underline-offset-4">
                                    {{ $job['title'] }}
                                </h3>
                                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs sm:text-sm text-muted">
                                    <span class="font-medium text-ink">{{ $job['company'] }}</span>
                                    <span>•</span>
                                    <span>{{ $job['location'] }}</span>
                                    <span>•</span>
                                    <span class="font-mono text-xs text-ink">{{ $jobEligibility($job) }}</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between lg:justify-end gap-4 pt-3 lg:pt-0 border-t lg:border-t-0 border-hairline">
                                <span class="font-mono text-xs text-muted">{{ $job['id'] }}</span>
                                <span class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-mono border border-hairline rounded-lg bg-subtle group-hover:bg-ink group-hover:text-paper group-hover:border-ink transition-colors">
                                    VIEW DETAILS
                                    <span>→</span>
                                </span>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="py-16 text-center bg-paper border border-hairline rounded-2xl shadow-soft">
                    <p class="font-mono text-sm text-muted">No verified roles match your current search or filter.</p>
                    <button type="button" wire:click="setFilter('all')" @click="search = ''" class="mt-4 px-5 py-2.5 text-xs font-mono bg-ink text-paper rounded-lg shadow-soft hover:bg-neutral-800 transition-colors">
                        RESET FILTERS
                    </button>
                </div>
            @endif

            <div class="mt-8 p-5 bg-paper border border-hairline rounded-xl shadow-soft flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-muted">
                <p class="font-mono">Sign in with your institute credentials to unlock full compensation details and apply with one click.</p>
                <a href="{{ route('login') }}" class="px-5 py-2.5 bg-ink text-paper font-medium rounded-lg shadow-soft hover:bg-neutral-800 transition-colors shrink-0">
                    Login →
                </a>
            </div>
        </div>
    </section>

    {{-- HOW IT WORKS --}}
    <section id="how-it-works" class="border-b border-hairline py-16 sm:py-20 bg-paper">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
                <div>
                    <span class="font-mono text-xs text-muted uppercase tracking-wider">Simple Workflow</span>
                    <h2 class="text-2xl sm:text-3xl font-semibold tracking-tight mt-1">How Verification Works</h2>
                </div>

                <div class="inline-flex p-1 rounded-xl border border-hairline bg-subtle shadow-soft self-start">
                    <button
                        type="button"
                        wire:click="setAudience('student')"
                        @class([
                            'px-4 py-2 text-xs font-mono rounded-lg transition-all',
                            'bg-ink text-paper shadow-soft' => $audience === 'student',
                            'text-muted hover:text-ink' => $audience !== 'student',
                        ])
                    >
                        FOR STUDENTS &amp; ALUMNI
                    </button>
                    <button
                        type="button"
                        wire:click="setAudience('employer')"
                        @class([
                            'px-4 py-2 text-xs font-mono rounded-lg transition-all',
                            'bg-ink text-paper shadow-soft' => $audience === 'employer',
                            'text-muted hover:text-ink' => $audience !== 'employer',
                        ])
                    >
                        FOR EMPLOYERS
                    </button>
                </div>
            </div>

            @if ($audience === 'student')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-7 bg-paper border border-hairline rounded-2xl shadow-card flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between font-mono text-xs text-muted mb-6">
                                <span>STEP 01</span>
                                <span>CAMPUS SSO</span>
                            </div>
                            <h3 class="text-lg font-semibold mb-2">Log In with Institute ID</h3>
                            <p class="text-sm text-muted leading-relaxed">
                                Use your official university or alumni account. Your degree status and legal work authorization sync automatically from your institute.
                            </p>
                        </div>
                        <div class="mt-8 pt-4 border-t border-hairline font-mono text-[11px] text-ink">✓ Zero manual paperwork</div>
                    </div>
                    <div class="p-7 bg-paper border border-hairline rounded-2xl shadow-card flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between font-mono text-xs text-muted mb-6">
                                <span>STEP 02</span>
                                <span>CURATED FEED</span>
                            </div>
                            <h3 class="text-lg font-semibold mb-2">Explore Eligible Roles</h3>
                            <p class="text-sm text-muted leading-relaxed">
                                Browse full-time roles, term-time compliant part-time jobs, and licensed visa-sponsoring employers approved for your cohort.
                            </p>
                        </div>
                        <div class="mt-8 pt-4 border-t border-hairline font-mono text-[11px] text-ink">✓ 100% verified employers</div>
                    </div>
                    <div class="p-7 bg-paper border border-hairline rounded-2xl shadow-card flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between font-mono text-xs text-muted mb-6">
                                <span>STEP 03</span>
                                <span>VERIFIED SEAL</span>
                            </div>
                            <h3 class="text-lg font-semibold mb-2">Apply with Institutional Trust</h3>
                            <p class="text-sm text-muted leading-relaxed">
                                Your CV reaches employers stamped with official institute verification of your graduation status and legal right to work.
                            </p>
                        </div>
                        <div class="mt-8 pt-4 border-t border-hairline font-mono text-[11px] text-ink">✓ Direct recruiter visibility</div>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-7 bg-paper border border-hairline rounded-2xl shadow-card flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between font-mono text-xs text-muted mb-6">
                                <span>STEP 01</span>
                                <span>EMPLOYER KYB</span>
                            </div>
                            <h3 class="text-lg font-semibold mb-2">Get Approved by Institutes</h3>
                            <p class="text-sm text-muted leading-relaxed">
                                Verify your organization once with partner university career offices to publish roles directly to targeted faculties and alumni.
                            </p>
                        </div>
                        <div class="mt-8 pt-4 border-t border-hairline font-mono text-[11px] text-ink">✓ High-trust campus presence</div>
                    </div>
                    <div class="p-7 bg-paper border border-hairline rounded-2xl shadow-card flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between font-mono text-xs text-muted mb-6">
                                <span>STEP 02</span>
                                <span>ELIGIBILITY FILTER</span>
                            </div>
                            <h3 class="text-lg font-semibold mb-2">Set Work-Right Requirements</h3>
                            <p class="text-sm text-muted leading-relaxed">
                                Specify unrestricted work authorization, student part-time permits, or visa sponsorship availability. Ineligible applicants are screened out automatically.
                            </p>
                        </div>
                        <div class="mt-8 pt-4 border-t border-hairline font-mono text-[11px] text-ink">✓ Zero compliance friction</div>
                    </div>
                    <div class="p-7 bg-paper border border-hairline rounded-2xl shadow-card flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between font-mono text-xs text-muted mb-6">
                                <span>STEP 03</span>
                                <span>VERIFIED CVs</span>
                            </div>
                            <h3 class="text-lg font-semibold mb-2">Receive Ready-to-Hire CVs</h3>
                            <p class="text-sm text-muted leading-relaxed">
                                Every CV you receive comes from an institute-verified student or alumnus who is legally eligible to start in your role.
                            </p>
                        </div>
                        <div class="mt-8 pt-4 border-t border-hairline font-mono text-[11px] text-ink">✓ Faster, compliant hiring</div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- LEGAL ELIGIBILITY GUARANTEE SECTION --}}
    <section id="eligibility" class="border-b border-hairline py-16 sm:py-20 bg-subtle/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                <div class="lg:col-span-5 space-y-4">
                    <span class="font-mono text-xs text-muted uppercase tracking-wider">Legal Clarity</span>
                    <h2 class="text-2xl sm:text-3xl font-semibold tracking-tight">
                        Built around statutory work eligibility.
                    </h2>
                    <p class="text-sm text-muted leading-relaxed">
                        Both students and employers save weeks of back-and-forth because work permits, term-time hour limits, and sponsorship licenses are verified upfront by the institute.
                    </p>
                </div>

                <div class="lg:col-span-7">
                    <div class="bg-paper border border-hairline rounded-2xl shadow-card divide-y divide-hairline overflow-hidden">
                        <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <h3 class="text-sm font-semibold">Term-Time Part-Time Compliance</h3>
                                <p class="text-xs text-muted mt-1">Matches enrolled students strictly with roles respecting statutory weekly hour caps.</p>
                            </div>
                            <span class="font-mono text-[11px] px-3 py-1 bg-subtle border border-hairline rounded-md self-start sm:self-center shrink-0">
                                STUDENT VISA SAFE
                            </span>
                        </div>
                        <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <h3 class="text-sm font-semibold">Post-Study Graduate Permits</h3>
                                <p class="text-xs text-muted mt-1">Confirms alumni work authorization for immediate full-time onboarding.</p>
                            </div>
                            <span class="font-mono text-[11px] px-3 py-1 bg-subtle border border-hairline rounded-md self-start sm:self-center shrink-0">
                                ALUMNI VERIFIED
                            </span>
                        </div>
                        <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <h3 class="text-sm font-semibold">Licensed Visa Sponsorship</h3>
                                <p class="text-xs text-muted mt-1">Clearly badges employers holding active sponsorship licenses vetted by the institute.</p>
                            </div>
                            <span class="font-mono text-[11px] px-3 py-1 bg-ink text-paper rounded-md self-start sm:self-center shrink-0">
                                SPONSOR LICENSED
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- MINIMALIST CALL TO ACTION --}}
    <section class="py-16 sm:py-24 bg-ink text-paper">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl space-y-6">
                <span class="font-mono text-xs text-neutral-400 uppercase tracking-widest">studentsjob.work</span>
                <h2 class="text-3xl sm:text-4xl font-semibold tracking-tight leading-tight">
                    Access your institute’s verified career portal.
                </h2>
                <p class="text-sm sm:text-base text-neutral-400 leading-relaxed">
                    Log in with your academic credentials to browse verified opportunities, or register your company to hire legally eligible students and alumni.
                </p>
                <div class="pt-2 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('login') }}" class="px-6 py-3 text-center bg-paper text-ink text-sm font-medium rounded-lg shadow-soft hover:bg-neutral-200 transition-colors">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="px-6 py-3 text-center bg-transparent text-paper border border-neutral-700 text-sm font-medium rounded-lg hover:border-paper transition-colors">
                        Register
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="bg-paper border-t border-hairline py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 pb-8 border-b border-hairline">
                <div>
                    <span class="font-semibold text-base tracking-tight">studentsjob.work</span>
                    <p class="text-xs text-muted mt-1">Institute-Managed Recruitment &amp; Legal Work-Eligibility Network.</p>
                </div>
                <div class="flex flex-wrap gap-6 text-xs font-mono text-muted">
                    <a href="#opportunities" class="hover:text-ink transition-colors">VERIFIED JOBS</a>
                    <a href="#how-it-works" class="hover:text-ink transition-colors">HOW IT WORKS</a>
                    <a href="#eligibility" class="hover:text-ink transition-colors">ELIGIBILITY</a>
                    <a href="{{ route('login') }}" class="hover:text-ink transition-colors">INSTITUTE ADMIN</a>
                </div>
            </div>

            <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-muted font-mono">
                <span>© {{ date('Y') }} studentsjob.work. All rights reserved.</span>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-ink"></span>
                    <span>ALL INSTITUTE NODES OPERATIONAL</span>
                </div>
            </div>
        </div>
    </footer>

    {{-- JOB DETAIL SLIDE-OVER DRAWER --}}
    @if ($jobDrawerOpen && $selectedJob)
        @php
            $drawerType = $jobTypeKey($selectedJob);
            $drawerTypeLabel = $jobTypeBadge($selectedJob);
        @endphp
        <div class="fixed inset-0 z-50" aria-modal="true" role="dialog">
            <div
                wire:click="$set('jobDrawerOpen', false)"
                class="absolute inset-0 bg-black/35 backdrop-blur-[2px]"
                aria-hidden="true"
            ></div>
            <div class="fixed inset-y-0 right-0 z-10 max-w-lg w-full bg-paper border-l border-hairline shadow-modal flex flex-col justify-between overflow-y-auto">
                <div class="p-6 sm:p-8 space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-hairline">
                        <span class="font-mono text-xs px-2.5 py-1 bg-ink text-paper rounded-md">{{ $drawerTypeLabel }}</span>
                        <button type="button" wire:click="$set('jobDrawerOpen', false)" aria-label="Close Details" class="p-2 rounded-lg border border-hairline shadow-soft hover:border-ink transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div>
                        <p class="font-mono text-xs text-muted uppercase tracking-wider">{{ $selectedJob['company'] }}</p>
                        <h3 class="text-2xl font-semibold tracking-tight mt-1">{{ $selectedJob['title'] }}</h3>
                        <p class="text-sm text-muted mt-1">{{ $selectedJob['location'] }} • {{ $drawerType }}</p>
                    </div>

                    <div class="p-5 bg-subtle border border-hairline rounded-xl shadow-soft space-y-3 text-xs">
                        <div class="flex items-center justify-between gap-2">
                            <span class="font-mono text-muted">VERIFIED BY</span>
                            <span class="font-mono font-medium text-ink text-right">{{ $selectedJob['institute'] }} Career Office</span>
                        </div>
                        <div class="flex items-center justify-between gap-2">
                            <span class="font-mono text-muted">LEGAL ELIGIBILITY</span>
                            <span class="font-mono font-medium text-ink text-right">{{ $jobEligibility($selectedJob) }}</span>
                        </div>
                        <div class="flex items-center justify-between gap-2">
                            <span class="font-mono text-muted">COMPENSATION</span>
                            <span class="font-mono font-medium text-ink text-right">{{ $selectedJob['salary'] }}</span>
                        </div>
                    </div>

                    <div class="space-y-2.5">
                        <h4 class="font-mono text-xs uppercase tracking-wider text-muted">Role Overview</h4>
                        <p class="text-sm text-ink/90 leading-relaxed">{{ $selectedJob['summary'] }}</p>
                    </div>

                    <div class="space-y-2">
                        <h4 class="font-mono text-xs uppercase tracking-wider text-muted">Eligible Institute Cohorts</h4>
                        <p class="text-sm text-ink/90">
                            {{ $selectedJob['eligible'] ? 'Enrolled students and verified alumni meeting institute hour and visa policies.' : 'Candidates with unrestricted work rights or active sponsorship pathways.' }}
                        </p>
                    </div>
                </div>

                <div class="p-6 border-t border-hairline bg-subtle space-y-3">
                    <a
                        href="{{ route('login') }}"
                        class="block w-full py-3 text-center bg-ink text-paper text-sm font-medium rounded-lg shadow-soft hover:bg-neutral-800 transition-colors"
                    >
                        Login to Apply
                    </a>
                    <p class="text-[11px] font-mono text-center text-muted">
                        Your legal work right is attached automatically upon institutional login.
                    </p>
                </div>
            </div>
        </div>
    @endif

</div>