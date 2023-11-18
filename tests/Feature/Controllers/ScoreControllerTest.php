<?php

namespace Tests\Feature\Controllers;

use App\Enum\Achievement;
use App\Enum\Role;
use App\Models\Mattch;
use App\Models\Score;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ScoreControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $user;

    private Mattch $mattch;

    private Score $score;

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

        $this->mattch = Mattch::factory()->create();

        $this->score = Score::factory()->create([
            'mattch_id' => Mattch::factory()->create()->id,
        ]);
    }

    public function testCreate(): void
    {
        Passport::actingAs($this->admin);

        $achievement = Achievement::random();

        $this->post('/api/scores', [
            'mattch_id'  => $this->mattch->id,
            'achievement' => $achievement->value,
            'home_score'  => 5,
            'home_points' => 20,
            'away_score'  => 3,
            'away_points' => 15,
        ])
        ->assertCreated()
        ->assertJson([
            'mattch_id'  => $this->mattch->id,
            'achievement' => $achievement->value,
            'home_score'  => 5,
            'home_points' => 20,
            'away_score'  => 3,
            'away_points' => 15,
        ]);

        $this->assertDatabaseHas('scores', [
            'mattch_id'  => $this->mattch->id,
            'achievement' => $achievement->value,
            'home_score'  => 5,
            'home_points' => 20,
            'away_score'  => 3,
            'away_points' => 15,
        ]);
    }

    public function testCreateDuplicateShouldFail(): void
    {
        Passport::actingAs($this->admin);

        $achievement = Achievement::random();
        Score::factory()->create([
            'mattch_id'  => $this->mattch->id,
            'achievement' => $achievement->value,
            'home_score'  => 5,
            'home_points' => 20,
            'away_score'  => 3,
            'away_points' => 15,
        ]);

        $this->post('/api/scores', [
            'mattch_id'  => $this->mattch->id,
            'achievement' => $achievement->value,
            'home_score'  => 1,
            'home_points' => 2,
            'away_score'  => 3,
            'away_points' => 4,
        ])
        ->assertUnprocessable();

        $score = Score::where([
            'mattch_id'  => $this->mattch->id,
            'achievement' => $achievement->value,
        ])->get();

        $this->assertCount(1, $score);

        $this->assertDatabaseHas('scores', [
            'mattch_id'  => $this->mattch->id,
            'achievement' => $achievement->value,
            'home_score'  => 5,
            'home_points' => 20,
            'away_score'  => 3,
            'away_points' => 15,
        ]);

        $this->assertDatabaseMissing('scores', [
            'mattch_id'  => $this->mattch->id,
            'achievement' => $achievement->value,
            'home_score'  => 1,
            'home_points' => 2,
            'away_score'  => 3,
            'away_points' => 4,
        ]);
    }

    public function testCreateUnauthorizeNonAdmin(): void
    {
        Passport::actingAs($this->user);

        $achievement = Achievement::random();

        $this->post('/api/scores', [
            'mattch_id'  => $this->mattch->id,
            'achievement' => $achievement->value,
            'home_score'  => 5,
            'home_points' => 20,
            'away_score'  => 3,
            'away_points' => 15,
        ])
        ->assertUnauthorized();
    }

    public function testUpdate(): void
    {
        Passport::actingAs($this->admin);

        $previousValues = [
            'home_score'  => floatval($this->score->home_score),
            'home_points' => floatval($this->score->home_points),
            'away_score'  => floatval($this->score->away_score),
            'away_points' => floatval($this->score->away_points),
            'updated_at'  => $this->score->updated_at->jsonSerialize(),
        ];

        $this->put('/api/scores/'.$this->score->id, [
            'home_score'  => 10,
            'home_points' => 50,
            'away_score'  => 5,
            'away_points' => 25,
        ])
        ->assertOk()
        ->assertJson([
            'mattch_id'  => $this->score->mattch_id,
            'achievement' => $this->score->achievement->value,
            'home_score'  => 10,
            'home_points' => 50,
            'away_score'  => 5,
            'away_points' => 25,
            'history'     => [$previousValues],
        ]);
    }

    public function testUpdateDuplicateShouldFail(): void
    {
        Passport::actingAs($this->admin);

        Score::factory()->create([
            'mattch_id'  => $this->mattch->id,
            'achievement' => Achievement::ACES->value,
            'home_score'  => 5,
            'home_points' => 20,
            'away_score'  => 3,
            'away_points' => 15,
        ]);

        $score = Score::factory()->create([
            'mattch_id'  => $this->mattch->id,
            'achievement' => Achievement::ASSIST->value,
            'home_score'  => 5,
            'home_points' => 20,
            'away_score'  => 3,
            'away_points' => 15,
        ]);

        $this->put('/api/scores/'.$score->id, [
            'achievement' => Achievement::ACES->value,
        ])
        ->assertUnprocessable()
        ->assertInvalid(['achievement']);

        $this->assertDatabaseHas('scores', [
            'mattch_id'  => $this->mattch->id,
            'achievement' => Achievement::ACES->value,
            'home_score'  => 5,
            'home_points' => 20,
            'away_score'  => 3,
            'away_points' => 15,
        ]);

        $this->assertDatabaseHas('scores', [
            'mattch_id'  => $this->mattch->id,
            'achievement' => Achievement::ASSIST->value,
            'home_score'  => 5,
            'home_points' => 20,
            'away_score'  => 3,
            'away_points' => 15,
        ]);
    }

    public function testUpdateUnauthorizeNonAdmin(): void
    {
        Passport::actingAs($this->user);

        $this->put('/api/scores/'.$this->score->id, [
            'home_score' => 10,
        ])
        ->assertUnauthorized();
    }
}
