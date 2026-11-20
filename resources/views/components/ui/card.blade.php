@props([
    'padding' => true,
])

<div {{ $attributes->class([
    'bg-paper border border-hairline rounded-2xl shadow-card',
    'p-5 sm:p-6' => $padding,
]) }}>
    {{ $slot }}
</div>
