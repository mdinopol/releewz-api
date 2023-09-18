<?php

namespace Tests\Feature\Middlewares;

use App\Enum\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RoleAuthorizationMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    private \Illuminate\Routing\Route $route;

    public function setUp(): void
    {
        parent::setUp();

        $this->route = Route::get('/api/dummy-test-route', static function () {
            return 'test';
        });
    }

    /**
     * Exact role should have authorization.
     */
    public function testExactRoleForAuthorization(): void
    {
        $user = User::factory()->create(['role' => Role::USER]);

        $this->route->middleware(rr(Role::USER));

        $this->actingAs($user)
            ->get('/api/dummy-test-route')
            ->assertOk();
    }

    /**
     * High level role should have authorization for lower level roles.
     */
    public function testHighLevelRoleForLowLevelAuthorization(): void
    {
        $superAdmin = User::factory()->create(['role' => Role::SUPER_ADMIN]);

        $this->route->middleware(rr(Role::ADMIN));

        $this->actingAs($superAdmin)
            ->get('/api/dummy-test-route')
            ->assertOk();
    }

    /**
     * Low level role should NOT have authorization for higher level roles.
     */
    public function testLowLevelRoleForHighLevelAuthorization(): void
    {
        $user = User::factory()->create(['role' => Role::USER]);

        $this->route->middleware(rr(Role::ADMIN));

        $this->actingAs($user)
            ->get('/api/dummy-test-route')
            ->assertStatus(401);
    }

    public function testRoleNameDoesntExists(): void
    {
        $staff = User::factory()->create(['role' => Role::ADMIN]);

        $this->route->middleware('role:non_existing_role');

        $this->actingAs($staff)
            ->get('/api/dummy-test-route')
            ->assertStatus(500);
    }
}
