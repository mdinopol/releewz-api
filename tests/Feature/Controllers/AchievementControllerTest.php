<?php

namespace Tests\Feature\Controllers;

use App\Enum\Role;
use App\Models\Achievement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AchievementControllerTest extends TestCase
{
    use RefreshDatabase;

    private Achievement $achievement;

    private User $user;

    private User $admin;

    public function setUp(): void
    {
        parent::setUp();

        $this->achievement = Achievement::factory()->create([
            'name'        => 'Test Achievement',
            'alias'       => 'TAchievement',
            'short'       => 'TA',
            'order'       => 0,
            'is_range'    => false,
            'description' => 'A Test Achievement.',
        ]);

        $this->user = User::factory()->create([
            'user_name'  => 'normal_doe',
            'first_name' => 'Normal',
            'last_name'  => 'Doe',
            'email'      => 'normal_doe@email.com',
            'role'       => Role::USER,
        ]);

        $this->admin = User::factory()->create([
            'user_name'  => 'admin_doe',
            'first_name' => 'Admin',
            'last_name'  => 'Doe',
            'email'      => 'admin_doe@email.com',
            'role'       => Role::ADMIN,
        ]);
    }

    public function testList(): void
    {
        $this->get('/api/achievements')
            ->assertOk()
            ->assertJsonStructure([[
                'name',
                'alias',
                'short',
                'order',
                'is_range',
                'description',
            ]]);
    }

    public function testShow(): void
    {
        $this->get('/api/achievements/'.$this->achievement->id)
            ->assertOk()
            ->assertJson([
                'name'        => $this->achievement->name,
                'alias'       => $this->achievement->alias,
                'short'       => $this->achievement->short,
                'order'       => $this->achievement->order,
                'is_range'    => $this->achievement->is_range,
                'description' => $this->achievement->description,
            ]);
    }

    public function testCreate(): void
    {
        Passport::actingAs($this->admin);

        $this->post('/api/achievements', [
                'name'     => 'Test create achievement',
                'alias' => 'TestCA',
                'short' => 'TCA',
                'order' => 1,
                'is_range' => true,
                'description' => 'A test created achievement.',
            ])
            ->assertCreated()
            ->assertJson([
                'name'     => 'Test create achievement',
                'alias' => 'TestCA',
                'short' => 'TCA',
                'order' => 1,
                'is_range' => true,
                'description' => 'A test created achievement.',
            ]);

        $this->assertDatabaseHas('achievements', [
            'name'     => 'Test create achievement',
            'alias' => 'TestCA',
            'short' => 'TCA',
            'order' => 1,
            'is_range' => true,
            'description' => 'A test created achievement.',
        ]);
    }

    public function testUpdate(): void
    {
        Passport::actingAs($this->admin);

        $this->put('/api/achievements/'.$this->achievement->id, [
            'name'        => 'Test Achievement Updated',
            'alias'       => 'TAchievement Updated',
            'short'       => 'TA Updated',
            'order'       => 1,
            'is_range'    => true,
            'description' => 'A Test Achievement Updated.',
        ])
        ->assertOk()
        ->assertJson([
            'name'        => 'Test Achievement Updated',
            'alias'       => 'TAchievement Updated',
            'short'       => 'TA Updated',
            'order'       => 1,
            'is_range'    => true,
            'description' => 'A Test Achievement Updated.',
        ]);

        $this->assertDatabaseHas('achievements', [
            'name'        => 'Test Achievement Updated',
            'alias'       => 'TAchievement Updated',
            'short'       => 'TA Updated',
            'order'       => 1,
            'is_range'    => true,
            'description' => 'A Test Achievement Updated.',
        ]);
    }

    public function testStoreShouldUnauthorizeNonAdmin(): void
    {
        Passport::actingAs($this->user);

        $this->post('/api/achievements', [
                'name'        => 'Block Percentage',
                'alias'       => 'Blk Pctg.',
                'short'       => 'Blk%%',
                'order'       => 1,
                'is_range'    => true,
                'description' => 'Player block percentage.',
            ])
            ->assertUnauthorized();
    }

    public function testUpdateShouldUnauthorizeNonAdmin(): void
    {
        Passport::actingAs($this->user);

        $this->put('/api/achievements/'.$this->achievement->id, [
                'alias' => 'Test Update 1',
            ])
            ->assertUnauthorized();
    }

    public function testDestroyShouldUnauthorizeNonAdmin(): void
    {
        Passport::actingAs($this->user);

        $this->delete('/api/achievements/'.$this->achievement->id)
            ->assertUnauthorized();
    }

    public function testStoreExistingNameShouldFail(): void
    {
        Passport::actingAs($this->admin);

        $this->post('/api/achievements', [
                'name'     => $this->achievement->name,
                'is_range' => false,
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }

    public function testUpdateToAnExistingNameShouldFail(): void
    {
        Passport::actingAs($this->admin);

        $achievement = Achievement::factory()->create();

        $this->put('/api/achievements/'.$achievement->id, [
                'name' => $this->achievement->name,
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }
}
