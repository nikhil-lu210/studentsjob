<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-subtle text-ink font-sans antialiased flex flex-col justify-between overflow-x-hidden">
    <header class="w-full bg-paper/90 backdrop-blur-md border-b border-hairline shadow-soft">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <x-brand />
            <a href="{{ route('home') }}" wire:navigate class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-mono text-ink bg-paper border border-hairline rounded-lg shadow-soft hover:border-ink transition-all">
                <span>←</span>
                <span>BACK TO HOME</span>
            </a>
        </div>
    </header>

    <main class="flex-1 flex items-center justify-center px-4 py-10 sm:py-16">
        <div class="w-full max-w-[440px] space-y-5">
            {{ $slot }}
        </div>
    </main>

    <footer class="border-t border-hairline bg-paper">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row items-center justify-between gap-2 font-mono text-[11px] text-muted">
            <span>studentsjob.work // Institute-Verified Career Network</span>
            <span>Secure Access Portal</span>
        </div>
    </footer>

    @fluxScripts
</body>
</html>
