@php
    $user = auth()->user();
    $title = $title ?? null;
    $portalLabel = $portalLabel ?? 'Portal';
    $breadcrumbPrefix = $breadcrumbPrefix ?? 'PORTAL';
    $breadcrumbParent = $breadcrumbParent ?? 'Overview';
    $breadcrumbChild = $breadcrumbChild ?? 'Dashboard Summary';
    $pill = $pill ?? null;
    $contextTitle = $contextTitle ?? null;
    $contextMeta = $contextMeta ?? null;
    $contextVariant = $contextVariant ?? 'default';
    $nav = $nav ?? [];
    $active = $active ?? null;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head', ['title' => $title])
    <style>[x-cloak]{display:none!important}</style>
</head>
<body
    class="min-h-screen bg-subtle text-ink font-sans antialiased overflow-x-hidden"
    x-data="{ sidebarOpen: false }"
>
    <div
        x-show="sidebarOpen"
        x-cloak
        @click="sidebarOpen = false"
        class="fixed inset-0 z-40 bg-black/35 backdrop-blur-[1px] lg:hidden"
    ></div>

    <div class="min-h-screen flex">
        <x-portal.sidebar
            :portal-label="$portalLabel"
            :context-title="$contextTitle"
            :context-meta="$contextMeta"
            :context-variant="$contextVariant"
            :nav="$nav"
            :active="$active"
        />

        <div class="flex-1 flex flex-col min-w-0 lg:pl-72">
            <x-portal.topbar
                :breadcrumb-prefix="$breadcrumbPrefix"
                :breadcrumb-parent="$breadcrumbParent"
                :breadcrumb-child="$breadcrumbChild"
                :pill="$pill"
                :user-name="$user?->name"
                :user-email="$user?->email"
                :user-initials="$user?->initials() ?? 'U'"
            />

            <main class="flex-1 px-4 sm:px-6 py-6">
                <x-portal.toast />
                {{ $slot }}
            </main>
        </div>
    </div>

    @fluxScripts
</body>
</html>
