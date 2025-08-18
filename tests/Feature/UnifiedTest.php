<?php

namespace Tests\Feature;

use App\Livewire\LoginForm;
use App\Models\User;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * Unified Login and Access Test
 *
 * This test suite is designed to cover all major login scenarios
 * in a single unified test file, including:
 * 
 * 1. Admin middleware access verification (IsAdmin)
 * 2. Livewire login form functionality for both admin and regular users
 * 3. Validation and error handling for login attempts
 * 4. Basic route access checks for '/' and '/home' routes
 *
 * The goal is to provide a central, comprehensive set of tests 
 * for authentication and authorization flows, reducing duplication 
 * and ensuring consistent behavior across the application.
 */

class UnifiedTest extends TestCase
{
    use RefreshDatabase;

    /* =====================================================
       Middleware: IsAdmin
    ===================================================== */

    /**
     * Test that the IsAdmin middleware can be resolved.
     *
     * @return void
     */
    public function test_is_admin_middleware_resolvable(): void
    {
        $this->assertInstanceOf(
            \App\Http\Middleware\IsAdmin::class,
            app(\App\Http\Middleware\IsAdmin::class)
        );
    }

    /**
     * Test that an admin user can access admin routes.
     *
     * @return void
     */
    public function test_admin_can_access_admin_dashboard(): void
    {
        $company = Company::factory()->create();

        /** @var User&\Illuminate\Contracts\Auth\Authenticatable $admin */
        $admin = User::factory()->create([
            'is_admin' => true,
            'company_id' => $company->id,
        ]);

        $this->actingAs($admin)
            ->get('/admin/dashboard')
            ->assertStatus(200);
    }

    /**
     * Test that a non-admin user is denied access to admin routes.
     *
     * @return void
     */
    public function test_non_admin_cannot_access_admin_dashboard(): void
    {
        $company = Company::factory()->create();

        /** @var User&\Illuminate\Contracts\Auth\Authenticatable $user */
        $user = User::factory()->create([
            'is_admin' => false,
            'company_id' => $company->id,
        ]);

        $response = $this->actingAs($user)
            ->get('/admin/dashboard');

        $response->assertStatus(403);
        $this->assertStringContainsString('Acesso negado', $response->getContent());
    }

    /**
     * Test that guests are redirected to login when accessing admin routes.
     *
     * @return void
     */
    public function test_guest_redirected_from_admin_dashboard(): void
    {
        $this->get('/admin/dashboard')
            ->assertRedirect('/login');
    }

    /* =====================================================
       Livewire LoginForm Tests
    ===================================================== */

    /**
     * Test that an admin user can login and is redirected to admin dashboard.
     *
     * @return void
     */
    public function test_livewire_admin_login_redirect(): void
    {
        $company = Company::factory()->create();

        /** @var User&\Illuminate\Contracts\Auth\Authenticatable $admin */
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
            'is_admin' => true,
            'company_id' => $company->id,
        ]);

        Livewire::test(LoginForm::class)
            ->set('email', 'admin@example.com')
            ->set('password', 'password123')
            ->call('login')
            ->assertRedirect('/admin/dashboard');
    }

    /**
     * Test that a regular user can login and is redirected to /home.
     *
     * @return void
     */
    public function test_livewire_user_login_redirect(): void
    {
        $company = Company::factory()->create();

        /** @var User&\Illuminate\Contracts\Auth\Authenticatable $user */
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
            'is_admin' => false,
            'company_id' => $company->id,
        ]);

        Livewire::test(LoginForm::class)
            ->set('email', 'user@example.com')
            ->set('password', 'password123')
            ->call('login')
            ->assertRedirect('/home');
    }

    /**
     * Test invalid credentials show error.
     *
     * @return void
     */
    public function test_livewire_login_invalid_credentials(): void
    {
        $company = Company::factory()->create();

        /** @var User&\Illuminate\Contracts\Auth\Authenticatable $user */
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
            'is_admin' => false,
            'company_id' => $company->id,
        ]);

        Livewire::test(LoginForm::class)
            ->set('email', 'user@example.com')
            ->set('password', 'wrongpassword')
            ->call('login')
            ->assertSet('error', 'Credenciais inválidas. Verifique seu email e senha.');
    }

    /**
     * Test validation errors for empty fields.
     *
     * @return void
     */
    public function test_livewire_login_empty_fields_validation(): void
    {
        Livewire::test(LoginForm::class)
            ->call('login')
            ->assertHasErrors([
                'email' => 'required',
                'password' => 'required',
            ]);
    }

    /**
     * Test validation error for invalid email format.
     *
     * @return void
     */
    public function test_livewire_login_invalid_email_format(): void
    {
        Livewire::test(LoginForm::class)
            ->set('email', 'not-an-email')
            ->set('password', 'password123')
            ->call('login')
            ->assertHasErrors(['email' => 'email']);
    }

    /* =====================================================
       Basic Route Tests
    ===================================================== */

    /**
     * Test root url redirects to login for guests.
     *
     * @return void
     */
    public function test_root_redirects_to_login_for_guests(): void
    {
        $this->get('/')
            ->assertRedirect('/login');
    }

    /**
     * Test root url redirects to home for authenticated users.
     *
     * @return void
     */
    public function test_root_redirects_to_home_for_authenticated_users(): void
    {
        $company = Company::factory()->create();

        /** @var User&\Illuminate\Contracts\Auth\Authenticatable $user */
        $user = User::factory()->create([
            'is_admin' => false,
            'company_id' => $company->id,
        ]);

        $this->actingAs($user)
            ->get('/')
            ->assertRedirect('/home');
    }

    /**
     * Test home page returns HTTP 200 for authenticated users.
     *
     * @return void
     */
    public function test_home_page_returns_ok(): void
    {
        $company = Company::factory()->create();

        /** @var User&\Illuminate\Contracts\Auth\Authenticatable $user */
        $user = User::factory()->create([
            'is_admin' => false,
            'company_id' => $company->id,
        ]);

        $this->actingAs($user)
            ->get('/home')
            ->assertOk();
    }
}
