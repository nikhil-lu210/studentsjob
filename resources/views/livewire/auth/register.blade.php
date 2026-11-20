<x-layouts::guest>
    <div
        class="space-y-5 -mx-4 sm:mx-0 w-[calc(100%+2rem)] sm:w-full max-w-[540px] sm:max-w-none"
        x-data="{
            role: 'student',
            showPassword: false,
            showConfirm: false,
            student: {
                name: @js(old('name', '')),
                id: '',
                email: @js(old('email', '')),
                institute: 'Polytechnic Institute',
                cohort: 'Enrolled Master\'s / PhD Student',
                workAuth: 'Student Visa — Term-Time Compliant (Max 20 hrs/wk)',
            },
            employer: {
                company: '',
                regId: '',
                email: '',
                sponsor: 'Licensed Visa Sponsor (Full-Time & Relocation)',
            },
            institute: {
                officialName: '',
                adminName: '',
                domain: '',
                email: '',
            },
            password: '',
            passwordConfirmation: '',
            consent: true,
            submitLabel() {
                return {
                    student: '{{ __('Verify & Create Student Account') }}',
                    employer: '{{ __('Submit Employer for KYB Approval') }}',
                    institute: '{{ __('Register Accredited Career Office') }}',
                }[this.role];
            },
            resolvedName() {
                if (this.role === 'student') return this.student.name;
                if (this.role === 'employer') return this.employer.company;
                return this.institute.adminName;
            },
            resolvedEmail() {
                if (this.role === 'student') return this.student.email;
                if (this.role === 'employer') return this.employer.email;
                return this.institute.email;
            },
            fillSample(role) {
                this.role = role;
                this.consent = true;
                this.password = 'VerifiedPass2026!';
                this.passwordConfirmation = 'VerifiedPass2026!';
                if (role === 'student') {
                    this.student.name = 'Alex Mercer';
                    this.student.id = 'STU-2026-8492';
                    this.student.email = 'alex.mercer@polytechnic.edu';
                    this.student.institute = 'Polytechnic Institute';
                    this.student.cohort = 'Enrolled Master\'s / PhD Student';
                    this.student.workAuth = 'Student Visa — Term-Time Compliant (Max 20 hrs/wk)';
                } else if (role === 'employer') {
                    this.employer.company = 'Helvetia Financial Systems';
                    this.employer.regId = 'CHE-114.920.381';
                    this.employer.email = 'talent@helvetiasystems.ch';
                    this.employer.sponsor = 'Licensed Visa Sponsor (Full-Time & Relocation)';
                } else {
                    this.institute.officialName = 'Polytechnic Institute of Zurich';
                    this.institute.adminName = 'Dr. Elena Vance';
                    this.institute.domain = 'polytechnic.edu';
                    this.institute.email = 'careers-office@polytechnic.edu';
                }
            },
            tabClass(active) {
                return active
                    ? 'py-2.5 px-2 text-xs font-mono rounded-lg bg-ink text-paper shadow-soft transition-all text-center'
                    : 'py-2.5 px-2 text-xs font-mono rounded-lg text-muted hover:text-ink transition-all text-center';
            },
        }"
    >
        <div class="bg-paper border border-hairline rounded-2xl shadow-card overflow-hidden">
            <div class="px-6 sm:px-8 py-3.5 bg-subtle border-b border-hairline flex items-center justify-between">
                <div class="flex items-center gap-2 font-mono text-[11px] text-muted">
                    <span class="w-1.5 h-1.5 rounded-full bg-ink"></span>
                    <span>{{ __('VERIFIED ACCOUNT ONBOARDING') }}</span>
                </div>
                <span class="font-mono text-[10px] px-2 py-0.5 border border-hairline bg-paper rounded text-ink">
                    {{ __('STEP 01 / 02') }}
                </span>
            </div>

            <div class="p-6 sm:p-8 space-y-6">
                <div class="space-y-1.5">
                    <h1 class="text-2xl font-semibold tracking-tight text-ink">{{ __('Create your verified account') }}</h1>
                    <p class="text-xs sm:text-sm text-muted leading-relaxed">
                        {{ __('Select your role below. Every profile is verified through accredited institutes to guarantee authentic opportunities and legal work eligibility.') }}
                    </p>
                </div>

                <div class="grid grid-cols-3 p-1 rounded-xl border border-hairline bg-subtle shadow-soft gap-1" role="tablist">
                    <button type="button" role="tab" x-on:click="role = 'student'" :class="tabClass(role === 'student')">
                        {{ __('STUDENT / ALUMNI') }}
                    </button>
                    <button type="button" role="tab" x-on:click="role = 'employer'" :class="tabClass(role === 'employer')">
                        {{ __('EMPLOYER') }}
                    </button>
                    <button type="button" role="tab" x-on:click="role = 'institute'" :class="tabClass(role === 'institute')">
                        {{ __('INSTITUTE') }}
                    </button>
                </div>

                <x-auth-session-status
                    class="p-3.5 rounded-xl border border-hairline bg-subtle text-xs font-mono text-ink text-center"
                    :status="session('status')"
                />

                @if ($errors->any())
                    <div class="p-3.5 rounded-xl border border-hairline bg-subtle text-xs font-mono text-ink">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('register.store') }}" class="space-y-4">
                    @csrf

                    <input type="hidden" name="name" :value="resolvedName()">
                    <input type="hidden" name="email" :value="resolvedEmail()">

                    <div x-show="role === 'student'" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="block font-mono text-[11px] uppercase tracking-wider text-muted">
                                    {{ __('Full Legal Name') }}
                                </label>
                                <input
                                    type="text"
                                    x-model="student.name"
                                    placeholder="Alex Mercer"
                                    class="w-full px-3.5 py-2.5 text-sm bg-paper border border-hairline rounded-lg shadow-soft placeholder:text-neutral-400 focus:border-ink focus:outline-none transition-colors"
                                />
                            </div>
                            <div class="space-y-1.5">
                                <label class="block font-mono text-[11px] uppercase tracking-wider text-muted">
                                    {{ __('Student / Alumni ID') }}
                                </label>
                                <input
                                    type="text"
                                    x-model="student.id"
                                    placeholder="STU-2026-8492"
                                    class="w-full px-3.5 py-2.5 text-sm bg-paper border border-hairline rounded-lg shadow-soft placeholder:text-neutral-400 focus:border-ink focus:outline-none transition-colors"
                                />
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between">
                                <label class="block font-mono text-[11px] uppercase tracking-wider text-muted">
                                    {{ __('Official University / Alumni Email') }}
                                </label>
                                <span class="font-mono text-[10px] text-muted">.EDU / .AC.*</span>
                            </div>
                            <input
                                type="email"
                                x-model="student.email"
                                placeholder="alex.mercer@polytechnic.edu"
                                class="w-full px-3.5 py-2.5 text-sm bg-paper border border-hairline rounded-lg shadow-soft placeholder:text-neutral-400 focus:border-ink focus:outline-none transition-colors @error('email') border-red-500 @enderror"
                            />
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="block font-mono text-[11px] uppercase tracking-wider text-muted">
                                    {{ __('Affiliated Institute') }}
                                </label>
                                <select x-model="student.institute" class="w-full px-3.5 py-2.5 text-sm bg-paper border border-hairline rounded-lg shadow-soft focus:border-ink focus:outline-none transition-colors">
                                    <option>{{ __('Polytechnic Institute') }}</option>
                                    <option>{{ __('School of Economics') }}</option>
                                    <option>{{ __('Faculty of Engineering') }}</option>
                                    <option>{{ __('Graduate Research Hub') }}</option>
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="block font-mono text-[11px] uppercase tracking-wider text-muted">
                                    {{ __('Academic Status') }}
                                </label>
                                <select x-model="student.cohort" class="w-full px-3.5 py-2.5 text-sm bg-paper border border-hairline rounded-lg shadow-soft focus:border-ink focus:outline-none transition-colors">
                                    <option>{{ __('Enrolled Student (BSc/BA)') }}</option>
                                    <option>{{ __('Enrolled Graduate (MSc/PhD)') }}</option>
                                    <option>{{ __('Verified Alumni') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block font-mono text-[11px] uppercase tracking-wider text-muted">
                                {{ __('Legal Work Eligibility (Verified by Institute)') }}
                            </label>
                            <select x-model="student.workAuth" class="w-full px-3.5 py-2.5 text-sm bg-paper border border-hairline rounded-lg shadow-soft focus:border-ink focus:outline-none transition-colors">
                                <option>{{ __('Student Visa — Term-Time Compliant (Max 20 hrs/wk)') }}</option>
                                <option>{{ __('Post-Study Graduate Work Permit (Full-Time)') }}</option>
                                <option>{{ __('Unrestricted Right to Work (Citizen / PR)') }}</option>
                                <option>{{ __('Requires Employer Visa Sponsorship') }}</option>
                            </select>
                        </div>
                    </div>

                    <div x-show="role === 'employer'" x-cloak class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="block font-mono text-[11px] uppercase tracking-wider text-muted">
                                    {{ __('Registered Company Name') }}
                                </label>
                                <input type="text" x-model="employer.company" placeholder="Helvetia Financial Systems" class="w-full px-3.5 py-2.5 text-sm bg-paper border border-hairline rounded-lg shadow-soft placeholder:text-neutral-400 focus:border-ink focus:outline-none transition-colors" />
                            </div>
                            <div class="space-y-1.5">
                                <label class="block font-mono text-[11px] uppercase tracking-wider text-muted">
                                    {{ __('Business Reg / Tax ID (KYB)') }}
                                </label>
                                <input type="text" x-model="employer.regId" placeholder="CHE-114.920.381" class="w-full px-3.5 py-2.5 text-sm bg-paper border border-hairline rounded-lg shadow-soft placeholder:text-neutral-400 focus:border-ink focus:outline-none transition-colors" />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="block font-mono text-[11px] uppercase tracking-wider text-muted">
                                    {{ __('Corporate Work Email') }}
                                </label>
                                <input type="email" x-model="employer.email" placeholder="talent@helvetiasystems.ch" class="w-full px-3.5 py-2.5 text-sm bg-paper border border-hairline rounded-lg shadow-soft placeholder:text-neutral-400 focus:border-ink focus:outline-none transition-colors @error('email') border-red-500 @enderror" />
                            </div>
                            <div class="space-y-1.5">
                                <label class="block font-mono text-[11px] uppercase tracking-wider text-muted">
                                    {{ __('Visa Sponsorship Status') }}
                                </label>
                                <select x-model="employer.sponsor" class="w-full px-3.5 py-2.5 text-sm bg-paper border border-hairline rounded-lg shadow-soft focus:border-ink focus:outline-none transition-colors">
                                    <option>{{ __('Licensed Visa Sponsor') }}</option>
                                    <option>{{ __('Work-Permit / Student Visa Only') }}</option>
                                    <option>{{ __('Both Sponsored & Permit-Ready') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div x-show="role === 'institute'" x-cloak class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="block font-mono text-[11px] uppercase tracking-wider text-muted">
                                {{ __('Accredited University / College Name') }}
                            </label>
                            <input type="text" x-model="institute.officialName" placeholder="Polytechnic Institute of Zurich" class="w-full px-3.5 py-2.5 text-sm bg-paper border border-hairline rounded-lg shadow-soft placeholder:text-neutral-400 focus:border-ink focus:outline-none transition-colors" />
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="block font-mono text-[11px] uppercase tracking-wider text-muted">
                                    {{ __('Career Office Representative') }}
                                </label>
                                <input type="text" x-model="institute.adminName" placeholder="Dr. Elena Vance" class="w-full px-3.5 py-2.5 text-sm bg-paper border border-hairline rounded-lg shadow-soft placeholder:text-neutral-400 focus:border-ink focus:outline-none transition-colors @error('name') border-red-500 @enderror" />
                            </div>
                            <div class="space-y-1.5">
                                <label class="block font-mono text-[11px] uppercase tracking-wider text-muted">
                                    {{ __('Official Student Email Domain') }}
                                </label>
                                <input type="text" x-model="institute.domain" placeholder="polytechnic.edu" class="w-full px-3.5 py-2.5 text-sm bg-paper border border-hairline rounded-lg shadow-soft placeholder:text-neutral-400 focus:border-ink focus:outline-none transition-colors" />
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block font-mono text-[11px] uppercase tracking-wider text-muted">
                                {{ __('Official Career Office / Registrar Email') }}
                            </label>
                            <input type="email" x-model="institute.email" placeholder="careers-office@polytechnic.edu" class="w-full px-3.5 py-2.5 text-sm bg-paper border border-hairline rounded-lg shadow-soft placeholder:text-neutral-400 focus:border-ink focus:outline-none transition-colors @error('email') border-red-500 @enderror" />
                        </div>
                    </div>

                    <div class="space-y-1.5 pt-1">
                        <div class="flex items-center justify-between">
                            <label for="password" class="block font-mono text-[11px] uppercase tracking-wider text-muted">
                                {{ __('Create Password') }}
                            </label>
                            <span class="font-mono text-[10px] text-muted">{{ __('MIN. 8 CHARACTERS') }}</span>
                        </div>
                        <div class="relative">
                            <input
                                id="password"
                                name="password"
                                :type="showPassword ? 'text' : 'password'"
                                required
                                autocomplete="new-password"
                                x-model="password"
                                placeholder="••••••••••••"
                                class="w-full pl-3.5 pr-11 py-2.5 text-sm bg-paper border border-hairline rounded-lg shadow-soft placeholder:text-neutral-400 focus:border-ink focus:outline-none transition-colors @error('password') border-red-500 @enderror"
                            />
                            <button type="button" x-on:click="showPassword = !showPassword" aria-label="{{ __('Toggle password visibility') }}" class="absolute inset-y-0 right-0 px-3 flex items-center text-muted hover:text-ink transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-xs font-mono text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label for="password_confirmation" class="block font-mono text-[11px] uppercase tracking-wider text-muted">
                            {{ __('Confirm Password') }}
                        </label>
                        <div class="relative">
                            <input
                                id="password_confirmation"
                                name="password_confirmation"
                                :type="showConfirm ? 'text' : 'password'"
                                required
                                autocomplete="new-password"
                                x-model="passwordConfirmation"
                                placeholder="••••••••••••"
                                class="w-full pl-3.5 pr-11 py-2.5 text-sm bg-paper border border-hairline rounded-lg shadow-soft placeholder:text-neutral-400 focus:border-ink focus:outline-none transition-colors"
                            />
                            <button type="button" x-on:click="showConfirm = !showConfirm" aria-label="{{ __('Toggle password visibility') }}" class="absolute inset-y-0 right-0 px-3 flex items-center text-muted hover:text-ink transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <label class="flex items-start gap-2.5 pt-1 cursor-pointer select-none">
                        <input type="checkbox" x-model="consent" required class="mt-0.5 shrink-0" />
                        <span class="text-xs text-muted leading-relaxed">
                            {{ __('I confirm that the provided academic or corporate credentials are accurate and consent to institutional work-eligibility verification.') }}
                        </span>
                    </label>

                    <button
                        type="submit"
                        data-test="register-user-button"
                        class="w-full py-3 px-4 bg-ink text-paper text-sm font-medium rounded-lg shadow-soft hover:bg-neutral-800 transition-all flex items-center justify-center gap-2"
                    >
                        <span x-text="submitLabel()"></span>
                        <span>→</span>
                    </button>
                </form>

                <div class="pt-4 border-t border-hairline flex items-center justify-between text-xs">
                    <span class="text-muted">{{ __('Already have a verified account?') }}</span>
                    <a href="{{ route('login') }}" wire:navigate class="font-mono font-medium text-ink underline underline-offset-4 hover:opacity-75 transition-opacity">
                        {{ __('Sign in here →') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-paper border border-hairline rounded-xl p-3.5 shadow-soft space-y-2.5">
            <div class="flex items-center justify-between text-[11px] font-mono text-muted">
                <span>{{ __('QUICK-FILL SAMPLE DATA:') }}</span>
                <span>{{ __('1-CLICK TEST') }}</span>
            </div>
            <div class="grid grid-cols-3 gap-2">
                <button type="button" x-on:click="fillSample('student')" class="py-1.5 px-2 text-[11px] font-mono bg-subtle border border-hairline rounded-lg hover:border-ink hover:bg-paper transition-all text-center truncate">
                    {{ __('Student Sample') }}
                </button>
                <button type="button" x-on:click="fillSample('employer')" class="py-1.5 px-2 text-[11px] font-mono bg-subtle border border-hairline rounded-lg hover:border-ink hover:bg-paper transition-all text-center truncate">
                    {{ __('Employer Sample') }}
                </button>
                <button type="button" x-on:click="fillSample('institute')" class="py-1.5 px-2 text-[11px] font-mono bg-subtle border border-hairline rounded-lg hover:border-ink hover:bg-paper transition-all text-center truncate">
                    {{ __('Institute Sample') }}
                </button>
            </div>
        </div>
    </div>
</x-layouts::guest>