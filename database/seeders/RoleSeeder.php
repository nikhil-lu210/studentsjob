<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role as RoleModel;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        foreach (Role::cases() as $role) {
            RoleModel::findOrCreate($role->value);
        }

        $demos = [
            ['name' => 'Alex Mercer', 'email' => 'student@studentsjob.work', 'role' => Role::Student],
            ['name' => 'Jordan Lee', 'email' => 'alumni@studentsjob.work', 'role' => Role::Alumni],
            ['name' => 'Northwind Recruiter', 'email' => 'employer@studentsjob.work', 'role' => Role::Employer],
            ['name' => 'Career Office', 'email' => 'institute@studentsjob.work', 'role' => Role::Institute],
            ['name' => 'Platform Root', 'email' => 'admin@studentsjob.work', 'role' => Role::Superadmin],
        ];

        foreach ($demos as $demo) {
            $user = User::query()->updateOrCreate(
                ['email' => $demo['email']],
                [
                    'name' => $demo['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ],
            );

            $user->syncRoles([$demo['role']->value]);
        }
    }
}
