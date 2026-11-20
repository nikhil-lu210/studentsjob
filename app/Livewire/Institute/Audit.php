<?php

namespace App\Livewire\Institute;

use App\Livewire\Concerns\InteractsWithPortal;
use App\Support\Demo\DemoCatalog;
use Livewire\Component;

class Audit extends Component
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
            'livewire.institute.audit',
            'audit',
            'Audit',
            'Audit Workspace',
            [
                'jobs' => collect(DemoCatalog::jobs()),
                'applications' => collect(DemoCatalog::applications()),
                'candidates' => collect(DemoCatalog::candidates()),
                'employers' => collect(DemoCatalog::employers()),
                'institutes' => collect(DemoCatalog::institutes()),
                'students' => collect(DemoCatalog::students()),
                'auditLogs' => collect(DemoCatalog::auditLogs()),
            ],
            'Institute Admin',
        );
    }
}
