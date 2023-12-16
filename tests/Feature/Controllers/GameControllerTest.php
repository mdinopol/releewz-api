<?php

namespace Tests\Feature\Controllers;

use App\Enum\ContestantType;
use App\Enum\Currency;
use App\Enum\GameDuration;
use App\Enum\GameState;
use App\Enum\GameType;
use App\Enum\License;
use App\Enum\Role;
use App\Enum\Sport;
use App\Models\Contestant;
use App\Models\Game;
use App\Models\Tournament;
use App\Models\User;
use App\Services\GameService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
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

        $this->liveGame = Game::withoutEvents(
            fn () => Game::factory()->create([
                'tournament_id'      => null,
                'name'               => 'Test Game 1',
                'short'              => 'TG1',
                'description'        => 'A test game 1',
                'sport'              => Sport::BASKETBALL->value,
                'game_state'         => GameState::LIVE->value,
                'duration_type'      => GameDuration::SPAN->value,
                'game_type'          => GameType::FINALS->value,
                'contestant_type'    => ContestantType::TEAM_MEMBER->value,
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
            ])
        );
    }

    public function testList(): void
    {
        Game::withoutEvents(
            fn () => Game::factory()->create([
                'tournament_id'      => null,
                'name'               => 'Test Open Registration Game 1',
                'short'              => 'TORG1',
                'description'        => 'A test open registration game 1',
                'sport'              => Sport::BASKETBALL->value,
                'game_state'         => GameState::OPEN_REGISTRATION->value,
                'duration_type'      => GameDuration::SPAN->value,
                'game_type'          => GameType::FINALS->value,
                'contestant_type'    => ContestantType::TEAM_MEMBER->value,
                'min_entry'          => 5,
                'max_entry'          => 20,
                'entry_contestants'  => 8,
                'max_entry_value'    => 105.5,
                'entry_price'        => 2.00,
                'initial_prize_pool' => 10000.00,
                'current_prize_pool' => null,
                'start_date'         => Carbon::now(),
                'end_date'           => Carbon::tomorrow()->addYear(),
                'point_template'     => null,
            ])
        );

        $this->get('/api/games/state/'.GameState::OPEN_REGISTRATION->value.'/sport/'.Sport::BASKETBALL->value)
            ->assertOk()
            ->assertJsonStructure($this->paginatedStructure(
                $this->getAssertableJsonStructure()
            ));
    }

    public function testShowById(): void
    {
        $this->get('/api/games/i/'.$this->liveGame->id)
            ->assertOk()
            ->assertJsonStructure(array_merge(
                $this->getAssertableJsonStructure(),
                ['users']
            ));
    }

    public function testShowBySlug(): void
    {
        // $this->get('/api/games/'.$this->liveGame->id)
        $this->get('/api/games/s/'.$this->liveGame->slug)
            ->assertOk()
            ->assertJsonStructure(array_merge(
                $this->getAssertableJsonStructure(),
                ['users']
            ));
    }

    public function testCreate(): void
    {
        Passport::actingAs($this->admin);

        $start = Carbon::now();
        $end   = Carbon::now()->addYear();

        $this->post('/api/games', [
            'name'               => 'Test Game Create 1',
            'short'              => 'TG1C',
            'description'        => 'A test game create 1',
            'sport'              => Sport::BASKETBALL->value,
            'duration_type'      => GameDuration::SPAN->value,
            'game_type'          => GameType::FINALS->value,
            'contestant_type'    => ContestantType::TEAM_MEMBER->value,
            'min_entry'          => 5,
            'max_entry'          => 20,
            'entry_contestants'  => 8,
            'max_entry_value'    => 105.5,
            'entry_price'        => 10,
            'initial_prize_pool' => 200.50,
            'current_prize_pool' => 200.50,
            'start_date'         => $start,
            'end_date'           => $end,
            'point_template'     => null,
        ])
        ->assertCreated()
        ->assertJson([
            'name'        => 'Test Game Create 1',
            'short'       => 'TG1C',
            'description' => 'A test game create 1',
            'sport'       => Sport::BASKETBALL->value,

            // Should be default game state
            'game_state' => GameState::getDefault()->value,

            'duration_type'      => GameDuration::SPAN->value,
            'game_type'          => GameType::FINALS->value,
            'contestant_type'    => ContestantType::TEAM_MEMBER->value,
            'min_entry'          => 5,
            'max_entry'          => 20,
            'entry_contestants'  => 8,
            'max_entry_value'    => 105.5,
            'entry_price'        => 10,
            'initial_prize_pool' => 200.50,
            'current_prize_pool' => 200.50,
        ]);

        $this->assertDatabaseHas('games', [
            'name'        => 'Test Game Create 1',
            'short'       => 'TG1C',
            'description' => 'A test game create 1',
            'sport'       => Sport::BASKETBALL->value,

            // Should be default game state
            'game_state' => GameState::getDefault()->value,

            'duration_type'      => GameDuration::SPAN->value,
            'game_type'          => GameType::FINALS->value,
            'contestant_type'    => ContestantType::TEAM_MEMBER->value,
            'min_entry'          => 5,
            'max_entry'          => 20,
            'entry_contestants'  => 8,
            'max_entry_value'    => 105.5,
            'entry_price'        => 10,
            'initial_prize_pool' => 200.50,
            'current_prize_pool' => 200.50,
        ]);
    }

    public function testCreateShouldUnauthorizeNonAdmin(): void
    {
        Passport::actingAs($this->user);

        $this->post('/api/games', [
            'name'               => 'Test Game Create 1',
            'short'              => 'TGC1',
            'description'        => 'A test game create 1',
            'min_entry'          => 5,
            'max_entry'          => 20,
            'entry_contestants'  => 8,
            'max_entry_value'    => 105.5,
            'entry_price'        => 2.00,
            'initial_prize_pool' => 10000.00,
            'current_prize_pool' => null,
            'start_date'         => Carbon::tomorrow(),
            'end_date'           => Carbon::tomorrow()->addYear(),
            'point_template'     => null,
        ])
        ->assertUnauthorized();
    }

    public function testUpdate(): void
    {
        Passport::actingAs($this->admin);

        $preOpenGame = Game::factory()->create([
            'tournament_id'      => null,
            'name'               => 'Test Game Update 1',
            'short'              => 'TGU1',
            'description'        => 'A test game 1',
            'sport'              => Sport::BASKETBALL->value,
            'duration_type'      => GameDuration::SPAN->value,
            'game_type'          => GameType::FINALS->value,
            'contestant_type'    => ContestantType::TEAM_MEMBER->value,
            'min_entry'          => 5,
            'max_entry'          => 20,
            'entry_contestants'  => 8,
            'max_entry_value'    => 105.5,
            'entry_price'        => 2.00,
            'initial_prize_pool' => 10000.00,
            'current_prize_pool' => null,
        ]);

        $this->put('/api/games/'.$preOpenGame->id, [
            'name'      => 'Test Game Updated V2.0',
            'slug'      => Str::slug('Test Game Updated V2.0'),
            'game_type' => GameType::REGULAR_SEASON->value,
        ])
        ->assertOk()
        ->assertJson([
            'tournament_id'      => null,
            'name'               => 'Test Game Updated V2.0',
            'slug'               => Str::slug('Test Game Updated V2.0'),
            'short'              => 'TGU1',
            'description'        => 'A test game 1',
            'sport'              => Sport::BASKETBALL->value,
            'game_state'         => GameState::getDefault()->value,
            'duration_type'      => GameDuration::SPAN->value,
            'game_type'          => GameType::REGULAR_SEASON->value,
            'contestant_type'    => ContestantType::TEAM_MEMBER->value,
            'min_entry'          => 5,
            'max_entry'          => 20,
            'entry_contestants'  => 8,
            'max_entry_value'    => 105.5,
            'entry_price'        => 2.00,
            'initial_prize_pool' => 10000.00,
            'current_prize_pool' => null,
        ]);

        $this->assertDatabaseHas('games', [
            'tournament_id'      => null,
            'name'               => 'Test Game Updated V2.0',
            'slug'               => Str::slug('Test Game Updated V2.0'),
            'short'              => 'TGU1',
            'description'        => 'A test game 1',
            'sport'              => Sport::BASKETBALL->value,
            'game_state'         => GameState::getDefault()->value,
            'duration_type'      => GameDuration::SPAN->value,
            'game_type'          => GameType::REGULAR_SEASON->value,
            'contestant_type'    => ContestantType::TEAM_MEMBER->value,
            'min_entry'          => 5,
            'max_entry'          => 20,
            'entry_contestants'  => 8,
            'max_entry_value'    => 105.5,
            'entry_price'        => 2.00,
            'initial_prize_pool' => 10000.00,
            'current_prize_pool' => null,
        ]);
    }

    public function testUpdateShouldUnauthorizeNonAdmin(): void
    {
        Passport::actingAs($this->user);

        $preOpenGame = Game::factory()->create([
            'name'               => 'Test Game Update 1',
            'short'              => 'TGU1',
            'description'        => 'A test update game 1',
            'sport'              => Sport::BASKETBALL->value,
            'duration_type'      => GameDuration::SPAN->value,
            'game_type'          => GameType::FINALS->value,
            'contestant_type'    => ContestantType::TEAM_MEMBER->value,
            'min_entry'          => 5,
            'max_entry'          => 20,
            'entry_contestants'  => 8,
            'max_entry_value'    => 105.5,
            'entry_price'        => 2.00,
            'initial_prize_pool' => 10000.00,
            'current_prize_pool' => null,
            'start_date'         => Carbon::now(),
            'end_date'           => Carbon::tomorrow()->addYear(),
            'point_template'     => null,
        ]);

        $this->put('/api/games/'.$preOpenGame->id, [
            'name'      => 'Test Game Updated V2.0',
            'slug'      => 'test-game-upgated-v2',
            'game_type' => GameType::REGULAR_SEASON->value,
        ])
        ->assertUnauthorized();
    }

    public function testUpdateImmutableGameShouldFail(): void
    {
        Passport::actingAs($this->admin);

        $this->put('/api/games/'.$this->liveGame->id, [
            'name' => 'Test Update Live Game 122',
            'slug' => 'test-update-live-game-122',
        ])
        ->assertForbidden();
    }

    public function testUpdateGameState(): void
    {
        Passport::actingAs($this->admin);

        Carbon::setTestNow(Carbon::now());

        $tournament = Tournament::factory()->create();

        $preOpenGame = Game::factory()->create([
            'tournament_id'      => $tournament->id,
            'name'               => 'Best Game of The Year',
            'short'              => 'BGY',
            'description'        => 'The best game of the year',
            'sport'              => Sport::BASKETBALL->value,
            'duration_type'      => GameDuration::SPAN->value,
            'game_type'          => GameType::FINALS->value,
            'contestant_type'    => ContestantType::TEAM_MEMBER->value,
            'min_entry'          => 5,
            'max_entry'          => 20,
            'entry_contestants'  => 8,
            'max_entry_value'    => 105.5,
            'entry_price'        => 2.00,
            'initial_prize_pool' => 10000.00,
            'current_prize_pool' => null,
            'start_date'         => Carbon::now(),
            'end_date'           => Carbon::tomorrow()->addYear(),
            'point_template'     => null,
        ]);

        $this->put('/api/games/'.$preOpenGame->id.'/state/'.GameState::OPEN_REGISTRATION->value)
        ->assertOk()
        ->assertJson([
            'tournament_id'      => $tournament->id,
            'name'               => 'Best Game of The Year',
            'short'              => 'BGY',
            'description'        => 'The best game of the year',
            'sport'              => Sport::BASKETBALL->value,
            'game_state'         => GameState::OPEN_REGISTRATION->value,
            'duration_type'      => GameDuration::SPAN->value,
            'game_type'          => GameType::FINALS->value,
            'contestant_type'    => ContestantType::TEAM_MEMBER->value,
            'min_entry'          => 5,
            'max_entry'          => 20,
            'entry_contestants'  => 8,
            'max_entry_value'    => 105.5,
            'entry_price'        => 2.00,
            'initial_prize_pool' => 10000.00,
            'current_prize_pool' => null,
            'point_template'     => null,
        ]);

        $this->assertDatabaseMissing('games', [
            'tournament_id'      => $tournament->id,
            'name'               => 'Best Game of The Year',
            'short'              => 'BGY',
            'description'        => 'The best game of the year',
            'sport'              => Sport::BASKETBALL->value,
            'game_state'         => GameState::getDefault()->value,
            'duration_type'      => GameDuration::SPAN->value,
            'game_type'          => GameType::FINALS->value,
            'contestant_type'    => ContestantType::TEAM_MEMBER->value,
            'min_entry'          => 5,
            'max_entry'          => 20,
            'entry_contestants'  => 8,
            'max_entry_value'    => 105.5,
            'entry_price'        => 2.00,
            'initial_prize_pool' => 10000.00,
            'current_prize_pool' => null,
            'point_template'     => null,
        ]);

        $this->assertDatabaseHas('games', [
            'tournament_id'      => $tournament->id,
            'name'               => 'Best Game of The Year',
            'short'              => 'BGY',
            'description'        => 'The best game of the year',
            'sport'              => Sport::BASKETBALL->value,
            'game_state'         => GameState::OPEN_REGISTRATION->value,
            'duration_type'      => GameDuration::SPAN->value,
            'game_type'          => GameType::FINALS->value,
            'contestant_type'    => ContestantType::TEAM_MEMBER->value,
            'min_entry'          => 5,
            'max_entry'          => 20,
            'entry_contestants'  => 8,
            'max_entry_value'    => 105.5,
            'entry_price'        => 2.00,
            'initial_prize_pool' => 10000.00,
            'current_prize_pool' => null,
            'point_template'     => null,
        ]);
    }

    public function testRevertGameStateShouldFail(): void
    {
        Passport::actingAs($this->admin);

        $this->put('/api/games/'.$this->liveGame->id.'/state/'.GameState::OPEN_REGISTRATION->value)
        ->assertUnprocessable();
    }

    public function testDelete(): void
    {
        Passport::actingAs($this->admin);

        $game = Game::factory()->create([
            'tournament_id'      => null,
            'name'               => 'Test Game Update 1',
            'short'              => 'TGU1',
            'description'        => 'A test game 1',
            'sport'              => Sport::BASKETBALL->value,
            'duration_type'      => GameDuration::SPAN->value,
            'game_type'          => GameType::FINALS->value,
            'contestant_type'    => ContestantType::TEAM_MEMBER->value,
            'min_entry'          => 5,
            'max_entry'          => 20,
            'entry_contestants'  => 8,
            'max_entry_value'    => 105.5,
            'entry_price'        => 2.00,
            'initial_prize_pool' => 10000.00,
            'current_prize_pool' => null,
        ]);

        $this->delete('/api/games/'.$game->id)
            ->assertOk()
            ->assertJson([]);

        $this->assertDatabaseMissing('games', [
            'tournament_id'      => null,
            'name'               => 'Test Game Update 1',
            'short'              => 'TGU1',
            'slug'               => $game->slug,
            'description'        => 'A test game 1',
            'sport'              => Sport::BASKETBALL->value,
            'game_state'         => GameState::getDefault()->value,
            'duration_type'      => GameDuration::SPAN->value,
            'game_type'          => GameType::FINALS->value,
            'contestant_type'    => ContestantType::TEAM_MEMBER->value,
            'min_entry'          => 5,
            'max_entry'          => 20,
            'entry_contestants'  => 8,
            'max_entry_value'    => 105.5,
            'entry_price'        => 2.00,
            'initial_prize_pool' => 10000.00,
            'current_prize_pool' => null,
        ]);
    }

    public function testDeleteShouldUnauthorizeNonAdmin(): void
    {
        Passport::actingAs($this->user);

        $game = Game::factory()->create();

        $this->delete('/api/games/'.$game->id)
            ->assertUnauthorized();
    }

    public function testDeleteLiveGameShouldFail(): void
    {
        Passport::actingAs($this->admin);

        $this->delete('/api/games/'.$this->liveGame->id)
            ->assertForbidden();
    }

    public function testSyncStartlist(): void
    {
        Passport::actingAs($this->admin);

        $memberCount = 3;

        $game    = Game::factory()->create(['contestant_type' => ContestantType::TEAM_MEMBER, 'sport' => Sport::BASKETBALL->value]);
        $team    = Contestant::factory()->create(['contestant_type' => ContestantType::TEAM]);
        $members = Contestant::factory($memberCount)->create([
            'parent_id'       => $team->id,
            'contestant_type' => ContestantType::TEAM_MEMBER,
            'sport'           => Sport::BASKETBALL->value,
        ]);
        $individual        = Contestant::factory()->create(['contestant_type' => ContestantType::INDIVIDUAL]);
        $membersWithValues = array_map(fn ($id) => [
            'id'    => $id,
            'value' => fake()->randomFloat('2', 10, 50),
        ], $members->pluck('id')->toArray());

        // Assert that all member type are allowed if they are assigned to the same contestant type game
        $this->post('/api/games/'.$game->id.'/startlist', [
            'contestants' => $membersWithValues,
        ])
        ->assertOk();

        // Assert that assigning contestant with different type from game's contestant type should fail
        $this->post('/api/games/'.$game->id.'/startlist', [
            'contestants' => [['id' => $team->id]],
        ])
        ->assertUnprocessable();
        $this->post('/api/games/'.$game->id.'/startlist', [
            'contestants' => [['id' => $individual->id]],
        ])
        ->assertUnprocessable();
        $this->post('/api/games/'.$game->id.'/startlist', [
            'contestants' => [['id' => $team->id], ['id' => $individual->id]],
        ])
        ->assertUnprocessable();

        // Assert that assigning array of valid contestants mixed with at least a single invalid contestant should fail
        $this->post('/api/games/'.$game->id.'/startlist', [
            'contestants' => array_merge($membersWithValues, [['id' => $team->id], ['id' => $individual->id]]),
        ])
        ->assertUnprocessable();

        // Assert that the only synced contestants are the members
        $this->assertCount($memberCount, $game->contestants);
    }

    public function testCreateUserEntry(): void
    {
        Passport::actingAs($this->user);

        $memberCount = 3;

        $contestantType = ContestantType::TEAM_MEMBER;
        $sport          = Sport::BASKETBALL;

        $game = Game::withoutEvents(
            fn () => Game::factory()->create([
                'tournament_id'   => Tournament::factory()->create()->id,
                'contestant_type' => $contestantType,
                'game_state'      => GameState::OPEN_REGISTRATION,
                'sport'           => $sport,
            ])
        );
        $team    = Contestant::factory()->create(['contestant_type' => ContestantType::TEAM, 'sport' => $sport]);
        $members = Contestant::factory($memberCount)->create([
            'parent_id'       => $team->id,
            'contestant_type' => $contestantType,
            'sport'           => $sport,
        ])->pluck('id');

        $contestantIds = $members->map(fn ($id) => ['id' => $id]);
        app(GameService::class)->syncStartlist($game, $contestantIds->toArray());
        // $game->contestants()->sync($members);

        $this->post('/api/games/'.$game->id.'/entries', [
            'name'                 => 'Entry 1',
            'contestants'          => $members->toArray(),
            'license_at_creation'  => License::MALTA_EUR->value,
            'currency_at_creation' => Currency::EUR->value,
        ])
        ->assertOk();

        $this->assertCount(1, $this->user->games);
        $this->assertDatabaseHas('entries', [
            'user_id'              => $this->user->id,
            'game_id'              => $game->id,
            'name'                 => 'Entry 1',
            'total_points'         => null,
            'points_history'       => null,
            'extra_predictions'    => null,
            'license_at_creation'  => License::MALTA_EUR->value,
            'currency_at_creation' => Currency::EUR->value,
        ]);
    }

    public function testCreateUserEntryForPublicGameShouldFail(): void
    {
        Passport::actingAs($this->user);

        $memberCount = 3;

        $contestantType = ContestantType::TEAM_MEMBER;
        $sport          = Sport::BASKETBALL;

        $game = Game::withoutEvents(
            fn () => Game::factory()->create([
                'tournament_id'   => Tournament::factory()->create()->id,
                'contestant_type' => $contestantType,
                'game_state'      => GameState::PRE_LIVE,
                'sport'           => $sport,
            ])
        );
        $team    = Contestant::factory()->create(['contestant_type' => ContestantType::TEAM, 'sport' => $sport]);
        $members = Contestant::factory($memberCount)->create([
            'parent_id'       => $team->id,
            'contestant_type' => $contestantType,
            'sport'           => $sport,
        ])->pluck('id');

        $contestantIds = $members->map(fn ($id) => ['id' => $id]);
        app(GameService::class)->syncStartlist($game, $contestantIds->toArray());
        // $game->contestants()->sync($members);

        $this->post('/api/games/'.$game->id.'/entries', [
            'name'                 => 'Entry 1',
            'contestants'          => $members->toArray(),
            'license_at_creation'  => License::MALTA_EUR->value,
            'currency_at_creation' => Currency::EUR->value,
        ])
        ->assertForbidden();

        $this->assertCount(0, $this->user->games);
    }

    public function testCreateUserEntryExceedingValueLimitShouldFail(): void
    {
        Passport::actingAs($this->user);

        $memberCount = 3;

        $contestantType = ContestantType::TEAM_MEMBER;
        $sport          = Sport::BASKETBALL;

        $game = Game::withoutEvents(
            fn () => Game::factory()->create([
                'tournament_id'   => Tournament::factory()->create()->id,
                'contestant_type' => $contestantType,
                'game_state'      => GameState::OPEN_REGISTRATION,
                'sport'           => $sport,
                'max_entry_value' => 1,
            ])
        );
        $team    = Contestant::factory()->create(['contestant_type' => ContestantType::TEAM, 'sport' => $sport]);
        $members = Contestant::factory($memberCount)->create([
            'parent_id'       => $team->id,
            'contestant_type' => $contestantType,
            'sport'           => $sport,
        ])->pluck('id');

        $contestantIds = $members->map(fn ($id) => ['id' => $id]);
        app(GameService::class)->syncStartlist($game, $contestantIds->toArray());
        // $game->contestants()->sync($members);

        $this->post('/api/games/'.$game->id.'/entries', [
            'name'                 => 'Entry 1',
            'contestants'          => $members->toArray(),
            'license_at_creation'  => License::MALTA_EUR->value,
            'currency_at_creation' => Currency::EUR->value,
        ])
        ->assertOk();

        $this->assertCount(1, $this->user->games);
        $this->assertDatabaseHas('entries', [
            'user_id'              => $this->user->id,
            'game_id'              => $game->id,
            'name'                 => 'Entry 1',
            'total_points'         => null,
            'points_history'       => null,
            'extra_predictions'    => null,
            'license_at_creation'  => License::MALTA_EUR->value,
            'currency_at_creation' => Currency::EUR->value,
        ]);
    }

    public function testSetGamePointTemplate(): void
    {
        Passport::actingAs($this->admin);

        $sport    = Sport::active()[0];
        $template = $sport->template();

        $game = Game::withoutEvents(
            fn () => Game::factory()->create([
                'tournament_id' => Tournament::factory()->create()->id,
                'game_state'    => GameState::OPEN_REGISTRATION,
                'sport'         => $sport->value,
            ])
        );

        $filledTemplate = [];

        // Decision-based achievement
        foreach ($template['decisions'] as $key => $value) {
            $filledTemplate['decisions'][$key] = rand(1, 100);
        }

        // Fillable achievement (basic)
        foreach ($template['fillables']['basic'] as $key => $value) {
            $filledTemplate['fillables']['basic'][$key] = rand(1, 100);
        }

        // Fillable achievement (range)
        foreach ($template['fillables']['range'] as $key => $value) {
            foreach ($value as $range => $value) {
                $filledTemplate['fillables']['range'][$key][$range] = array_merge($value, ['value' => rand(1, 100)]);
            }
        }

        // Extra achievements
        foreach ($template['extras'] as $key => $value) {
            $filledTemplate['extras'][$key] = rand(1, 100);
        }

        // Assert that normal setting of point template succeeds
        $this->post('/api/games/'.$game->id.'/point-template', [
            'template' => $filledTemplate,
        ])
        ->assertOk()
        ->assertJson([
            'sport'          => $sport->value,
            'game_state'     => GameState::OPEN_REGISTRATION->value,
            'point_template' => $filledTemplate,
        ]);

        // Assert that even 1 rouge achievement added to the template will fail
        $filledTemplate['decisions']['test'] = 10;
        $this->post('/api/games/'.$game->id.'/point-template', [
            'template' => $filledTemplate,
        ])
        ->assertUnprocessable();
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
            'contestant_type',
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
