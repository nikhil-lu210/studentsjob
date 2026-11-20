<x-layouts::guest>
    <div
        class="space-y-5"
        x-data="{
            recovery: false,
            ssoOpen: false,
            showPassword: false,
            roleBadge: null,
            email: @js(old('email', '')),
            password: '',
            resolveRole(email) {
                const normalized = (email || '').trim().toLowerCase();
                if (! normalized.includes('@')) {
                    return null;
                }

                const local = normalized.split('@')[0] || '';
                const domain = normalized.split('@')[1] || '';

                if (local === 'admin') {
                    return 'PLATFORM SUPERADMIN';
                }
                if (local === 'institute' || local.startsWith('careers') || local === 'registrar') {
                    return 'INSTITUTE CAREER ADMIN';
                }
                if (local === 'employer') {
                    return 'VERIFIED EMPLOYER';
                }
                if (
                    local === 'student' ||
                    local === 'alumni' ||
                    domain.endsWith('.edu') ||
                    domain.includes('.ac.') ||
                    domain.includes('university') ||
                    domain.includes('polytechnic')
                ) {
                    return 'STUDENT & ALUMNI';
                }

                return 'VERIFIED EMPLOYER';
            },
            onEmailInput() {
                this.roleBadge = this.resolveRole(this.email);
            },
            fillDemo(role) {
                this.recovery = false;
                const demos = {
                    student: { email: 'student@studentsjob.work', password: 'password' },
                    employer: { email: 'employer@studentsjob.work', password: 'password' },
                    institute: { email: 'institute@studentsjob.work', password: 'password' },
                    admin: { email: 'admin@studentsjob.work', password: 'password' },
                };
                const demo = demos[role] || demos.student;
                this.email = demo.email;
                this.password = demo.password;
                this.onEmailInput();
                this.$nextTick(() => {
                    document.getElementById('email')?.dispatchEvent(new Event('input', { bubbles: true }));
                    document.getElementById('password')?.dispatchEvent(new Event('input', { bubbles: true }));
                });
            },
        }"
        x-init="onEmailInput()"
    >
        <div class="bg-paper border border-hairline rounded-2xl shadow-card overflow-hidden">
            <div class="px-6 sm:px-8 py-3.5 bg-subtle border-b border-hairline flex items-center justify-between">
                <div class="flex items-center gap-2 font-mono text-[11px] text-muted">
                    <span class="w-1.5 h-1.5 rounded-full bg-ink"></span>
                    <span>UNIFIED ACCESS PORTAL</span>
                </div>
                <span class="font-mono text-[10px] px-2 py-0.5 border border-hairline bg-paper rounded text-ink">
                    ROLE-ROUTED
                </span>
            </div>

            {{-- Standard sign-in --}}
            <div x-show="!recovery" x-cloak class="p-6 sm:p-8 space-y-6">
                <div class="space-y-1.5">
                    <h1 class="text-2xl font-semibold tracking-tight text-ink">{{ __('Sign in to your account') }}</h1>
                    <p class="text-xs sm:text-sm text-muted leading-relaxed">
                        {{ __('Enter your credentials. You will be redirected automatically to your Student, Employer, or Institute dashboard.') }}
                    </p>
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

                <x-passkey-verify
                    :label="__('Sign in with a passkey')"
                    :separator="__('OR')"
                />

                <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
                    @csrf

                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between gap-2">
                            <label for="email" class="block font-mono text-[11px] uppercase tracking-wider text-muted">
                                {{ __('Work or Institutional Email') }}
                            </label>
                            <span
                                x-show="roleBadge"
                                x-text="roleBadge"
                                x-cloak
                                class="font-mono text-[10px] px-2 py-0.5 rounded bg-ink text-paper shrink-0"
                            ></span>
                        </div>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            required
                            autofocus
                            autocomplete="email"
                            placeholder="name@university.edu or name@company.com"
                            x-model="email"
                            x-on:input="onEmailInput()"
                            class="w-full px-3.5 py-2.5 text-sm bg-paper border border-hairline rounded-lg shadow-soft placeholder:text-neutral-400 focus:border-ink focus:outline-none transition-colors @error('email') border-red-500 @enderror"
                        />
                        @error('email')
                            <p class="text-xs font-mono text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <label for="password" class="block font-mono text-[11px] uppercase tracking-wider text-muted">
                                {{ __('Password') }}
                            </label>
                            @if (Route::has('password.email'))
                                <button
                                    type="button"
                                    x-on:click="recovery = true"
                                    class="font-mono text-[11px] text-muted hover:text-ink underline underline-offset-4 transition-colors"
                                >
                                    {{ __('Forgot password?') }}
                                </button>
                            @endif
                        </div>
                        <div class="relative">
                            <input
                                id="password"
                                name="password"
                                :type="showPassword ? 'text' : 'password'"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••••••"
                                x-model="password"
                                class="w-full pl-3.5 pr-11 py-2.5 text-sm bg-paper border border-hairline rounded-lg shadow-soft placeholder:text-neutral-400 focus:border-ink focus:outline-none transition-colors @error('password') border-red-500 @enderror"
                            />
                            <button
                                type="button"
                                x-on:click="showPassword = !showPassword"
                                aria-label="{{ __('Toggle password visibility') }}"
                                class="absolute inset-y-0 right-0 px-3 flex items-center text-muted hover:text-ink transition-colors"
                            >
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

                    <div class="flex items-center justify-between pt-0.5">
                        <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                            <input
                                type="checkbox"
                                name="remember"
                                value="1"
                                @checked(old('remember'))
                                class="mt-0.5"
                            />
                            <span class="text-xs text-muted">{{ __('Keep me signed in for 14 days') }}</span>
                        </label>
                    </div>

                    <button
                        type="submit"
                        data-test="login-button"
                        class="w-full py-3 px-4 bg-ink text-paper text-sm font-medium rounded-lg shadow-soft hover:bg-neutral-800 transition-all flex items-center justify-center gap-2"
                    >
                        <span>{{ __('Continue to Dashboard') }}</span>
                        <span>→</span>
                    </button>
                </form>

                <div class="relative flex items-center py-1">
                    <div class="grow border-t border-hairline"></div>
                    <span class="shrink mx-3 font-mono text-[10px] uppercase tracking-widest text-muted">{{ __('OR') }}</span>
                    <div class="grow border-t border-hairline"></div>
                </div>

                <div class="space-y-3">
                    <button
                        type="button"
                        x-on:click="ssoOpen = !ssoOpen"
                        class="w-full py-2.5 px-4 bg-paper text-ink text-sm font-medium border border-hairline rounded-lg shadow-soft hover:border-ink transition-all flex items-center justify-center gap-2.5"
                    >
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3L2 8l10 5 10-5-10-5Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 10.5v5.5c0 1.7 2.7 3 6 3s6-1.3 6-3v-5.5" />
                        </svg>
                        <span>{{ __('Continue with Institute SSO') }}</span>
                    </button>

                    <div
                        x-show="ssoOpen"
                        x-cloak
                        class="p-4 bg-subtle border border-hairline rounded-xl space-y-3"
                    >
                        <label for="institute_sso" class="block font-mono text-[11px] uppercase text-muted">
                            {{ __('Select Accredited Institute') }}
                        </label>
                        <select
                            id="institute_sso"
                            class="w-full px-3 py-2 text-xs font-medium bg-paper border border-hairline rounded-lg shadow-soft focus:border-ink focus:outline-none"
                        >
                            <option value="polytechnic.edu">{{ __('Polytechnic Institute (Student / Staff SSO)') }}</option>
                            <option value="economics.edu">{{ __('School of Economics (Academic SSO)') }}</option>
                            <option value="engineering.ac">{{ __('Faculty of Engineering (Campus ID)') }}</option>
                            <option value="gradhub.edu">{{ __('Graduate Research Hub (Alumni & PhD SSO)') }}</option>
                        </select>
                        <p class="text-[11px] font-mono text-muted leading-relaxed">
                            {{ __('Campus SSO integration is coming soon. Use email sign-in for verified access today.') }}
                        </p>
                    </div>
                </div>

                <div class="pt-4 border-t border-hairline flex items-center justify-between text-xs">
                    <span class="text-muted">{{ __('Don\'t have a verified account?') }}</span>
                    <a href="{{ route('register') }}" wire:navigate class="font-mono font-medium text-ink underline underline-offset-4 hover:opacity-75 transition-opacity">
                        {{ __('Create account →') }}
                    </a>
                </div>
            </div>

            {{-- Inline recovery --}}
            <div x-show="recovery" x-cloak class="p-6 sm:p-8 space-y-6">
                <div class="space-y-1.5">
                    <span class="font-mono text-[11px] text-muted uppercase">{{ __('Account Recovery') }}</span>
                    <h2 class="text-xl font-semibold tracking-tight text-ink">{{ __('Reset your password') }}</h2>
                    <p class="text-xs text-muted leading-relaxed">
                        {{ __('Enter your registered email address. If you use Campus SSO, password resets are managed directly by your institute IT desk.') }}
                    </p>
                </div>

                <x-auth-session-status
                    class="p-3.5 rounded-xl border border-hairline bg-subtle text-xs font-mono text-ink"
                    :status="session('status')"
                />

                <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                    @csrf
                    <div class="space-y-1.5">
                        <label for="recovery_email" class="block font-mono text-[11px] uppercase tracking-wider text-muted">
                            {{ __('Registered Email') }}
                        </label>
                        <input
                            id="recovery_email"
                            name="email"
                            type="email"
                            required
                            x-bind:value="email"
                            placeholder="name@domain.com"
                            class="w-full px-3.5 py-2.5 text-sm bg-paper border border-hairline rounded-lg shadow-soft focus:border-ink focus:outline-none"
                        />
                    </div>
                    <button
                        type="submit"
                        data-test="email-password-reset-link-button"
                        class="w-full py-2.5 px-4 bg-ink text-paper text-sm font-medium rounded-lg shadow-soft hover:bg-neutral-800 transition-colors"
                    >
                        {{ __('Send Verification Reset Link') }}
                    </button>
                </form>

                <button
                    type="button"
                    x-on:click="recovery = false"
                    class="text-xs font-mono text-muted hover:text-ink underline underline-offset-4"
                >
                    {{ __('← Return to Sign In') }}
                </button>
            </div>
        </div>

        <div class="bg-paper border border-hairline rounded-xl p-3.5 shadow-soft space-y-2.5">
            <div class="flex items-center justify-between text-[11px] font-mono text-muted">
                <span>{{ __('TEST ROLE AUTO-ROUTING:') }}</span>
                <span>{{ __('1-CLICK FILL') }}</span>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                <button
                    type="button"
                    x-on:click="fillDemo('student')"
                    class="py-1.5 px-2 text-[11px] font-mono bg-subtle border border-hairline rounded-lg hover:border-ink hover:bg-paper transition-all text-center truncate"
                >
                    {{ __('Student') }}
                </button>
                <button
                    type="button"
                    x-on:click="fillDemo('employer')"
                    class="py-1.5 px-2 text-[11px] font-mono bg-subtle border border-hairline rounded-lg hover:border-ink hover:bg-paper transition-all text-center truncate"
                >
                    {{ __('Employer') }}
                </button>
                <button
                    type="button"
                    x-on:click="fillDemo('institute')"
                    class="py-1.5 px-2 text-[11px] font-mono bg-subtle border border-hairline rounded-lg hover:border-ink hover:bg-paper transition-all text-center truncate"
                >
                    {{ __('Institute') }}
                </button>
                <button
                    type="button"
                    x-on:click="fillDemo('admin')"
                    class="py-1.5 px-2 text-[11px] font-mono bg-subtle border border-hairline rounded-lg hover:border-ink hover:bg-paper transition-all text-center truncate"
                >
                    {{ __('Admin') }}
                </button>
            </div>
            <p class="font-mono text-[10px] text-muted text-center">
                {{ __('Password for all demo accounts:') }} <span class="text-ink">password</span>
            </p>
        </div>
    </div>
</x-layouts::guest>