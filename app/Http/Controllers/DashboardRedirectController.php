<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardRedirectController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $user = $request->user();

        foreach (Role::cases() as $role) {
            if ($user?->hasRole($role->value)) {
                return redirect()->route($role->homeRoute());
            }
        }

        return redirect()->route('home');
    }
}
