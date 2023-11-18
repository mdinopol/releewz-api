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

        $this->startDate = Carbon::today();
        $this->endDate   = Carbon::today()->addYear();
    }

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

    public function testShow(): void
    {
        $tournament = Tournament::factory()->create([
            'name'        => 'Test Show Tournament',
            'description' => 'Test description',
            'start_date'  => $this->startDate,
            'end_date'    => $this->endDate,
        ]);

        $this->get('/api/tournaments/'.$tournament->id)
            ->assertOk()
            ->assertJson([
                'name'        => 'Test Show Tournament',
                'description' => 'Test description',
                'start_date'  => $this->startDate->jsonSerialize(),
                'end_date'    => $this->endDate->jsonSerialize(),
                'games'       => [],
                'mattches'    => [],
            ]);
    }

    public function testCreate(): void
    {
        Passport::actingAs($this->admin);

        $this->post('/api/tournaments', [
            'name'        => 'Test Create Tournament',
            'description' => 'Test create tournament description.',
            'start_date'  => $this->startDate,
            'end_date'    => $this->endDate,
        ])
        ->assertCreated()
        ->assertJson([
            'name'        => 'Test Create Tournament',
            'description' => 'Test create tournament description.',
            'start_date'  => $this->startDate->jsonSerialize(),
            'end_date'    => $this->endDate->jsonSerialize(),
        ]);

        $this->assertDatabaseHas('tournaments', [
            'name'        => 'Test Create Tournament',
            'description' => 'Test create tournament description.',
            'start_date'  => $this->startDate,
            'end_date'    => $this->endDate,
        ]);
    }

    public function testCreateShouldUnauthorizeNonAdmin(): void
    {
        Passport::actingAs($this->user);

        $this->post('/api/tournaments', [
            'name'        => 'Test Create Tournament',
            'description' => 'Test create tournament description.',
            'start_date'  => $this->startDate,
            'end_date'    => $this->endDate,
        ])
        ->assertUnauthorized();
    }

    public function testCreateShouldFailIfItExist(): void
    {
        Passport::actingAs($this->admin);

        Tournament::factory()->create([
            'name'        => 'Test Create Tournament',
            'description' => 'Test create tournament',
            'start_date'  => $this->startDate,
            'end_date'    => $this->endDate,
        ]);

        $this->post('/api/tournaments', [
            'name'        => 'Test Create Tournament',
            'description' => 'Test create tournament',
            'start_date'  => $this->startDate->addWeek(),
            'end_date'    => $this->endDate,
        ])
        ->assertUnprocessable()
        ->assertInvalid(['name']);
    }

    public function testCreateForPastDateShouldFail(): void
    {
        Passport::actingAs($this->admin);

        $this->post('/api/tournaments', [
            'name'        => 'Test Create Tournament For Past',
            'description' => 'Test create tournament for past description.',
            'start_date'  => Carbon::yesterday(),
            'end_date'    => Carbon::today()->addYear(),
        ])
        ->assertUnprocessable()
        ->assertInvalid(['start_date']);
    }

    public function testCreateWithEndDateLessThanStartDateShouldFail(): void
    {
        Passport::actingAs($this->admin);

        $this->post('/api/tournaments', [
            'name'        => 'Test Create Tournament For Past',
            'description' => 'Test create tournament for past description.',
            'start_date'  => Carbon::now(),
            'end_date'    => Carbon::yesterday(),
        ])
        ->assertUnprocessable()
        ->assertInvalid(['end_date']);
    }

    public function testUpdate(): void
    {
        Passport::actingAs($this->admin);

        $tournament = Tournament::factory()->create([
            'name'        => 'Test Create Tournament',
            'description' => 'Test create tournament description.',
            'start_date'  => $this->startDate,
            'end_date'    => $this->endDate,
        ]);

        $this->put('/api/tournaments/'.$tournament->id, [
            'name'        => 'Test Updated Tournament 1',
            'description' => 'Test updated tournament description.',
        ])
        ->assertOk()
        ->assertJson([
            'name'        => 'Test Updated Tournament 1',
            'description' => 'Test updated tournament description.',
        ]);

        $this->assertDatabaseHas('tournaments', [
            'name'        => 'Test Updated Tournament 1',
            'description' => 'Test updated tournament description.',
        ]);
    }

    public function testUpdateShouldUnauthorizeNonAdmin(): void
    {
        Passport::actingAs($this->user);

        $tournament = Tournament::factory()->create([
            'name'        => 'Test Create Tournament',
            'description' => 'Test create tournament description.',
        ]);

        $this->put('/api/tournaments/'.$tournament->id, [
            'name'        => 'Test Updated Tournament 1',
            'description' => 'Test updated tournament description.',
        ])
        ->assertUnauthorized();
    }

    public function testUpdateShouldFailIfItExist(): void
    {
        Passport::actingAs($this->admin);

        $tournament = Tournament::factory()->create([
            'name'        => 'Test Create Tournament 1',
            'description' => 'Test create tournament 1 description.',
            'start_date'  => $this->startDate,
            'end_date'    => $this->endDate,
        ]);

        $this->put('/api/tournaments/'.$tournament->id, [
            'name'        => 'Test Create Tournament 1',
            'description' => 'Test create tournament 1 description new.',
            'start_date'  => $this->startDate,
            'end_date'    => $this->endDate,
        ])
        ->assertUnprocessable()
        ->assertInvalid(['name']);
    }

    public function testDelete(): void
    {
        Passport::actingAs($this->admin);

        $start = Carbon::tomorrow();
        $end   = $start->addYear();

        $tournament = Tournament::factory()->create([
            'name'        => 'Test Tournament Delete 1',
            'description' => 'The tournament to delete 1.',
            'start_date'  => $start,
            'end_date'    => $end,
        ]);

        $this->delete('/api/tournaments/'.$tournament->id)
            ->assertOk()
            ->assertJson([]);

        $this->assertDatabaseMissing('tournaments', [
            'name'        => 'Test Tournament Delete 1',
            'description' => 'The tournament to delete 1.',
            'start_date'  => $start,
            'end_date'    => $end,
        ]);
    }

    public function testDeleteShouldUnauthorizeNonAdmin(): void
    {
        Passport::actingAs($this->user);

        $tournament = Tournament::factory()->create([
            'name'        => 'Test Tournament Delete 1',
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
