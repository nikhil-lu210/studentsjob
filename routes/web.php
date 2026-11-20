<?php

use App\Http\Controllers\DashboardRedirectController;
use App\Livewire\Admin\Employers as AdminEmployers;
use App\Livewire\Admin\Governance as AdminGovernance;
use App\Livewire\Admin\Institutes as AdminInstitutes;
use App\Livewire\Admin\Jobs as AdminJobs;
use App\Livewire\Admin\Overview as AdminOverview;
use App\Livewire\Admin\Students as AdminStudents;
use App\Livewire\Employer\Candidates as EmployerCandidates;
use App\Livewire\Employer\Institutes as EmployerInstitutes;
use App\Livewire\Employer\Jobs as EmployerJobs;
use App\Livewire\Employer\Office as EmployerOffice;
use App\Livewire\Employer\Overview as EmployerOverview;
use App\Livewire\Home\Index as HomeIndex;
use App\Livewire\Institute\Audit as InstituteAudit;
use App\Livewire\Institute\Employers as InstituteEmployers;
use App\Livewire\Institute\Jobs as InstituteJobs;
use App\Livewire\Institute\Overview as InstituteOverview;
use App\Livewire\Institute\Registry as InstituteRegistry;
use App\Livewire\Student\Applications as StudentApplications;
use App\Livewire\Student\Credentials as StudentCredentials;
use App\Livewire\Student\Jobs as StudentJobs;
use App\Livewire\Student\Office as StudentOffice;
use App\Livewire\Student\Overview as StudentOverview;
use Illuminate\Support\Facades\Route;

Route::livewire('/', HomeIndex::class)->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardRedirectController::class)->name('dashboard');

    Route::middleware(['role:student|alumni'])->prefix('student')->name('student.')->group(function () {
        Route::livewire('/', StudentOverview::class)->name('overview');
        Route::livewire('/jobs', StudentJobs::class)->name('jobs');
        Route::livewire('/applications', StudentApplications::class)->name('applications');
        Route::livewire('/credentials', StudentCredentials::class)->name('credentials');
        Route::livewire('/office', StudentOffice::class)->name('office');
    });

    Route::middleware(['role:employer'])->prefix('employer')->name('employer.')->group(function () {
        Route::livewire('/', EmployerOverview::class)->name('overview');
        Route::livewire('/candidates', EmployerCandidates::class)->name('candidates');
        Route::livewire('/jobs', EmployerJobs::class)->name('jobs');
        Route::livewire('/institutes', EmployerInstitutes::class)->name('institutes');
        Route::livewire('/office', EmployerOffice::class)->name('office');
    });

    Route::middleware(['role:institute'])->prefix('institute')->name('institute.')->group(function () {
        Route::livewire('/', InstituteOverview::class)->name('overview');
        Route::livewire('/employers', InstituteEmployers::class)->name('employers');
        Route::livewire('/jobs', InstituteJobs::class)->name('jobs');
        Route::livewire('/registry', InstituteRegistry::class)->name('registry');
        Route::livewire('/audit', InstituteAudit::class)->name('audit');
    });

    Route::middleware(['role:superadmin'])->prefix('admin')->name('admin.')->group(function () {
        Route::livewire('/', AdminOverview::class)->name('overview');
        Route::livewire('/institutes', AdminInstitutes::class)->name('institutes');
        Route::livewire('/employers', AdminEmployers::class)->name('employers');
        Route::livewire('/students', AdminStudents::class)->name('students');
        Route::livewire('/jobs', AdminJobs::class)->name('jobs');
        Route::livewire('/governance', AdminGovernance::class)->name('governance');
    });
});

require __DIR__.'/settings.php';
