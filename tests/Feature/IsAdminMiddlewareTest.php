<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Auth\Authenticatable; 
use Tests\TestCase;

class IsAdminMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function allows_admin_user_to_access_admin_routes()
    {
        /** @var Authenticatable $admin */
        $admin = User::factory()->create([
            'is_admin' => true,
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
    }

    /** @test */
    public function denies_non_admin_user_access_to_admin_routes()
    {
        /** @var Authenticatable $user */
        $user = User::factory()->create([
            'is_admin' => false,
            'password' => bcrypt('password123'),
        ]);

        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertStatus(403);
        $response->assertSee('Acesso negado');
    }

    /** @test */
    public function denies_guest_access_to_admin_routes()
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/login');
    }
}
