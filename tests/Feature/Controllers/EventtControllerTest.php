<?php

namespace Tests\Feature\Controllers;

use App\Enum\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class EventtControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    public function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'user_name'  => 'admin_doe',
            'first_name' => 'Admin',
            'last_name'  => 'Doe',
            'email'      => 'admin_doe@email.com',
            'role'       => Role::ADMIN,
        ]);
    }

    public function testCreate(): void
    {
        Passport::actingAs($this->admin);

        $this->post('/api/eventts', [
            'name' => 'Test event',
        ])
        ->assertCreated()
        ->assertJson([
            'name' => 'Test event',
        ]);
    }
}
