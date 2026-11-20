<?php

$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator(dirname(__DIR__).'/app/Livewire')
);

foreach ($files as $file) {
    if (! $file->isFile() || $file->getExtension() !== 'php') {
        continue;
    }

    $path = $file->getPathname();
    if (! str_contains($path, 'Student') && ! str_contains($path, 'Employer') && ! str_contains($path, 'Institute') && ! str_contains($path, 'Admin')) {
        continue;
    }

    $contents = file_get_contents($path);

    if (! str_contains($contents, 'return view(')) {
        continue;
    }

    // Extract activeNav / breadcrumb from existing array for replacement
    if (! preg_match("/'activeNav' => '([^']+)'/", $contents, $active)) {
        continue;
    }
    if (! preg_match("/'breadcrumbParent' => '([^']+)'/", $contents, $parent)) {
        continue;
    }
    if (! preg_match("/'breadcrumbChild' => '([^']+)'/", $contents, $child)) {
        continue;
    }
    if (! preg_match("/return view\\('([^']+)'/", $contents, $view)) {
        continue;
    }

    $ns = str_contains($path, 'Admin') ? 'Platform Root'
        : (str_contains($path, 'Institute') ? 'Institute Admin'
            : (str_contains($path, 'Employer') ? 'Employer Portal' : 'Student Portal'));

    $replacement = <<<PHP
        return \$this->portalView(
            '{$view[1]}',
            '{$active[1]}',
            '{$parent[1]}',
            '{$child[1]}',
            [
                'jobs' => collect(DemoCatalog::jobs()),
                'applications' => collect(DemoCatalog::applications()),
                'candidates' => collect(DemoCatalog::candidates()),
                'employers' => collect(DemoCatalog::employers()),
                'institutes' => collect(DemoCatalog::institutes()),
                'students' => collect(DemoCatalog::students()),
                'auditLogs' => collect(DemoCatalog::auditLogs()),
            ],
            '{$ns}',
        );
PHP;

    $contents = preg_replace(
        '/return view\((.*?)\);/s',
        $replacement,
        $contents,
        1
    );

    // Remove unused activeNav breadcrumb from being needed in view - keep filter etc
    file_put_contents($path, $contents);
    echo "Updated {$path}\n";
}
