<?php

use App\Models\User;
use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Illuminate\Http\Request;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(ConsoleKernel::class)->bootstrap();

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$demos = [
    'student@studentsjob.work' => [
        '/', '/dashboard', '/student', '/student/jobs', '/student/applications', '/student/credentials', '/student/office',
    ],
    'employer@studentsjob.work' => [
        '/dashboard', '/employer', '/employer/candidates', '/employer/jobs', '/employer/institutes', '/employer/office',
    ],
    'institute@studentsjob.work' => [
        '/dashboard', '/institute', '/institute/employers', '/institute/jobs', '/institute/registry', '/institute/audit',
    ],
    'admin@studentsjob.work' => [
        '/dashboard', '/admin', '/admin/institutes', '/admin/employers', '/admin/students', '/admin/jobs', '/admin/governance',
    ],
];

$failed = 0;

foreach ($demos as $email => $paths) {
    $user = User::where('email', $email)->firstOrFail();
    auth()->login($user);

    foreach ($paths as $path) {
        $request = Request::create($path, 'GET');
        $response = $kernel->handle($request);
        $status = $response->getStatusCode();
        $ok = $status < 400;
        if ($status >= 300 && $status < 400) {
            $ok = true;
        }
        echo ($ok ? 'OK ' : 'FAIL ').str_pad($email, 32).' '.$path.' => '.$status.PHP_EOL;
        if (! $ok) {
            $failed++;
            preg_match_all('/\[.*?\] local\.ERROR: ([^\{\n]+)/', file_get_contents(__DIR__.'/../storage/logs/laravel.log'), $matches);
            echo '  ERROR: '.end($matches[1]).PHP_EOL;
        }
        $kernel->terminate($request, $response);
    }
}

echo $failed === 0 ? "All smoke checks passed.\n" : "Failed: {$failed}\n";
exit($failed === 0 ? 0 : 1);
