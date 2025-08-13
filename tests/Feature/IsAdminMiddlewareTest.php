<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Auth\Authenticatable; 
use Tests\TestCase;

class IsAdminMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_middleware_is_resolvable()
    {
        $this->assertInstanceOf(
            \App\Http\Middleware\IsAdmin::class,
            app(\App\Http\Middleware\IsAdmin::class)
        );
    }

    public function test_allows_admin_user_to_access_admin_routes()
    {
        /** @var Authenticatable $admin */
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $this->actingAs($admin)
            ->get('/admin/dashboard')
            ->assertOk(); // HTTP 200
    }

    public function test_denies_non_admin_user_access_to_admin_routes()
    {
        /** @var Authenticatable $user */
        $user = User::factory()->create([
            'is_admin' => false,
        ]);

        $this->actingAs($user)
            ->get('/admin/dashboard')
            ->assertForbidden()
            ->assertSeeText('Acesso negado');
    }

    public function test_denies_guest_access_to_admin_routes()
    {
        $this->get('/admin/dashboard')
            ->assertRedirect('/login');
    }
}
