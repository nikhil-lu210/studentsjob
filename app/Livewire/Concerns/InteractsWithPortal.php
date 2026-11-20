<?php

namespace App\Livewire\Concerns;

use App\Support\Navigation\PortalNav;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

trait InteractsWithPortal
{
    /**
     * @return array{portalLabel: string, breadcrumbPrefix: string, contextTitle: string, contextMeta: string, contextVariant: string, pill: string, items: list<array<string, mixed>>}
     */
    protected function portalConfig(): array
    {
        return PortalNav::for(Auth::user());
    }

    protected function toast(string $message): void
    {
        $this->dispatch('portal-toast', message: $message);
    }

    protected function portalView(
        string $view,
        string $activeNav,
        string $breadcrumbParent,
        string $breadcrumbChild,
        array $data = [],
        ?string $title = null,
    ): View {
        $config = $this->portalConfig();

        return view($view, $data)
            ->layout('layouts.portal', [
                'title' => $title ?? $config['portalLabel'],
                'portalLabel' => $config['portalLabel'],
                'breadcrumbPrefix' => $config['breadcrumbPrefix'],
                'breadcrumbParent' => $breadcrumbParent,
                'breadcrumbChild' => $breadcrumbChild,
                'pill' => $config['pill'],
                'contextTitle' => $config['contextTitle'],
                'contextMeta' => $config['contextMeta'],
                'contextVariant' => $config['contextVariant'],
                'nav' => $config['items'],
                'active' => $activeNav,
            ]);
    }
}
