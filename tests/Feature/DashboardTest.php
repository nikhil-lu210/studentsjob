<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role as RoleModel;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_users_are_redirected_to_their_role_home(): void
    {
        RoleModel::findOrCreate(Role::Student->value);

        $user = User::factory()->create();
        $user->assignRole(Role::Student->value);

        $this->actingAs($user);

        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('student.overview'));
    }
}
