<?php

namespace Tests\Feature\Controllers;

use App\Enum\Role;
use App\Models\Bout;
use App\Models\Contestant;
use App\Models\Matchup;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class MatchupControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $user;

    private Bout $bout;

    private Contestant $home;

    private Contestant $away;

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

        $this->bout = Bout::factory()->create();
        $this->home = Contestant::factory()->create();
        $this->away = Contestant::factory()->create();
    }

    public function testList(): void
    {
        Matchup::factory(2)->create();

        $this->get('/api/matchups')
            ->assertOk()
            ->assertJsonStructure([[
                'bout_id',
                'home_id',
                'away_id',
                'start_date',
                'end_date',
            ]]);
    }

    public function testShow(): void
    {
        $matchup = Matchup::factory()->create();

        $this->get('/api/matchups/'.$matchup->id)
            ->assertOk()
            ->assertJsonStructure([
                'bout_id',
                'home_id',
                'away_id',
                'start_date',
                'end_date',
                'bout',
                'home',
                'away',
                'scores',
            ]);
    }

    public function testCreate(): void
    {
        Passport::actingAs($this->admin);

        $this->post('/api/matchups', [
            'bout_id' => $this->bout->id,
            'home_id' => $this->home->id,
            'away_id' => $this->away->id,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addHour(),
        ])
        ->assertCreated()
        ->assertJson([
            'bout_id' => $this->bout->id,
            'home_id' => $this->home->id,
            'away_id' => $this->away->id,
        ]);

        $this->assertDatabaseHas('matchups', [
            'bout_id' => $this->bout->id,
            'home_id' => $this->home->id,
            'away_id' => $this->away->id,
        ]);
    }

    public function testCreateDuplicateForGameShouldFail(): void
    {
        Passport::actingAs($this->admin);

        Matchup::factory()->create([
            'bout_id' => $this->bout->id,
            'home_id' => $this->home->id,
            'away_id' => $this->away->id,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addHour(),
        ]);

        $this->post('/api/matchups', [
            'bout_id' => $this->bout->id,
            'home_id' => $this->home->id,
            'away_id' => $this->away->id,
            'start_date' => Carbon::now()->addHours(2),
            'end_date' => Carbon::now()->addHours(3),
        ])
        ->assertUnprocessable()
        ->assertInvalid(['bout_id']);

        $matchups = Matchup::where([
            'bout_id' => $this->bout->id,
            'home_id' => $this->home->id,
            'away_id' => $this->away->id,
        ])->get();

        $this->assertCount(1, $matchups);
    }

    public function testCreateShouldUnauthorizeNonAdmin(): void
    {
        Passport::actingAs($this->user);

        $this->post('/api/matchups', [
            'bout_id' => $this->bout->id,
            'home_id' => $this->home->id,
            'away_id' => $this->away->id,
            'start_date' => Carbon::now()->addHours(2),
            'end_date' => Carbon::now()->addHours(3),
        ])
        ->assertUnauthorized();
    }

    public function testUpdate(): void
    {
        Passport::actingAs($this->admin);

        $newBout = Bout::factory()->create();

        $matchup = Matchup::factory()->create([
            'bout_id' => $this->bout->id,
            'home_id' => $this->home->id,
            'away_id' => $this->away->id,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addHour(),
        ]);

        $this->put('/api/matchups/'.$matchup->id, [
            'bout_id' => $newBout->id,
        ])
        ->assertOk()
        ->assertJson([
            'bout_id' => $newBout->id,
            'home_id' => $this->home->id,
            'away_id' => $this->away->id,
        ]);

        $this->assertDatabaseMissing('matchups', [
            'bout_id' => $this->bout->id,
            'home_id' => $this->home->id,
            'away_id' => $this->away->id,
        ]);

        $this->assertDatabaseHas('matchups', [
            'bout_id' => $newBout->id,
            'home_id' => $this->home->id,
            'away_id' => $this->away->id,
        ]);
    }

    public function testUpdateDuplicateForGameShouldFail(): void
    {
        Passport::actingAs($this->admin);

        $bout = Bout::factory()->create();
        $home = Contestant::factory()->create();
        $away = Contestant::factory()->create();

        // Existing matchup
        Matchup::factory()->create([
            'bout_id' => $this->bout->id,
            'home_id' => $home->id,
            'away_id' => $away->id,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addHour(),
        ]);

        $matchup = Matchup::factory()->create([
            'bout_id' => $this->bout->id,
            'home_id' => $this->home->id,
            'away_id' => $this->away->id,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addHour(),
        ]);



        $this->put('/api/matchups/'.$matchup->id, [
            'home_id' => $home->id,
            'away_id' => $away->id,
        ])
        ->assertUnprocessable()
        ->assertInvalid(['bout_id']);

        $existingMatchup = Matchup::where([
            'bout_id' => $this->bout->id,
            'home_id' => $home->id,
            'away_id' => $away->id,
        ])->get();

        $this->assertCount(1, $existingMatchup);
    }
}
