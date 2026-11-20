<?php

namespace App\Support\Demo;

class DemoCatalog
{
    /**
     * @return list<array<string, mixed>>
     */
    public static function jobs(): array
    {
        return [
            [
                'id' => 'JOB-1001',
                'title' => 'Campus Research Assistant',
                'company' => 'Northwind Analytics',
                'institute' => 'Polytechnic Institute',
                'type' => 'Part-Time',
                'hours' => '≤16h/wk',
                'filter' => ['part-time', 'saved'],
                'visa' => false,
                'eligible' => true,
                'location' => 'On-campus',
                'salary' => '£14–16/hr',
                'summary' => 'Assist faculty with term-time research tasks. Institute-vetted, seal-compatible.',
            ],
            [
                'id' => 'JOB-1002',
                'title' => 'Junior Data Analyst',
                'company' => 'Brightpath Labs',
                'institute' => 'Polytechnic Institute',
                'type' => 'Full-Time',
                'hours' => '40h/wk',
                'filter' => ['full-time', 'visa'],
                'visa' => true,
                'eligible' => false,
                'location' => 'Hybrid',
                'salary' => '£32k–36k',
                'summary' => 'Full-time graduate role with sponsorship pathway. Locked for term-time visa holders.',
            ],
            [
                'id' => 'JOB-1003',
                'title' => 'Student Ambassador',
                'company' => 'City Careers Hub',
                'institute' => 'Polytechnic Institute',
                'type' => 'Part-Time',
                'hours' => '≤12h/wk',
                'filter' => ['part-time'],
                'visa' => false,
                'eligible' => true,
                'location' => 'Campus',
                'salary' => '£13.50/hr',
                'summary' => 'Represent the institute at open days and peer mentoring sessions.',
            ],
            [
                'id' => 'JOB-1004',
                'title' => 'Software Intern (Sponsorship)',
                'company' => 'Orbital Systems',
                'institute' => 'Polytechnic Institute',
                'type' => 'Full-Time',
                'hours' => '37.5h/wk',
                'filter' => ['full-time', 'visa', 'saved'],
                'visa' => true,
                'eligible' => false,
                'location' => 'Remote-first',
                'salary' => '£28k',
                'summary' => 'Requires unrestricted work rights or active sponsorship.',
            ],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function applications(): array
    {
        return [
            ['id' => 'APP-01', 'role' => 'Campus Research Assistant', 'company' => 'Northwind Analytics', 'status' => 'Interview', 'filter' => 'interview', 'updated' => '2h ago'],
            ['id' => 'APP-02', 'role' => 'Student Ambassador', 'company' => 'City Careers Hub', 'status' => 'Under Review', 'filter' => 'review', 'updated' => '1d ago'],
            ['id' => 'APP-03', 'role' => 'Library Desk Assistant', 'company' => 'Polytechnic Library', 'status' => 'Submitted', 'filter' => 'review', 'updated' => '3d ago'],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function candidates(): array
    {
        return [
            ['id' => 'CV-01', 'name' => 'Alex Mercer', 'program' => 'MSc Computer Science', 'seal' => 'Term-Time ≤20h', 'stage' => 'new', 'match' => '96%'],
            ['id' => 'CV-02', 'name' => 'Samira Khan', 'program' => 'BEng Software', 'seal' => 'Term-Time ≤20h', 'stage' => 'shortlisted', 'match' => '91%'],
            ['id' => 'CV-03', 'name' => 'Jordan Lee', 'program' => 'Alumni — PSW', 'seal' => 'Post-Study Work', 'stage' => 'interview', 'match' => '88%'],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function employers(): array
    {
        return [
            ['id' => 'ORG-01', 'name' => 'Northwind Analytics Ltd', 'status' => 'approved', 'kyb' => 'Verified', 'jobs' => 4],
            ['id' => 'ORG-02', 'name' => 'Brightpath Labs', 'status' => 'pending', 'kyb' => 'In review', 'jobs' => 2],
            ['id' => 'ORG-03', 'name' => 'Orbital Systems', 'status' => 'pending', 'kyb' => 'Docs requested', 'jobs' => 1],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function institutes(): array
    {
        return [
            ['id' => 'INS-01', 'name' => 'Polytechnic Institute', 'status' => 'active', 'students' => 1842, 'employers' => 64],
            ['id' => 'INS-02', 'name' => 'Harbour College', 'status' => 'pending', 'students' => 0, 'employers' => 0],
            ['id' => 'INS-03', 'name' => 'Westbridge University', 'status' => 'active', 'students' => 920, 'employers' => 41],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function students(): array
    {
        return [
            ['id' => 'STU-8492', 'name' => 'Alex Mercer', 'cohort' => 'Term-Time', 'filter' => 'term-time', 'hours' => '≤20h/wk'],
            ['id' => 'STU-7711', 'name' => 'Samira Khan', 'cohort' => 'Term-Time', 'filter' => 'term-time', 'hours' => '≤20h/wk'],
            ['id' => 'ALU-2201', 'name' => 'Jordan Lee', 'cohort' => 'Alumni', 'filter' => 'alumni', 'hours' => 'Unrestricted'],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function auditLogs(): array
    {
        return [
            ['when' => 'Today 09:14', 'actor' => 'Career Office', 'action' => 'Approved KYB', 'target' => 'Northwind Analytics'],
            ['when' => 'Yesterday 16:02', 'actor' => 'System', 'action' => 'Blocked ineligible apply', 'target' => 'JOB-1002 / Alex Mercer'],
            ['when' => 'Mon 11:40', 'actor' => 'Career Office', 'action' => 'Vetted job live', 'target' => 'Student Ambassador'],
        ];
    }
}
