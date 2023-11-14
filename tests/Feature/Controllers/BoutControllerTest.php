<?php

namespace Tests\Feature\Controllers;

use App\Enum\GameDuration;
use App\Enum\GameState;
use App\Enum\GameType;
use App\Enum\Role;
use App\Enum\Sport;
use App\Models\Bout;
use App\Models\Game;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class BoutControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private User $admin;

    private Bout $bout;

    private Game $game;

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

        $this->user = User::factory()->create([
            'user_name'  => 'normal_doe',
            'first_name' => 'Normal',
            'last_name'  => 'Doe',
            'email'      => 'normal_doe@email.com',
            'role'       => Role::USER,
        ]);

        $this->game = Game::factory()->create([
            'tournament_id'      => null,
            'name'               => 'Test Game 1',
            'short'              => 'TG1',
            'description'        => 'A test game 1',
            'sport'              => Sport::BASKETBALL->value,
            'game_state'         => GameState::LIVE->value,
            'duration_type'      => GameDuration::SPAN->value,
            'game_type'          => GameType::FINALS->value,
            'min_entry'          => 5,
            'max_entry'          => 20,
            'entry_contestants'  => 8,
            'max_entry_value'    => 105.5,
            'entry_price'        => 2.00,
            'initial_prize_pool' => 10000.00,
            'current_prize_pool' => null,
            'start_date'         => Carbon::now(),
            'end_date'           => Carbon::now()->addYear(),
            'point_template'     => null,
        ]);

        $this->bout = Bout::factory()->create([
            'game_id' => $this->game->id,
            'name'    => 'Test Bout 1',
        ]);
    }

    public function testList(): void
    {
        Bout::factory(2)->create();

        $this->get('/api/bouts')
            ->assertOk()
            ->assertJsonStructure([[
                'game_id',
                'name',
            ]]);
    }

    public function testShow(): void
    {
        $this->get('/api/bouts/'.$this->bout->id)
            ->assertOk()
            ->assertJson([
                'game_id'  => $this->game->id,
                'name'     => 'Test Bout 1',
                'matchups' => [],
            ]);
    }

    public function testCreate(): void
    {
        Passport::actingAs($this->admin);

        $this->post('/api/bouts', [
            'game_id' => $this->game->id,
            'name'    => 'Test Bout Create 1',
        ])
        ->assertCreated()
        ->assertJson([
            'game_id' => $this->game->id,
            'name'    => 'Test Bout Create 1',
        ]);

        $this->assertDatabaseHas('bouts', [
            'game_id' => $this->game->id,
            'name'    => 'Test Bout Create 1',
        ]);
    }

    public function testCreateSameNameForGameShouldFail(): void
    {
        Passport::actingAs($this->admin);

        $this->post('/api/bouts', [
            'game_id' => $this->bout->game_id,
            'name'    => $this->bout->name,
        ])
        ->assertUnprocessable()
        ->assertInvalid(['name']);
    }

    public function testCreateShouldUnauthorizeNonAdmin(): void
    {
        Passport::actingAs($this->user);

        $this->post('/api/bouts', [
            'game_id' => $this->game->id,
            'name'    => 'Test create by User',
        ])
        ->assertUnauthorized();
    }

    public function testUpdate(): void
    {
        Passport::actingAs($this->admin);

        $this->put('/api/bouts/'.$this->bout->id, [
            'name' => 'Test Bout Updated 1',
        ])
        ->assertOk()
        ->assertJson([
            'game_id' => $this->bout->game_id,
            'name'    => 'Test Bout Updated 1',
        ]);

        $this->assertDatabaseHas('bouts', [
            'game_id' => $this->bout->game_id,
            'name'    => 'Test Bout Updated 1',
        ]);
    }

    public function testUpdateSameNameForGameShouldFail(): void
    {
        Passport::actingAs($this->admin);

        $bout = Bout::factory()->create([
            'game_id' => $this->game->id,
            'name'    => 'Test Bout 2',
        ]);

        $this->put('/api/bouts/'.$bout->id, [
            'name' => $this->bout->name,
        ])
        ->assertUnprocessable()
        ->assertInvalid(['name']);
    }

    public function testUpdateShouldUnauthorizeNonAdmin(): void
    {
        Passport::actingAs($this->user);

        $this->put('/api/bouts/'.$this->bout->id, [
            'name' => 'Test update by User',
        ])
        ->assertUnauthorized();
    }

    public function testDelete(): void
    {
        Passport::actingAs($this->admin);

        $gameId = $this->bout->game_id;
        $name   = $this->bout->name;

        $this->delete('/api/bouts/'.$this->bout->id)
            ->assertOk()
            ->assertJson([]);

        $this->assertDatabaseMissing('bouts', [
            'game_id' => $gameId,
            'name'    => $name,
        ]);
    }

    public function testDeleteShouldUnauthorizeNonAdmin(): void
    {
        Passport::actingAs($this->user);

        $gameId = $this->bout->game_id;
        $name   = $this->bout->name;

        $this->delete('/api/bouts/'.$this->bout->id)
            ->assertUnauthorized();

        $this->assertDatabaseHas('bouts', [
            'game_id' => $gameId,
            'name'    => $name,
        ]);
    }
}
