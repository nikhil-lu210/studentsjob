<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\InteractsWithPortal;
use App\Support\Demo\DemoCatalog;
use Livewire\Component;

class Overview extends Component
{
    use InteractsWithPortal;

    public string $filter = 'all';

    public bool $drawerOpen = false;

    public bool $modalOpen = false;

    public ?string $selectedId = null;

    public function mount(): void
    {
        $this->filter = (string) request()->query('filter', request()->query('section', request()->query('action', 'all')));

        if (in_array($this->filter, ['create', 'onboard'], true)) {
            $this->modalOpen = true;
        }
    }

    public function openDrawer(?string $id = null): void
    {
        $this->selectedId = $id;
        $this->drawerOpen = true;
    }

    public function openModal(): void
    {
        $this->modalOpen = true;
    }

    public function render()
    {
                return $this->portalView(
            'livewire.admin.overview',
            'overview',
            'Overview',
            'Dashboard Summary',
            [
                'jobs' => collect(DemoCatalog::jobs()),
                'applications' => collect(DemoCatalog::applications()),
                'candidates' => collect(DemoCatalog::candidates()),
                'employers' => collect(DemoCatalog::employers()),
                'institutes' => collect(DemoCatalog::institutes()),
                'students' => collect(DemoCatalog::students()),
                'auditLogs' => collect(DemoCatalog::auditLogs()),
            ],
            'Platform Root',
        );
    }
}
