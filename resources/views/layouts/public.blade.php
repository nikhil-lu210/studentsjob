<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    @include('partials.head')
    <style>[x-cloak]{display:none!important}</style>
</head>
<body class="bg-paper text-ink font-sans antialiased overflow-x-hidden">
    {{ $slot }}

    @fluxScripts
</body>
</html>
