<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LoginRedirectTest extends TestCase
{
    use RefreshDatabase;

#[Test]
public function admin_user_is_redirected_to_admin_dashboard_after_login()
{
    $admin = User::factory()->create([
        'is_admin' => true,
        'password' => bcrypt('password123'), // HASH a senha
    ]);

    $response = $this->post('/login', [
        'email' => $admin->email,
        'password' => 'password123',
    ]);

    $response->assertRedirect('/admin/dashboard');
}

#[Test]
public function regular_user_is_redirected_to_home_after_login()
{
    $user = User::factory()->create([
        'is_admin' => false,
        'password' => bcrypt('password123'), 
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password123',
    ]);

    $response->assertRedirect('/home');
}

#[Test]
public function regular_user_cannot_access_admin_routes()
{
    $user = User::factory()->create([
        'is_admin' => false,
        'password' => bcrypt('password123'), 
    ]);

    $this->actingAs($user);

    $response = $this->get('/admin/dashboard');

    $response->assertStatus(403);
}

}
