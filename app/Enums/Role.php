<?php

namespace App\Enums;

enum Role: string
{
    case Superadmin = 'superadmin';
    case Institute = 'institute';
    case Employer = 'employer';
    case Student = 'student';
    case Alumni = 'alumni';

    public function label(): string
    {
        return match ($this) {
            self::Superadmin => 'Superadmin',
            self::Institute => 'Institute Career Office',
            self::Employer => 'Employer',
            self::Student => 'Student',
            self::Alumni => 'Alumni',
        };
    }

    public function homeRoute(): string
    {
        return match ($this) {
            self::Superadmin => 'admin.overview',
            self::Institute => 'institute.overview',
            self::Employer => 'employer.overview',
            self::Student, self::Alumni => 'student.overview',
        };
    }
}
