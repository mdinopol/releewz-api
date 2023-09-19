<?php

namespace Tests\Feature\Controllers;

use App\Enum\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;

    public function setUp(): void
    {
        parent::setUp();

        $this->superAdmin = User::factory()->create([
            'user_name'  => 'john_doe',
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'email'      => 'john.doe@email.com',
            'role'       => Role::SUPER_ADMIN,
        ]);
    }

    public function testList(): void
    {
        Passport::actingAs($this->superAdmin);

        $this->get('/api/users')
            ->assertOk()
            ->assertJsonStructure([[
                'user_name',
                'first_name',
                'last_name',
                'email',
                'email_verified_at',
                'phone',
                'date_of_birth',
                'country_code',
                'role',
            ]]);
    }

    public function testShow(): void
    {
        Passport::actingAs($this->superAdmin);

        $this->get('/api/users/'.$this->superAdmin->id)
            ->assertOk()
            ->assertJson([
                'user_name'  => 'john_doe',
                'first_name' => 'John',
                'last_name'  => 'Doe',
                'email'      => 'john.doe@email.com',
            ]);
    }

    public function testCreate(): void
    {
        Passport::actingAs($this->superAdmin);

        $this->post('/api/users', [
                'user_name'    => 'jane_doe',
                'first_name'   => 'Jane',
                'last_name'    => 'Doe',
                'email'        => 'jane.doe@email.com',
                'password'     => 'password',
                'role'         => Role::ADMIN->value,
                'country_code' => 'ph',
            ])
            ->assertCreated()
            ->assertJson([
                'user_name'    => 'jane_doe',
                'first_name'   => 'Jane',
                'last_name'    => 'Doe',
                'email'        => 'jane.doe@email.com',
                'role'         => Role::ADMIN->value,
                'country_code' => 'ph',
            ]);

        $this->assertDatabaseHas('users', [
            'user_name'    => 'jane_doe',
            'first_name'   => 'Jane',
            'last_name'    => 'Doe',
            'email'        => 'jane.doe@email.com',
            'role'         => Role::ADMIN->value,
            'country_code' => 'ph',
        ]);
    }

    public function testSuperAdminCanCreateSuperAdmin(): void
    {
        Passport::actingAs($this->superAdmin);

        $this->post('/api/users', [
                'user_name'    => 'admin_super',
                'first_name'   => 'Admin',
                'last_name'    => 'Super',
                'email'        => 'new.super@email.com',
                'password'     => 'password',
                'role'         => Role::ADMIN->value,
                'country_code' => 'ph',
            ])
            ->assertCreated()
            ->assertJson([
                'user_name'    => 'admin_super',
                'first_name'   => 'Admin',
                'last_name'    => 'Super',
                'email'        => 'new.super@email.com',
                'role'         => Role::ADMIN->value,
                'country_code' => 'ph',
            ]);

        $this->assertDatabaseHas('users', [
            'user_name'    => 'admin_super',
            'first_name'   => 'Admin',
            'last_name'    => 'Super',
            'email'        => 'new.super@email.com',
            'role'         => Role::ADMIN->value,
            'country_code' => 'ph',
        ]);
    }

    public function testCreateDuplicateEmail(): void
    {
        Passport::actingAs($this->superAdmin);

        $this->post('/api/users', [
                'user_name'  => 'john_doe',
                'first_name' => 'John',
                'last_name'  => 'Doe',
                'email'      => 'john.doe@email.com',
                'password'   => 'password',
                'role'       => Role::SUPER_ADMIN->value,
            ])
            ->assertUnprocessable();
    }

    public function testCreateNoPasswordFails(): void
    {
        Passport::actingAs($this->superAdmin);

        $this->post('/api/users', [
                'user_name'  => 'jane_doe',
                'first_name' => 'Jane',
                'last_name'  => 'Doe',
                'email' => 'jane.doe@email.com',
            ])
            ->assertUnprocessable();
    }

    public function testUpdate(): void
    {
        Passport::actingAs($this->superAdmin);

        $this->put('/api/users/'.$this->superAdmin->id, [
                'user_name'  => 'johnny_dow',
                'first_name' => 'Johnny',
                'last_name'  => 'Dow',
            ])
            ->assertOk()
            ->assertJson([
                'user_name'  => 'johnny_dow',
                'first_name' => 'Johnny',
                'last_name'  => 'Dow',
            ]);

        $this->assertDatabaseHas('users', [
            'user_name'  => 'johnny_dow',
            'first_name' => 'Johnny',
            'last_name'  => 'Dow',
        ]);

        $this->assertDatabaseMissing('users', [
            'user_name'  => 'john_doe',
            'first_name' => 'John',
            'last_name'  => 'Doe',
        ]);
    }

    public function testUpdateDuplicateEmailWithSelf(): void
    {
        Passport::actingAs($this->superAdmin);

        $this->put('/api/users/'.$this->superAdmin->id, [
                'user_name'  => 'john_james_doe',
                'first_name' => 'John James',
                'last_name'  => 'Doe',
                'email' => 'john.doe@email.com',
            ])
            ->assertOk()
            ->assertJson([
                'user_name'  => 'john_james_doe',
                'first_name' => 'John James',
                'last_name'  => 'Doe',
                'email' => 'john.doe@email.com',
            ]);

        $this->assertDatabaseHas('users', [
            'user_name'  => 'john_james_doe',
            'first_name' => 'John James',
            'last_name'  => 'Doe',
            'email' => 'john.doe@email.com',
        ]);

        $this->assertDatabaseMissing('users', [
            'user_name'  => 'john_doe',
            'first_name' => 'John',
            'last_name'  => 'Doe',
        ]);
    }

    public function testUpdateDuplicateEmailWithOther(): void
    {
        Passport::actingAs($this->superAdmin);

        User::factory()->create([
            'user_name'  => 'juan_doe',
            'first_name' => 'Juan',
            'last_name'  => 'Doe',
            'email' => 'juan.doe@email.com',
            'role'  => Role::SUPER_ADMIN,
        ]);

        $this->put('/api/users/'.$this->superAdmin->id, [
                'user_name'  => 'juan_james_doe',
                'first_name' => 'Juan James',
                'last_name'  => 'Doe',
                'email' => 'juan.doe@email.com',
            ])
            ->assertUnprocessable();
    }

    public function testUpdateMe(): void
    {
        Passport::actingAs($this->superAdmin);

        $this->put('/api/users/me', [
                'user_name'  => 'jose_doe',
                'first_name' => 'Jose',
                'last_name'  => 'Doe',
                'email' => 'jose.doe@email.com',
            ])
            ->assertOk()
            ->assertJson([
                'user_name'  => 'jose_doe',
                'first_name' => 'Jose',
                'last_name'  => 'Doe',
                'email' => 'jose.doe@email.com',
            ]);

        $this->assertDatabaseHas('users', [
            'user_name'  => 'jose_doe',
            'first_name' => 'Jose',
            'last_name'  => 'Doe',
            'email' => 'jose.doe@email.com',
        ]);

        $this->assertDatabaseMissing('users', [
            'user_name'  => 'john_doe',
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'email' => 'john.doe@email.com',
        ]);
    }

    public function testDelete(): void
    {
        Passport::actingAs($this->superAdmin);

        $this->delete('/api/users/'.$this->superAdmin->id)
            ->assertOk()
            ->assertJson([]);

        $this->assertDatabaseMissing('users', [
            'user_name'  => 'john_doe',
            'first_name' => 'John',
            'last_name'  => 'Doe',
            'email' => 'john.doe@email.com',
        ]);
    }

    public function testMe(): void
    {
        Passport::actingAs($this->superAdmin);

        $this->get('/api/users/me')
            ->assertOk()
            ->assertJson([
                'user_name'  => 'john_doe',
                'first_name' => 'John',
                'last_name'  => 'Doe',
                'email' => 'john.doe@email.com',
            ]);
    }

    public function testOnlySuperAdminCanCreateUsers(): void
    {
        $admin = User::factory()->create([
            'user_name'  => 'mr_manager',
            'first_name' => 'Mister',
            'last_name'  => 'Manager',
            'email' => 'mr.manager@email.com',
            'role'  => Role::ADMIN,
        ]);

        Passport::actingAs($admin);

        $this->post('/api/users', [
                'user_name'  => 'jane_doe',
                'first_name' => 'Jane',
                'last_name'  => 'Doe',
                'email'    => 'jane.doe@email.com',
                'password' => 'password',
                'role'     => Role::ADMIN->value,
            ])
            ->assertUnauthorized();
    }

    public function testRegister(): void
    {
        $this->post('/api/oauth/register', [
                'user_name'  => 'jane_doe',
                'first_name' => 'Jane',
                'last_name'  => 'Doe',
                'email'        => 'jane.doe@email.com',
                'password'     => 'password',
                'country_code' => 'ph',
            ])
            ->assertCreated()
            ->assertJson([
                'user_name'  => 'jane_doe',
                'first_name' => 'Jane',
                'last_name'  => 'Doe',
                'email'        => 'jane.doe@email.com',
                'country_code' => 'ph',

                // Should be assigned as player by default
                'role' => Role::USER->value,
            ]);

        // Should create a user default player role
        $this->assertDatabaseHas('users', [
            'user_name'  => 'jane_doe',
            'first_name' => 'Jane',
            'last_name'  => 'Doe',
            'email'        => 'jane.doe@email.com',
            'country_code' => 'ph',
            'role'  => Role::USER->value,
        ]);
    }

    public function testRegisterDuplicateUserEmail(): void
    {
        $this->post('/api/oauth/register', [
            'user_name'  => 'john_james_doe',
            'first_name' => 'John James',
            'last_name'  => 'Doe',
            'email'    => 'john.doe@email.com',
            'password' => 'password',
        ])
        ->assertUnprocessable();
    }
}
