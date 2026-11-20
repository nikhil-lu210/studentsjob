@props([
    'message' => null,
])

<div
    x-data="{ show: @js(filled($message)), message: @js($message ?? '') }"
    x-show="show"
    x-cloak
    x-init="
        $watch('show', value => {
            if (value) {
                clearTimeout(window.__portalToastTimer);
                window.__portalToastTimer = setTimeout(() => show = false, 4500);
            }
        });
        window.addEventListener('portal-toast', event => {
            message = event.detail?.message ?? event.detail?.[0]?.message ?? 'Action completed.';
            show = true;
        });
        Livewire.on('portal-toast', (payload) => {
            message = payload?.message ?? 'Action completed.';
            show = true;
        });
    "
    class="mb-4"
>
    <div class="flex items-center justify-between gap-3 px-4 py-3 rounded-xl bg-ink text-paper shadow-soft">
        <span class="text-sm" x-text="message"></span>
        <button type="button" class="font-mono text-[10px] uppercase tracking-wider opacity-80 hover:opacity-100" @click="show = false">
            Dismiss
        </button>
    </div>
</div>
