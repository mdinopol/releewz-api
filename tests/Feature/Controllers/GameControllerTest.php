<?php

namespace Tests\Feature\Controllers;

use App\Enum\GameDuration;
use App\Enum\GameState;
use App\Enum\GameType;
use App\Enum\Role;
use App\Enum\Sport;
use App\Models\Game;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class GameControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $user;

    private Game $liveGame;


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

        $this->liveGame = Game::factory()->create([
            'tournament_id' => null,
            'name' => 'Test Game 1',
            'short' => 'TG1',
            'slug' => 'test-game-1',
            'description' => 'A test game 1',
            'sport' => Sport::BASKETBALL->value,
            'game_state' => GameState::LIVE->value,
            'duration_type' => GameDuration::SPAN->value,
            'game_type' => GameType::FINALS->value,
            'min_entry' => 5,
            'max_entry' => 20,
            'entry_contestants' => 8,
            'max_entry_value' => 105.5,
            'entry_price' => 2.00,
            'initial_prize_pool' => 10000.00,
            'current_prize_pool' => null,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addYear(),
            'point_template' => null,
        ]);
    }
    
    public function testList(): void
    {
        Game::factory()->create([
            'tournament_id' => null,
            'name' => 'Test Open Registration Game 1',
            'short' => 'TORG1',
            'slug' => 'test-open-registration-game-1',
            'description' => 'A test open registration game 1',
            'sport' => Sport::BASKETBALL->value,
            'game_state' => GameState::OPEN_REGISTRATION->value,
            'duration_type' => GameDuration::SPAN->value,
            'game_type' => GameType::FINALS->value,
            'min_entry' => 5,
            'max_entry' => 20,
            'entry_contestants' => 8,
            'max_entry_value' => 105.5,
            'entry_price' => 2.00,
            'initial_prize_pool' => 10000.00,
            'current_prize_pool' => null,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::tomorrow()->addYear(),
            'point_template' => null,
        ]);

        $this->get('/api/games')
            ->assertOk()
            ->assertJsonStructure($this->paginatedStructure(
                $this->getAssertableJsonStructure()
            ));
    }

    public function testShow(): void
    {
        $this->get('/api/games/'.$this->liveGame->id)
            ->assertOk()
            ->assertJsonStructure(array_merge(
                $this->getAssertableJsonStructure(),
                ['users_count']
            ));
    }
    
    public function testCreate(): void
    {
        Passport::actingAs($this->admin);

        $start = Carbon::now();
        $end = Carbon::now()->addYear();

        $this->post('/api/games', [
            'name' => 'Test Game Create 1',
            'short' => 'TG1C',
            'slug' => 'test-game-create-1',
            'description' => 'A test game create 1',
            'sport' => Sport::BASKETBALL->value,
            'game_state' => GameState::IN_SETUP->value,
            'duration_type' => GameDuration::SPAN->value,
            'game_type' => GameType::FINALS->value,
            'min_entry' => 5,
            'max_entry' => 20,
            'entry_contestants' => 8,
            'max_entry_value' => 105.5,
            'entry_price' => 10,
            'initial_prize_pool' => 200.50,
            'current_prize_pool' => 200.50,
            'start_date' => $start,
            'end_date' => $end,
            'point_template' => null,
        ])
        ->assertCreated()
        ->assertJson([
            'name' => 'Test Game Create 1',
            'short' => 'TG1C',
            'slug' => 'test-game-create-1',
            'description' => 'A test game create 1',
            'sport' => Sport::BASKETBALL->value,
            'game_state' => GameState::IN_SETUP->value,
            'duration_type' => GameDuration::SPAN->value,
            'game_type' => GameType::FINALS->value,
            'min_entry' => 5,
            'max_entry' => 20,
            'entry_contestants' => 8,
            'max_entry_value' => 105.5,
            'entry_price' => 10,
            'initial_prize_pool' => 200.50,
            'current_prize_pool' => 200.50,
        ]);

        $this->assertDatabaseHas('games', [
            'name' => 'Test Game Create 1',
            'short' => 'TG1C',
            'slug' => 'test-game-create-1',
            'description' => 'A test game create 1',
            'sport' => Sport::BASKETBALL->value,
            'game_state' => GameState::IN_SETUP->value,
            'duration_type' => GameDuration::SPAN->value,
            'game_type' => GameType::FINALS->value,
            'min_entry' => 5,
            'max_entry' => 20,
            'entry_contestants' => 8,
            'max_entry_value' => 105.5,
            'entry_price' => 10,
            'initial_prize_pool' => 200.50,
            'current_prize_pool' => 200.50,
        ]);
    }

    public function testCreateShouldUnauthorizeNonAdmin(): void
    {
        Passport::actingAs($this->user);

        $this->post('/api/games', [
            'name' => 'Test Game Create 1',
            'short' => 'TGC1',
            'slug' => 'test-game-create-1',
            'description' => 'A test game create 1',
            'min_entry' => 5,
            'max_entry' => 20,
            'entry_contestants' => 8,
            'max_entry_value' => 105.5,
            'entry_price' => 2.00,
            'initial_prize_pool' => 10000.00,
            'current_prize_pool' => null,
            'start_date' => Carbon::tomorrow(),
            'end_date' => Carbon::tomorrow()->addYear(),
            'point_template' => null,
        ])
        ->assertUnauthorized();
    }

    public function testUpdate(): void
    {
        Passport::actingAs($this->admin);

        $preOpenGame = Game::factory()->create([
            'tournament_id' => null,
            'name' => 'Test Game Update 1',
            'short' => 'TGU1',
            'slug' => 'test-game-update-1',
            'description' => 'A test game 1',
            'sport' => Sport::BASKETBALL->value,
            'game_state' => GameState::IN_SETUP->value,
            'duration_type' => GameDuration::SPAN->value,
            'game_type' => GameType::FINALS->value,
            'min_entry' => 5,
            'max_entry' => 20,
            'entry_contestants' => 8,
            'max_entry_value' => 105.5,
            'entry_price' => 2.00,
            'initial_prize_pool' => 10000.00,
            'current_prize_pool' => null,
        ]);

        $this->put('/api/games/'.$preOpenGame->id, [
            'name' => 'Test Game Updated V2.0',
            'slug' => 'test-game-upgated-v2',
            'game_state' => GameState::OPEN_REGISTRATION->value,
            'game_type' => GameType::REGULAR_SEASON->value,
        ])
        ->assertOk()
        ->assertJson([
            'name' => 'Test Game Updated V2.0',
            'slug' => 'test-game-upgated-v2',
            'game_state' => GameState::OPEN_REGISTRATION->value,
            'game_type' => GameType::REGULAR_SEASON->value,
        ]);

        $this->assertDatabaseHas('games', [
            'tournament_id' => null,
            'name' => 'Test Game Updated V2.0',
            'short' => 'TGU1',
            'slug' => 'test-game-upgated-v2',
            'description' => 'A test game 1',
            'sport' => Sport::BASKETBALL->value,
            'game_state' => GameState::OPEN_REGISTRATION->value,
            'duration_type' => GameDuration::SPAN->value,
            'game_type' => GameType::REGULAR_SEASON->value,
            'min_entry' => 5,
            'max_entry' => 20,
            'entry_contestants' => 8,
            'max_entry_value' => 105.5,
            'entry_price' => 2.00,
            'initial_prize_pool' => 10000.00,
            'current_prize_pool' => null,
        ]);
    }

    public function testUpdateShouldUnauthorizeNonAdmin(): void
    {
        Passport::actingAs($this->user);

        $preOpenGame = Game::factory()->create([
            'name' => 'Test Game Update 1',
            'short' => 'TGU1',
            'slug' => 'test-game-update-1',
            'description' => 'A test update game 1',
            'sport' => Sport::BASKETBALL->value,
            'game_state' => GameState::OPEN_REGISTRATION->value,
            'duration_type' => GameDuration::SPAN->value,
            'game_type' => GameType::FINALS->value,
            'min_entry' => 5,
            'max_entry' => 20,
            'entry_contestants' => 8,
            'max_entry_value' => 105.5,
            'entry_price' => 2.00,
            'initial_prize_pool' => 10000.00,
            'current_prize_pool' => null,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::tomorrow()->addYear(),
            'point_template' => null,
        ]);

        $this->put('/api/games/'.$preOpenGame->id, [
            'name' => 'Test Game Updated V2.0',
            'slug' => 'test-game-upgated-v2',
            'game_type' => GameType::REGULAR_SEASON->value,
        ])
        ->assertUnauthorized();
    }

    public function testUpdateInfoOfPublicGameShouldFail(): void
    {
        Passport::actingAs($this->admin);

        $this->put('/api/games/'.$this->liveGame->id, [
            'name' => 'Test Update Live Game 122',
            'slug' => 'test-update-live-game-122',
        ])
        ->assertForbidden();
    }

    public function testRevertGameStateShouldFail(): void
    {
        Passport::actingAs($this->admin);
        
        $this->put('/api/games/'.$this->liveGame->id, [
            'game_state' => GameState::OPEN_REGISTRATION->value,
        ])
        ->assertForbidden();
    }

    public function testDelete(): void
    {
        Passport::actingAs($this->admin);

        $game = Game::factory()->create([
            'tournament_id' => null,
            'name' => 'Test Game Update 1',
            'short' => 'TGU1',
            'slug' => 'test-game-update-1',
            'description' => 'A test game 1',
            'sport' => Sport::BASKETBALL->value,
            'game_state' => GameState::IN_SETUP->value,
            'duration_type' => GameDuration::SPAN->value,
            'game_type' => GameType::FINALS->value,
            'min_entry' => 5,
            'max_entry' => 20,
            'entry_contestants' => 8,
            'max_entry_value' => 105.5,
            'entry_price' => 2.00,
            'initial_prize_pool' => 10000.00,
            'current_prize_pool' => null,
        ]);

        $this->delete('/api/games/'.$game->id)
            ->assertOk()
            ->assertJson([]);
        
        $this->assertDatabaseMissing('games', [
            'tournament_id' => null,
            'name' => 'Test Game Update 1',
            'short' => 'TGU1',
            'slug' => 'test-game-update-1',
            'description' => 'A test game 1',
            'sport' => Sport::BASKETBALL->value,
            'game_state' => GameState::IN_SETUP->value,
            'duration_type' => GameDuration::SPAN->value,
            'game_type' => GameType::FINALS->value,
            'min_entry' => 5,
            'max_entry' => 20,
            'entry_contestants' => 8,
            'max_entry_value' => 105.5,
            'entry_price' => 2.00,
            'initial_prize_pool' => 10000.00,
            'current_prize_pool' => null,
        ]);
    }

    public function testDeleteShouldUnauthorizeNonAdmin(): void
    {
        Passport::actingAs($this->user);

        $game = Game::factory()->create([
            'game_state' => GameState::IN_SETUP->value,
        ]);

        $this->delete('/api/games/'.$game->id)
            ->assertUnauthorized();
    }

    public function testDeleteLiveGameShouldFail(): void
    {
        Passport::actingAs($this->admin);

        $this->delete('/api/games/'.$this->liveGame->id)
            ->assertForbidden();
    }

    private function getAssertableJsonStructure(): array
    {
        return [
            'tournament_id',
            'name',
            'short',
            'slug',
            'description',
            'sport',
            'game_state',
            'duration_type',
            'game_type',
            'min_entry',
            'max_entry',
            'entry_contestants',
            'max_entry_value',
            'entry_price',
            'initial_prize_pool',
            'current_prize_pool',
            'start_date',
            'end_date',
            'point_template',
        ];
    }
}
