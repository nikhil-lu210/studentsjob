<?php

namespace App\Support\Navigation;

use App\Enums\Role;
use App\Models\User;

class PortalNav
{
    /**
     * @return array{portalLabel: string, breadcrumbPrefix: string, contextTitle: string, contextMeta: string, contextVariant: string, pill: string, items: list<array<string, mixed>>}
     */
    public static function for(User $user): array
    {
        return match (true) {
            $user->hasRole(Role::Superadmin->value) => self::superadmin(),
            $user->hasRole(Role::Institute->value) => self::institute(),
            $user->hasRole(Role::Employer->value) => self::employer(),
            $user->hasRole([Role::Student->value, Role::Alumni->value]) => self::student(),
            default => self::student(),
        };
    }

    /**
     * @return array{portalLabel: string, breadcrumbPrefix: string, contextTitle: string, contextMeta: string, contextVariant: string, pill: string, items: list<array<string, mixed>>}
     */
    public static function student(): array
    {
        return [
            'portalLabel' => 'Student Portal',
            'breadcrumbPrefix' => 'PORTAL',
            'contextTitle' => 'Polytechnic Institute',
            'contextMeta' => 'ID: STU-2026-8492 • MSc CS',
            'contextVariant' => 'default',
            'pill' => 'Work-Right Active',
            'items' => [
                ['key' => 'overview', 'label' => 'Overview', 'route' => 'student.overview'],
                [
                    'key' => 'jobs',
                    'label' => 'Verified Jobs',
                    'route' => 'student.jobs',
                    'children' => [
                        ['key' => 'jobs', 'label' => 'All Verified Roles', 'route' => 'student.jobs'],
                        ['key' => 'jobs-full', 'label' => 'Full-Time', 'route' => 'student.jobs', 'query' => ['filter' => 'full-time']],
                        ['key' => 'jobs-part', 'label' => 'Part-Time (≤20h Safe)', 'route' => 'student.jobs', 'query' => ['filter' => 'part-time']],
                        ['key' => 'jobs-visa', 'label' => 'Visa Sponsorship', 'route' => 'student.jobs', 'query' => ['filter' => 'visa']],
                        ['key' => 'jobs-saved', 'label' => 'Saved Opportunities', 'route' => 'student.jobs', 'query' => ['filter' => 'saved'], 'badge' => '02'],
                    ],
                ],
                [
                    'key' => 'applications',
                    'label' => 'Applications',
                    'route' => 'student.applications',
                    'children' => [
                        ['key' => 'applications', 'label' => 'All Submitted', 'route' => 'student.applications', 'badge' => '03'],
                        ['key' => 'applications-interview', 'label' => 'Interview Invites', 'route' => 'student.applications', 'query' => ['filter' => 'interview']],
                        ['key' => 'applications-review', 'label' => 'Under Review', 'route' => 'student.applications', 'query' => ['filter' => 'review']],
                    ],
                ],
                [
                    'key' => 'credentials',
                    'label' => 'Legal & CV Passport',
                    'route' => 'student.credentials',
                    'children' => [
                        ['key' => 'credentials', 'label' => 'Work-Right Certificate', 'route' => 'student.credentials'],
                        ['key' => 'credentials-cv', 'label' => 'Verified CV & Docs', 'route' => 'student.credentials', 'query' => ['section' => 'cv']],
                        ['key' => 'credentials-hours', 'label' => 'Term-Time Hour Cap', 'route' => 'student.credentials', 'query' => ['section' => 'hours']],
                    ],
                ],
                ['key' => 'office', 'label' => 'Career Office', 'route' => 'student.office'],
            ],
        ];
    }

    /**
     * @return array{portalLabel: string, breadcrumbPrefix: string, contextTitle: string, contextMeta: string, contextVariant: string, pill: string, items: list<array<string, mixed>>}
     */
    public static function employer(): array
    {
        return [
            'portalLabel' => 'Employer Portal',
            'breadcrumbPrefix' => 'EMPLOYER',
            'contextTitle' => 'Northwind Analytics Ltd',
            'contextMeta' => 'KYB Verified • Sponsor Ready',
            'contextVariant' => 'default',
            'pill' => 'KYB Active',
            'items' => [
                ['key' => 'overview', 'label' => 'Overview', 'route' => 'employer.overview'],
                [
                    'key' => 'candidates',
                    'label' => 'Verified CVs',
                    'route' => 'employer.candidates',
                    'children' => [
                        ['key' => 'candidates', 'label' => 'All Eligible CVs', 'route' => 'employer.candidates'],
                        ['key' => 'candidates-new', 'label' => 'New Submissions', 'route' => 'employer.candidates', 'query' => ['filter' => 'new']],
                        ['key' => 'candidates-shortlist', 'label' => 'Shortlisted', 'route' => 'employer.candidates', 'query' => ['filter' => 'shortlisted']],
                        ['key' => 'candidates-interview', 'label' => 'Interview Stage', 'route' => 'employer.candidates', 'query' => ['filter' => 'interview']],
                    ],
                ],
                [
                    'key' => 'jobs',
                    'label' => 'Job Postings',
                    'route' => 'employer.jobs',
                    'children' => [
                        ['key' => 'jobs', 'label' => 'All Posted Roles', 'route' => 'employer.jobs'],
                        ['key' => 'jobs-live', 'label' => 'Live & Verified', 'route' => 'employer.jobs', 'query' => ['filter' => 'live']],
                        ['key' => 'jobs-pending', 'label' => 'Pending Review', 'route' => 'employer.jobs', 'query' => ['filter' => 'pending']],
                        ['key' => 'jobs-new', 'label' => '+ Post New Role', 'route' => 'employer.jobs', 'query' => ['action' => 'create']],
                    ],
                ],
                [
                    'key' => 'institutes',
                    'label' => 'Institutes & KYB',
                    'route' => 'employer.institutes',
                    'children' => [
                        ['key' => 'institutes', 'label' => 'Partner Universities', 'route' => 'employer.institutes'],
                        ['key' => 'institutes-kyb', 'label' => 'KYB & Sponsor Vault', 'route' => 'employer.institutes', 'query' => ['section' => 'kyb']],
                    ],
                ],
                ['key' => 'office', 'label' => 'Career Office Desk', 'route' => 'employer.office'],
            ],
        ];
    }

    /**
     * @return array{portalLabel: string, breadcrumbPrefix: string, contextTitle: string, contextMeta: string, contextVariant: string, pill: string, items: list<array<string, mixed>>}
     */
    public static function institute(): array
    {
        return [
            'portalLabel' => 'Institute Admin',
            'breadcrumbPrefix' => 'CAREER OFFICE',
            'contextTitle' => 'Polytechnic Institute',
            'contextMeta' => 'Career Services Node',
            'contextVariant' => 'default',
            'pill' => 'Governance',
            'items' => [
                ['key' => 'overview', 'label' => 'Overview', 'route' => 'institute.overview'],
                [
                    'key' => 'employers',
                    'label' => 'Employer KYB',
                    'route' => 'institute.employers',
                    'children' => [
                        ['key' => 'employers', 'label' => 'All Organizations', 'route' => 'institute.employers'],
                        ['key' => 'employers-pending', 'label' => 'Pending KYB Queue', 'route' => 'institute.employers', 'query' => ['filter' => 'pending']],
                        ['key' => 'employers-approved', 'label' => 'Approved Employers', 'route' => 'institute.employers', 'query' => ['filter' => 'approved']],
                    ],
                ],
                [
                    'key' => 'jobs',
                    'label' => 'Job Vetting',
                    'route' => 'institute.jobs',
                    'children' => [
                        ['key' => 'jobs', 'label' => 'All Submitted Roles', 'route' => 'institute.jobs'],
                        ['key' => 'jobs-pending', 'label' => 'Pending Approval', 'route' => 'institute.jobs', 'query' => ['filter' => 'pending']],
                        ['key' => 'jobs-live', 'label' => 'Verified & Live', 'route' => 'institute.jobs', 'query' => ['filter' => 'live']],
                    ],
                ],
                [
                    'key' => 'registry',
                    'label' => 'Student Registry',
                    'route' => 'institute.registry',
                    'children' => [
                        ['key' => 'registry', 'label' => 'All Verified Cohorts', 'route' => 'institute.registry'],
                        ['key' => 'registry-term', 'label' => 'Term-Time (≤20h/wk)', 'route' => 'institute.registry', 'query' => ['filter' => 'term-time']],
                        ['key' => 'registry-alumni', 'label' => 'Post-Study & Alumni', 'route' => 'institute.registry', 'query' => ['filter' => 'alumni']],
                    ],
                ],
                ['key' => 'audit', 'label' => 'Compliance Audit', 'route' => 'institute.audit'],
            ],
        ];
    }

    /**
     * @return array{portalLabel: string, breadcrumbPrefix: string, contextTitle: string, contextMeta: string, contextVariant: string, pill: string, items: list<array<string, mixed>>}
     */
    public static function superadmin(): array
    {
        return [
            'portalLabel' => 'Platform Root',
            'breadcrumbPrefix' => 'ROOT',
            'contextTitle' => 'studentsjob.work Network',
            'contextMeta' => 'Global Master Access',
            'contextVariant' => 'root',
            'pill' => 'MASTER ROOT ACCESS',
            'items' => [
                ['key' => 'overview', 'label' => 'Overview', 'route' => 'admin.overview'],
                [
                    'key' => 'institutes',
                    'label' => 'Institutes',
                    'route' => 'admin.institutes',
                    'children' => [
                        ['key' => 'institutes', 'label' => 'All Institute Nodes', 'route' => 'admin.institutes'],
                        ['key' => 'institutes-pending', 'label' => 'Pending Approval', 'route' => 'admin.institutes', 'query' => ['filter' => 'pending']],
                        ['key' => 'institutes-active', 'label' => 'Active Nodes', 'route' => 'admin.institutes', 'query' => ['filter' => 'active']],
                        ['key' => 'institutes-new', 'label' => '+ Onboard Institute', 'route' => 'admin.institutes', 'query' => ['action' => 'onboard']],
                    ],
                ],
                [
                    'key' => 'employers',
                    'label' => 'Companies',
                    'route' => 'admin.employers',
                    'children' => [
                        ['key' => 'employers', 'label' => 'All Employers', 'route' => 'admin.employers'],
                        ['key' => 'employers-kyb', 'label' => 'KYB Queue', 'route' => 'admin.employers', 'query' => ['filter' => 'kyb']],
                        ['key' => 'employers-verified', 'label' => 'Verified & Active', 'route' => 'admin.employers', 'query' => ['filter' => 'verified']],
                    ],
                ],
                [
                    'key' => 'students',
                    'label' => 'Students & Alumni',
                    'route' => 'admin.students',
                    'children' => [
                        ['key' => 'students', 'label' => 'All Verified Users', 'route' => 'admin.students'],
                        ['key' => 'students-term', 'label' => 'Term-Time (≤20h/wk)', 'route' => 'admin.students', 'query' => ['filter' => 'term-time']],
                        ['key' => 'students-alumni', 'label' => 'Post-Study & Alumni', 'route' => 'admin.students', 'query' => ['filter' => 'alumni']],
                    ],
                ],
                [
                    'key' => 'jobs',
                    'label' => 'Global Jobs',
                    'route' => 'admin.jobs',
                    'children' => [
                        ['key' => 'jobs', 'label' => 'All Network Roles', 'route' => 'admin.jobs'],
                        ['key' => 'jobs-live', 'label' => 'Verified & Live', 'route' => 'admin.jobs', 'query' => ['filter' => 'live']],
                        ['key' => 'jobs-pending', 'label' => 'Pending / On Hold', 'route' => 'admin.jobs', 'query' => ['filter' => 'pending']],
                    ],
                ],
                ['key' => 'governance', 'label' => 'Platform Governance', 'route' => 'admin.governance'],
            ],
        ];
    }
}
