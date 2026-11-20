<?php

$roles = [
    'Student' => ['Overview', 'Jobs', 'Applications', 'Credentials', 'Office'],
    'Employer' => ['Overview', 'Candidates', 'Jobs', 'Institutes', 'Office'],
    'Institute' => ['Overview', 'Employers', 'Jobs', 'Registry', 'Audit'],
    'Admin' => ['Overview', 'Institutes', 'Employers', 'Students', 'Jobs', 'Governance'],
];

$base = dirname(__DIR__).'/app/Livewire';
$viewsBase = dirname(__DIR__).'/resources/views/livewire';

foreach ($roles as $ns => $pages) {
    $dir = $base.'/'.$ns;
    if (! is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    $nsPath = $ns === 'Admin' ? 'admin' : strtolower($ns);
    $viewDir = $viewsBase.'/'.$nsPath;
    if (! is_dir($viewDir)) {
        mkdir($viewDir, 0777, true);
    }

    foreach ($pages as $page) {
        $active = strtolower($page);
        $parent = $page;
        $child = $page === 'Overview' ? 'Dashboard Summary' : $page.' Workspace';
        $view = 'livewire.'.$nsPath.'.'.strtolower($page);

        $php = <<<PHP
<?php

namespace App\\Livewire\\{$ns};

use App\\Livewire\\Concerns\\InteractsWithPortal;
use App\\Support\\Demo\\DemoCatalog;
use Livewire\\Component;

class {$page} extends Component
{
    use InteractsWithPortal;

    public string \$filter = 'all';

    public bool \$drawerOpen = false;

    public bool \$modalOpen = false;

    public ?string \$selectedId = null;

    public function mount(): void
    {
        \$this->filter = (string) request()->query('filter', request()->query('section', request()->query('action', 'all')));

        if (in_array(\$this->filter, ['create', 'onboard'], true)) {
            \$this->modalOpen = true;
        }
    }

    public function openDrawer(?string \$id = null): void
    {
        \$this->selectedId = \$id;
        \$this->drawerOpen = true;
    }

    public function openModal(): void
    {
        \$this->modalOpen = true;
    }

    public function render()
    {
        return view('{$view}', [
            'jobs' => collect(DemoCatalog::jobs()),
            'applications' => collect(DemoCatalog::applications()),
            'candidates' => collect(DemoCatalog::candidates()),
            'employers' => collect(DemoCatalog::employers()),
            'institutes' => collect(DemoCatalog::institutes()),
            'students' => collect(DemoCatalog::students()),
            'auditLogs' => collect(DemoCatalog::auditLogs()),
            'activeNav' => '{$active}',
            'breadcrumbParent' => '{$parent}',
            'breadcrumbChild' => '{$child}',
        ]);
    }
}

PHP;
        file_put_contents($dir.'/'.$page.'.php', $php);
        echo "Wrote {$dir}/{$page}.php\n";
    }
}

echo "Done generating PHP classes.\n";
