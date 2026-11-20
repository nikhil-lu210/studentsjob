<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    @include('partials.head')
</head>
<body class="bg-paper text-ink font-sans antialiased overflow-x-hidden">
    {{ $slot }}

    @fluxScripts
</body>
</html>
