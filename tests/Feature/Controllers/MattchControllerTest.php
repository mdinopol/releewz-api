<?php

namespace Tests\Feature\Controllers;

use App\Enum\Achievement;
use App\Enum\Role;
use App\Models\Contestant;
use App\Models\Mattch;
use App\Models\Score;
use App\Models\Tournament;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Passport\Passport;
use Mockery\Generator\StringManipulation\Pass\Pass;
use Tests\TestCase;

class MattchControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $user;

    private Tournament $tournament;

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

        $this->tournament = Tournament::factory()->create();
        $this->home       = Contestant::factory()->create();
        $this->away       = Contestant::factory()->create();
    }

    public function testList(): void
    {
        Mattch::factory(2)->create();

        $this->get('/api/mattches')
            ->assertOk()
            ->assertJsonStructure([[
                'tournament_id',
                'home_id',
                'away_id',
                'start_date',
                'end_date',
            ]]);
    }

    public function testShow(): void
    {
        $mattch = Mattch::factory()->create();

        $this->get('/api/mattches/'.$mattch->id)
            ->assertOk()
            ->assertJsonStructure([
                'tournament_id',
                'home_id',
                'away_id',
                'start_date',
                'end_date',
                'tournament',
                'home',
                'away',
            ]);
    }

    public function testCreate(): void
    {
        Passport::actingAs($this->admin);

        $this->post('/api/mattches', [
            'tournament_id' => $this->tournament->id,
            'home_id'       => $this->home->id,
            'away_id'       => $this->away->id,
            'start_date'    => now(),
            'end_date'      => now()->addHour(),
        ])
        ->assertCreated()
        ->assertJson([
            'tournament_id' => $this->tournament->id,
            'home_id'       => $this->home->id,
            'away_id'       => $this->away->id,
        ]);

        $this->assertDatabaseHas('mattches', [
            'tournament_id' => $this->tournament->id,
            'home_id'       => $this->home->id,
            'away_id'       => $this->away->id,
        ]);
    }

    public function testCreateDuplicateShouldFail(): void
    {
        Passport::actingAs($this->admin);

        Mattch::factory()->create([
            'tournament_id' => $this->tournament->id,
            'home_id'       => $this->home->id,
            'away_id'       => $this->away->id,
            'start_date'    => now(),
            'end_date'      => now()->addHour(),
        ]);

        $this->post('/api/mattches', [
            'tournament_id' => $this->tournament->id,
            'home_id'       => $this->home->id,
            'away_id'       => $this->away->id,
            'start_date'    => now()->addHours(2),
            'end_date'      => now()->addHours(3),
        ])
        ->assertUnprocessable()
        ->assertInvalid(['tournament_id']);

        $mattches = Mattch::where([
            'tournament_id' => $this->tournament->id,
            'home_id'       => $this->home->id,
            'away_id'       => $this->away->id,
        ])->get();

        $this->assertCount(1, $mattches);
    }

    public function testCreateShouldUnauthorizeNonAdmin(): void
    {
        Passport::actingAs($this->user);

        $this->post('/api/mattches', [
            'tournament_id' => $this->tournament->id,
            'home_id'       => $this->home->id,
            'away_id'       => $this->away->id,
            'start_date'    => now()->addHours(2),
            'end_date'      => now()->addHours(3),
        ])
        ->assertUnauthorized();
    }

    public function testUpdate(): void
    {
        Passport::actingAs($this->admin);

        $newTournament = Tournament::factory()->create();

        $mattch = Mattch::factory()->create([
            'tournament_id' => $this->tournament->id,
            'home_id'       => $this->home->id,
            'away_id'       => $this->away->id,
            'start_date'    => now(),
            'end_date'      => now()->addHour(),
        ]);

        $this->put('/api/mattches/'.$mattch->id, [
            'tournament_id' => $newTournament->id,
        ])
        ->assertOk()
        ->assertJson([
            'tournament_id' => $newTournament->id,
            'home_id'       => $this->home->id,
            'away_id'       => $this->away->id,
        ]);

        $this->assertDatabaseMissing('mattches', [
            'tournament_id' => $this->tournament->id,
            'home_id'       => $this->home->id,
            'away_id'       => $this->away->id,
        ]);

        $this->assertDatabaseHas('mattches', [
            'tournament_id' => $newTournament->id,
            'home_id'       => $this->home->id,
            'away_id'       => $this->away->id,
        ]);
    }

    public function testUpdateDuplicateShouldFail(): void
    {
        Passport::actingAs($this->admin);

        $home = Contestant::factory()->create();
        $away = Contestant::factory()->create();

        // Existing match
        Mattch::factory()->create([
            'tournament_id' => $this->tournament->id,
            'home_id'       => $home->id,
            'away_id'       => $away->id,
            'start_date'    => now(),
            'end_date'      => now()->addHour(),
        ]);

        $mattch = Mattch::factory()->create([
            'tournament_id' => $this->tournament->id,
            'home_id'       => $this->home->id,
            'away_id'       => $this->away->id,
            'start_date'    => now(),
            'end_date'      => now()->addHour(),
        ]);

        $this->put('/api/mattches/'.$mattch->id, [
            'home_id' => $home->id,
            'away_id' => $away->id,
        ])
        ->assertUnprocessable()
        ->assertInvalid(['tournament_id']);

        $existingMattch = Mattch::where([
            'tournament_id' => $this->tournament->id,
            'home_id'       => $home->id,
            'away_id'       => $away->id,
        ])->get();

        $this->assertCount(1, $existingMattch);
    }

    public function testGiveScore(): void
    {
        Passport::actingAs($this->admin);

        $mattch = Mattch::factory()->create([
            'tournament_id' => $this->tournament->id,
            'home_id'       => $this->home->id,
            'away_id'       => $this->away->id,
            'start_date'    => now(),
            'end_date'      => now()->addHour(),
        ]);

        // Assert that invalid achievement value should fail
        $this->post('/api/mattches/'.$mattch->id.'/give-score', [
            'score' => [
                [
                    'achievement' => Achievement::FIELD_GOAL->value.'_incorrect',
                    'home'        => 20,
                    'away'        => 25,
                ],
                [
                    'achievement' => Achievement::THREE_POINT->value,
                    'home'        => 20,
                    'away'        => 25,
                ],
            ]
        ])->assertUnprocessable();

        // Success
        $this->post('/api/mattches/'.$mattch->id.'/give-score', [
            'score' => [
                [
                    'achievement' => Achievement::FIELD_GOAL->value,
                    'home'        => 20,
                    'away'        => 25,
                ],
                [
                    'achievement' => Achievement::THREE_POINT->value,
                    'home'        => 20,
                    'away'        => 25,
                ],
            ]
        ])
        ->assertOk();

        // Assert that history will be added after update
        $score = Score::factory()->create([
            'mattch_id'   => $mattch->id,
            'achievement' => Achievement::REBOUND,
            'home_score'  => 5,
            'away_score'  => 3,
        ]);

        $response = $this->post('/api/mattches/'.$mattch->id.'/give-score', [
            'score' => [
                [
                    'achievement' => Achievement::REBOUND->value,
                    'home'        => 10,
                    'away'        => 7,
                ],
            ]
        ]);
        
        $response
            ->assertOk()
            ->assertJson([
            'tournament_id' => $this->tournament->id,
            'home_id'       => $this->home->id,
            'away_id'       => $this->away->id,
        ]);

        $jsonResponse  = json_decode($response->getContent(), true);
        $expectedScore = [
            'id'          => $score->id,
            'mattch_id'   => $mattch->id,
            'achievement' => Achievement::REBOUND->value,
            'home_score'  => 10,
            'away_score'  => 7,
            'history'     => [['home_score'  => 5, 'away_score'  => 3]]
        ];

        $found = false;
        if (isset($jsonResponse['scores']) && is_array($jsonResponse['scores'])) {
            foreach ($jsonResponse['scores'] as $score) {
                if (
                    $score['id'] === $expectedScore['id'] &&
                    $score['mattch_id'] === $expectedScore['mattch_id'] &&
                    $score['achievement'] === $expectedScore['achievement'] &&
                    $score['home_score'] === $expectedScore['home_score'] &&
                    $score['away_score'] === $expectedScore['away_score']
                ) {
                    if (isset($score['history']) && is_array($score['history'])) {
                        foreach ($score['history'] as $history) {
                            if (
                                $history['home_score'] === $expectedScore['history'][0]['home_score'] &&
                                $history['away_score'] === $expectedScore['history'][0]['away_score']
                            ) {
                                $found = true;
                                break;
                            }
                        }
                    }
                }
            }
        }
        
        $this->assertTrue($found, 'Score not found.');
    }
}
