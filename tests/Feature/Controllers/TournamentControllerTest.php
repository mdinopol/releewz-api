<?php

namespace Tests\Feature\Controllers;

use App\Enum\Role;
use App\Models\Tournament;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class TournamentControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $user;

    private Carbon $startDate;

    private Carbon $endDate;

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

        $this->startDate = Carbon::tomorrow();
        $this->endDate = Carbon::tomorrow()->addYear();
    }

    // test list
    public function testList(): void
    {
        Tournament::factory(2)->create();

        $this->get('/api/tournaments')
            ->assertOk()
            ->assertJsonStructure([[
                'name',
                'description',
                'start_date',
                'end_date',
            ]]);
    }
    
    // test show
    public function testShow(): void
    {
        $tournament = Tournament::factory()->create([
            'name' => 'Test Show Tournament',
            'description' => 'Test description',
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
        ]);

        $this->get('/api/tournaments/'.$tournament->id)
            ->assertOk()
            ->assertJson([
                'name' => 'Test Show Tournament',
                'description' => 'Test description',
                'start_date' => $this->startDate->jsonSerialize(),
                'end_date' => $this->endDate->jsonSerialize(),
            ]);
    }

    // test create
    public function testCreate(): void
    {
        Passport::actingAs($this->admin);
        
        $this->post('/api/tournaments', [
            'name' => 'Test Create Tournament',
            'description' => 'Test create tournament description.',
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
        ])
        ->assertCreated()
        ->assertJson([
            'name' => 'Test Create Tournament',
            'description' => 'Test create tournament description.',
            'start_date' => $this->startDate->jsonSerialize(),
            'end_date' => $this->endDate->jsonSerialize(),
        ]);

        $this->assertDatabaseHas('tournaments', [
            'name' => 'Test Create Tournament',
            'description' => 'Test create tournament description.',
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
        ]);
    }

    // test create should unauthorize non admin
    public function testCreateShouldUnauthorizeNonAdmin(): void
    {
        Passport::actingAs($this->user);

        $this->post('/api/tournaments', [
            'name' => 'Test Create Tournament',
            'description' => 'Test create tournament description.',
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
        ])
        ->assertUnauthorized();
    }

    // test create should fail if existing for specified date range
    public function testCreateShouldFailIfItExistAndOverlapsWithAnEarlierTournament(): void
    {
        Passport::actingAs($this->admin);

        // Don't use fixed/specific date since it will fail from validation (Yesterday not allowed)
        // $start = Carbon::tomorrow();
        // $end = Carbon::tomorrow()->addYear();
        // $secondStart = Carbon::tomorrow()->addDays(10); // Dummy days

        Tournament::factory()->create([
            'name' => 'Test Create Tournament',
            'description' => 'Test create tournament',
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
        ]);

        $this->post('/api/tournaments', [
            'name' => 'Test Create Tournament',
            'description' => 'Test create tournament',
            'start_date' => $this->startDate->addWeek(),
            'end_date' => $this->endDate,
        ])
        ->assertUnprocessable()
        ->assertInvalid(['name']);
    }

    public function testCreateForPastDateShouldFail(): void
    {
        Passport::actingAs($this->admin);

        $this->post('/api/tournaments', [
            'name' => 'Test Create Tournament For Past',
            'description' => 'Test create tournament for past description.',
            'start_date' => Carbon::yesterday(),
            'end_date' => Carbon::today()->addYear(),
        ])
        ->assertUnprocessable()
        ->assertInvalid(['start_date']);
    }

    public function testCreateWithEndDateLessThanStartDateShouldFail(): void
    {
        Passport::actingAs($this->admin);

        $this->post('/api/tournaments', [
            'name' => 'Test Create Tournament For Past',
            'description' => 'Test create tournament for past description.',
            'start_date' => Carbon::now(),
            'end_date' => Carbon::yesterday(),
        ])
        ->assertUnprocessable()
        ->assertInvalid(['end_date']);
    }

    // test update
    public function testUpdate(): void
    {
        Passport::actingAs($this->admin);

        $tournament = Tournament::factory()->create([
            'name' => 'Test Create Tournament',
            'description' => 'Test create tournament description.',
        ]);

        $this->put('/api/tournaments/'.$tournament->id, [
            'name' => 'Test Updated Tournament 1',
            'description' => 'Test updated tournament description.',
        ])
        ->assertOk()
        ->assertJson([
            'name' => 'Test Updated Tournament 1',
            'description' => 'Test updated tournament description.',
        ]);

        $this->assertDatabaseHas('tournaments', [
            'name' => 'Test Updated Tournament 1',
            'description' => 'Test updated tournament description.',
        ]);
    }

    // test update should unauthorize non admin
    public function testUpdateShouldUnauthorizeNonAdmin(): void
    {
        Passport::actingAs($this->user);

        $tournament = Tournament::factory()->create([
            'name' => 'Test Create Tournament',
            'description' => 'Test create tournament description.',
        ]);

        $this->put('/api/tournaments/'.$tournament->id, [
            'name' => 'Test Updated Tournament 1',
            'description' => 'Test updated tournament description.',
        ])
        ->assertUnauthorized();
    }

    // test update should fail if existing for specified date
    public function testUpdateShouldFailIfItExistAndOverlapsWithAnEarlierTournament(): void
    {
        Passport::actingAs($this->admin);

        // Don't use fixed/specific date since it will fail from validation (Yesterday not allowed)
        $start = Carbon::tomorrow();
        $end = Carbon::tomorrow()->addYear();
        $secondStart = Carbon::tomorrow()->addDays(10); // Dummy days

        $tournament = Tournament::factory()->create([
            'name' => 'Test Create Tournament 1',
            'description' => 'Test create tournament 1 description.',
            'start_date' => $start,
            'end_date' => $end,
        ]);

        $this->put('/api/tournaments/'.$tournament->id, [
            'name' => 'Test Create Tournament 1',
            'description' => 'Test create tournament 1 description.',
            'start_date' => $secondStart,
            'end_date' => $end,
        ])
        ->assertUnprocessable()
        ->assertInvalid(['name']);
    }

    // test delete
    public function testDelete(): void
    {
        Passport::actingAs($this->admin);

        $start = Carbon::tomorrow();
        $end = $start->addYear();

        $tournament = Tournament::factory()->create([
            'name' => 'Test Tournament Delete 1',
            'description' => 'The tournament to delete 1.',
            'start_date' => $start,
            'end_date' => $end,
        ]);

        $this->delete('/api/tournaments/'.$tournament->id)
            ->assertOk()
            ->assertJson([]);

        $this->assertDatabaseMissing('tournaments', [
            'name' => 'Test Tournament Delete 1',
            'description' => 'The tournament to delete 1.',
            'start_date' => $start,
            'end_date' => $end,
        ]);
    }

    // test delete should unauthorize non admin
    public function testDeleteShouldUnauthorizeNonAdmin(): void
    {
        Passport::actingAs($this->user);

        $tournament = Tournament::factory()->create([
            'name' => 'Test Tournament Delete 1',
            'description' => 'The tournament to delete 1.',
        ]);

        $this->delete('/api/tournaments/'.$tournament->id)
            ->assertUnauthorized();
    }

    // test delete should fail if there is at least one game attached to it
    // TO DO: After creating game crud
    // public function testDeleteShouldFailIfThereIsLinkedGame(): void
    // {
    //     Passport::actingAs($this->user);

    //     $tournament = Tournament::factory()->create([
    //         'name' => 'Test Tournament Delete 1',
    //         'description' => 'The tournament to delete 1.',
    //     ]);

    // }
}
