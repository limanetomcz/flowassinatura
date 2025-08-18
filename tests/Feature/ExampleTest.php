<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * Test that the root URL redirects to login for guest users.
     *
     * @return void
     */
    public function test_root_url_redirects_to_login_for_guests(): void
    {
        // Attempt to access the root URL as a guest
        $response = $this->get('/');

        // Expect redirect to /login since the user is not authenticated
        $response->assertRedirect('/login');
    }

    /**
     * @test
     * Test that the root URL redirects to /home for authenticated users.
     *
     * @return void
     */
    public function test_root_url_redirects_to_home_for_authenticated_users(): void
    {
        // Create a fake company for foreign key
        $company = Company::factory()->create();

        /** @var \App\Models\User&\Illuminate\Contracts\Auth\Authenticatable $user */
        $user = User::factory()->create([
            'company_id' => $company->id,
        ]);

        // Act as the authenticated user and access the root URL
        $response = $this->actingAs($user)->get('/');

        // Expect redirect to /home
        $response->assertRedirect('/home');
    }

    /**
     * @test
     * Test that the /home page returns HTTP 200 for authenticated users.
     *
     * @return void
     */
    public function test_home_page_returns_ok_for_authenticated_users(): void
    {
        // Create a fake company for foreign key
        $company = Company::factory()->create();

        /** @var \App\Models\User&\Illuminate\Contracts\Auth\Authenticatable $user */
        $user = User::factory()->create([
            'company_id' => $company->id,
        ]);

        // Act as the authenticated user and access /home
        $response = $this->actingAs($user)->get('/home');

        // Assert HTTP 200 OK
        $response->assertStatus(200);
    }
}
