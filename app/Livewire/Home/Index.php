<?php

namespace App\Livewire\Home;

use App\Support\Demo\DemoCatalog;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.public')]
class Index extends Component
{
    public string $audience = 'student';

    public string $filter = 'all';

    public bool $jobDrawerOpen = false;

    public bool $authModalOpen = false;

    public string $authRole = 'student';

    public ?string $selectedJobId = null;

    public function setAudience(string $audience): void
    {
        $this->audience = $audience;
    }

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
    }

    public function openJob(string $jobId): void
    {
        $this->selectedJobId = $jobId;
        $this->jobDrawerOpen = true;
    }

    public function openAuth(string $role = 'student'): void
    {
        $this->authRole = $role;
        $this->authModalOpen = true;
    }

    public function render()
    {
        $jobs = collect(DemoCatalog::jobs())
            ->when($this->filter !== 'all', fn ($c) => $c->filter(fn ($job) => in_array($this->filter, $job['filter'], true) || ($this->filter === 'part-time' && $job['type'] === 'Part-Time') || ($this->filter === 'full-time' && $job['type'] === 'Full-Time') || ($this->filter === 'visa' && $job['visa'])))
            ->values();

        $selected = collect(DemoCatalog::jobs())->firstWhere('id', $this->selectedJobId);

        return view('livewire.home.index', [
            'jobs' => $jobs,
            'selectedJob' => $selected,
        ]);
    }
}
