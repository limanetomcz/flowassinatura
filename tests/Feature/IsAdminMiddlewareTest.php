<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IsAdminMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * Test that the IsAdmin middleware can be resolved from the container.
     *
     * @return void
     */
    public function test_middleware_is_resolvable(): void
    {
        $this->assertInstanceOf(
            \App\Http\Middleware\IsAdmin::class,
            app(\App\Http\Middleware\IsAdmin::class)
        );
    }

    /**
     * @test
     * Test that an admin user can access admin routes.
     *
     * @return void
     */
    public function test_allows_admin_user_to_access_admin_routes(): void
    {
        // Create a fake company for foreign key
        $company = Company::factory()->create();

        /** @var \App\Models\User&\Illuminate\Contracts\Auth\Authenticatable $admin */
        $admin = User::factory()->create([
            'is_admin' => true,
            'company_id' => $company->id,
        ]);

        // Act as the admin user and access the admin dashboard
        $this->actingAs($admin)
            ->get('/admin/dashboard')
            ->assertStatus(200); // Expect HTTP 200 OK
    }

    /**
     * @test
     * Test that a non-admin user is denied access to admin routes.
     *
     * @return void
     */
    public function test_denies_non_admin_user_access_to_admin_routes(): void
    {
        // Create a fake company for foreign key
        $company = Company::factory()->create();

        /** @var \App\Models\User&\Illuminate\Contracts\Auth\Authenticatable $user */
        $user = User::factory()->create([
            'is_admin' => false,
            'company_id' => $company->id,
        ]);

        // Act as the non-admin user and try to access the admin dashboard
        $response = $this->actingAs($user)
            ->get('/admin/dashboard');

        // Assert forbidden access (HTTP 403)
        $response->assertStatus(403);

        // Assert that the denied message is present in the response
        $this->assertStringContainsString(
            'Acesso negado',
            $response->getContent()
        );
    }

    /**
     * @test
     * Test that guest users are redirected to the login page.
     *
     * @return void
     */
    public function test_denies_guest_access_to_admin_routes(): void
    {
        // Attempt to access the admin dashboard as a guest
        $this->get('/admin/dashboard')
            ->assertRedirect('/login'); // Expect redirect to login
    }
}
