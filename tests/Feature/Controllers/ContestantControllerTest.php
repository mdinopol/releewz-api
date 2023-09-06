<?php

namespace Tests\Feature\Controllers;

use App\Enum\ContestantType;
use App\Enum\Country;
use App\Enum\Role;
use App\Enum\Sport;
use App\Models\Contestant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ContestantControllerTest extends TestCase
{
    use RefreshDatabase;

    private Contestant $team;

    private Contestant $member;

    private User $admin;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->team = Contestant::factory()->create([
            'parent_id'       => null,
            'name'            => 'Los Angeles Lakers',
            'contestant_type' => ContestantType::TEAM,
            'sport'           => Sport::BASKETBALL,
        ]);

        $this->member = Contestant::factory()->create([
            'parent_id'       => $this->team->id,
            'name'            => 'Anthony Davis',
            'contestant_type' => ContestantType::TEAM_MEMBER,
            'sport'           => Sport::BASKETBALL,
        ]);

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
    }

    public function testList(): void
    {
        $this->get('/api/contestants')
            ->assertOk()
            ->assertJsonStructure([$this->baseColumns()]);
    }

    public function testShow(): void
    {
        $this->get('/api/contestants/'.$this->team->id)
            ->assertOk()
            ->assertJsonStructure($this->baseColumns());
    }

    public function testCreate(): void
    {
        Passport::actingAs($this->admin);

        $this->post('/api/contestants', [
            'parent_id'       => null,
            'name'            => 'Kobe Byrant',
            'contestant_type' => ContestantType::TEAM_MEMBER->value,
            'sport'           => Sport::BASKETBALL->value,
        ])
        ->assertCreated()
        ->assertJson([
            'parent_id'       => null,
            'name'            => 'Kobe Byrant',
            'contestant_type' => ContestantType::TEAM_MEMBER->value,
            'sport'           => Sport::BASKETBALL->value,
        ]);

        $this->assertDatabaseHas('contestants', [
            'parent_id'       => null,
            'name'            => 'Kobe Byrant',
            'contestant_type' => ContestantType::TEAM_MEMBER->value,
            'sport'           => Sport::BASKETBALL->value,
        ]);
    }

    public function testCreateUnauthorizeNonAdmin(): void
    {
        Passport::actingAs($this->user);

        $this->post('/api/contestants', [
            'name'            => 'Kobe Byrant',
            'alias'           => 'Black Mamba',
            'contestant_type' => ContestantType::TEAM_MEMBER->value,
            'sport'           => Sport::BASKETBALL->value,
            'active'          => true,
        ])->assertUnauthorized();
    }

    public function testCreateShouldFailIfExist(): void
    {
        Passport::actingAs($this->admin);

        $name           = 'Kobe Bryant';
        $contestantType = ContestantType::TEAM_MEMBER;
        $sport          = Sport::BASKETBALL;

        Contestant::factory()->create([
            'name'            => $name,
            'alias'           => 'Black Mamba',
            'contestant_type' => $contestantType,
            'sport'           => $sport,
            'active'          => true,
        ]);

        $this->post('/api/contestants', [
            'name'            => $name,
            'alias'           => 'Bean',
            'contestant_type' => $contestantType->value,
            'sport'           => $sport->value,
            'active'          => false,
        ])
        ->assertUnprocessable()
        ->assertInvalid(['name']);

        $contestants = Contestant::where('name', $name)
            ->where('contestant_type', $contestantType)
            ->where('sport', $sport)
            ->get();

        $this->assertCount(1, $contestants);
    }

    public function testCreateNonTeamMemberAndAssignToTeamShouldFail(): void
    {
        Passport::actingAs($this->admin);

        $team = Contestant::factory()->create([
            'parent_id'       => null,
            'name'            => 'Espanya Smashers',
            'contestant_type' => ContestantType::TEAM,
            'sport'           => Sport::TENNIS,
        ]);

        $this->post('/api/contestants', [
            'parent_id'       => $team->id,
            'name'            => 'Novak Djokovic',
            'contestant_type' => ContestantType::INDIVIDUAL->value,
            'sport'           => Sport::TENNIS->value,
        ])
        ->assertUnprocessable()
        ->assertInvalid(['parent_id']);

        $this->post('/api/contestants', [
            'parent_id'       => $team->id,
            'name'            => 'Test Team 1',
            'contestant_type' => ContestantType::TEAM->value,
            'sport'           => Sport::SOCCER->value,
        ])
        ->assertUnprocessable()
        ->assertInvalid(['parent_id']);
    }

    public function testCreateMemberAndAssignToDifferentSportTeamShouldFail(): void
    {
        Passport::actingAs($this->admin);

        $this->post('/api/contestants', [
            'parent_id'       => $this->team->id,
            'name'            => 'Novak Djokovic',
            'contestant_type' => ContestantType::TEAM_MEMBER->value,
            'sport'           => Sport::TENNIS->value,
        ])
        ->assertUnprocessable()
        ->assertInvalid(['parent_id']);
    }

    public function testCreateMemberAndAssignToNonTeamShouldFail(): void
    {
        Passport::actingAs($this->admin);

        $this->post('/api/contestants', [
            'parent_id'       => $this->member->id,
            'name'            => 'Isaih Thomas',
            'alias'           => 'Zeke',
            'contestant_type' => ContestantType::TEAM_MEMBER->value,
            'sport'           => Sport::BASKETBALL->value,
            'active'          => true,
        ])
        ->assertUnprocessable()
        ->assertInvalid(['parent_id']);
    }

    public function testUpdate(): void
    {
        Passport::actingAs($this->admin);

        $contestant = Contestant::factory()->create([
            'parent_id'       => null,
            'name'            => 'Larry Bird',
            'alias'           => 'Kodak',
            'country_code'    => Country::US->value,
            'contestant_type' => ContestantType::TEAM_MEMBER->value,
            'sport'           => Sport::BASKETBALL->value,
            'active'          => true,
        ]);

        $this->put('/api/contestants/'.$contestant->id, [
            'name' => 'Larietta Birdy',
        ])
        ->assertOk()
        ->assertJson([
            'parent_id'       => null,
            'name'            => 'Larietta Birdy',
            'alias'           => 'Kodak',
            'country_code'    => Country::US->value,
            'contestant_type' => ContestantType::TEAM_MEMBER->value,
            'sport'           => Sport::BASKETBALL->value,
            'active'          => true,
        ]);

        $this->assertDatabaseHas('contestants', [
            'parent_id'       => null,
            'name'            => 'Larietta Birdy',
            'alias'           => 'Kodak',
            'country_code'    => Country::US->value,
            'contestant_type' => ContestantType::TEAM_MEMBER->value,
            'sport'           => Sport::BASKETBALL->value,
            'active'          => true,
        ]);
    }

    public function testUpdateShouldUnauthorizeNonAdmin(): void
    {
        Passport::actingAs($this->user);

        $this->put('/api/contestants/'.$this->member->id, [
            'name' => 'Test Contestant Update',
        ])
        ->assertUnauthorized();
    }

    public function testUpdateShouldFailIfExist(): void
    {
        Passport::actingAs($this->admin);

        $name           = 'Kobe Bryant';
        $contestantType = ContestantType::TEAM_MEMBER;
        $sport          = Sport::BASKETBALL;

        Contestant::factory()->create([
            'name'            => $name,
            'alias'           => 'Black Mamba',
            'contestant_type' => $contestantType,
            'sport'           => $sport,
            'active'          => true,
        ]);

        $this->post('/api/contestants', [
            'name'            => $name,
            'alias'           => 'Bean',
            'contestant_type' => $contestantType->value,
            'sport'           => $sport->value,
            'active'          => false,
        ])
        ->assertUnprocessable()
        ->assertInvalid(['name']);

        $contestants = Contestant::where('name', $name)
            ->where('contestant_type', $contestantType)
            ->where('sport', $sport)
            ->get();

        $this->assertCount(1, $contestants);
    }

    public function testAssignNonMemberToTeamShouldFail(): void
    {
        Passport::actingAs($this->admin);

        // Master team to assign contestants
        $masterTeam = Contestant::factory()->create([
            'parent_id'       => null,
            'name'            => 'Assign - Master Team 1',
            'contestant_type' => ContestantType::TEAM,
            'sport'           => Sport::TENNIS,
        ]);

        // Team type contestant to assign to master team
        $team = Contestant::factory()->create([
            'parent_id'       => null,
            'name'            => 'Assign - Team To Assign 1',
            'contestant_type' => ContestantType::TEAM,
            'sport'           => Sport::TENNIS,
        ]);

        // Individual type contestant to assign to master team
        $individual = Contestant::factory()->create([
            'parent_id'       => null,
            'name'            => 'Assign - Individual To Assign 1',
            'contestant_type' => ContestantType::INDIVIDUAL,
            'sport'           => Sport::TENNIS,
        ]);

        $this->put('/api/contestants/'.$team->id, [
            'parent_id' => $masterTeam->id,
        ])
        ->assertUnprocessable()
        ->assertInvalid(['parent_id']);

        $this->put('/api/contestants/'.$individual->id, [
            'parent_id' => $masterTeam->id,
        ])
        ->assertUnprocessable()
        ->assertInvalid(['parent_id']);
    }

    public function testAssignToNonTeamShouldFail(): void
    {
        Passport::actingAs($this->admin);

        $individual = Contestant::factory()->create([
            'parent_id'       => null,
            'name'            => 'Individual To Assign 1',
            'contestant_type' => ContestantType::INDIVIDUAL,
            'sport'           => Sport::TENNIS,
        ]);

        $member = Contestant::factory()->create([
            'parent_id'       => null,
            'name'            => 'Member To Assign 1',
            'contestant_type' => ContestantType::TEAM_MEMBER,
            'sport'           => Sport::TENNIS,
        ]);

        $this->put('/api/contestants/'.$member->id, [
            'parent_id' => $individual->id,
        ])
        ->assertUnprocessable()
        ->assertInvalid(['parent_id']);
    }

    public function testDelete(): void
    {
        Passport::actingAs($this->admin);

        $contestant = Contestant::factory()->create([
            'parent_id'       => null,
            'name'            => 'Contestant to Delete 1',
            'contestant_type' => ContestantType::TEAM_MEMBER,
            'sport'           => Sport::TENNIS,
        ]);

        $this->delete('/api/contestants/'.$contestant->id)
            ->assertOk()
            ->assertJson([]);

        $this->assertDatabaseMissing('contestants', [
            'name'            => 'Contestant to Delete 1',
            'contestant_type' => ContestantType::TEAM_MEMBER,
            'sport'           => Sport::TENNIS,
        ]);
    }

    public function testDeleteUnauthorizeNonAdmin(): void
    {
        Passport::actingAs($this->user);

        $this->delete('/api/contestants/'.$this->team->id)
            ->assertUnauthorized();
    }

    public function testDeletingTeamWillUnbindAllOfItsMembers(): void
    {
        Passport::actingAs($this->admin);

        $team = Contestant::factory()->create([
            'parent_id'       => null,
            'name'            => 'Master Team 1',
            'contestant_type' => ContestantType::TEAM,
            'sport'           => Sport::TENNIS,
        ]);

        Contestant::factory()->create([
            'parent_id'       => $team->id,
            'name'            => 'Member of Master Team 1',
            'contestant_type' => ContestantType::TEAM_MEMBER,
            'sport'           => Sport::TENNIS,
        ]);
        Contestant::factory()->create([
            'parent_id'       => $team->id,
            'name'            => 'Member of Master Team 2',
            'contestant_type' => ContestantType::TEAM_MEMBER,
            'sport'           => Sport::TENNIS,
        ]);

        $teamMembers    = $team->members()->get();
        $hasStrayMember = false;

        foreach ($teamMembers as $member) {
            if ($member->parent_id != $team->id) {
                $hasStrayMember = true;
                break;
            }
        }

        // Assert that all members do belong to Master Team 1
        $this->assertFalse($hasStrayMember);

        // Delete team
        $this->delete('/api/contestants/'.$team->id);

        $this->assertDatabaseMissing('contestants', [
            'parent_id'       => null,
            'name'            => 'Master Team 1',
            'contestant_type' => ContestantType::TEAM,
            'sport'           => Sport::TENNIS,
        ]);

        // Assert that all members are unbinded to the deleted team
        $orphanMembers     = Contestant::whereIn('id', $teamMembers->pluck('id')->all())->get();
        $hasUnbindedMember = false;

        foreach ($orphanMembers as $member) {
            if ($member->parent_id) {
                $hasUnbindedMember = true;
                break;
            }
        }
        $this->assertFalse($hasUnbindedMember);
    }

    private function baseColumns(): array
    {
        return [
            'parent_id',
            'name',
            'alias',
            'country_code',
            'contestant_type',
            'sport',
            'active',
            'image_path',
        ];
    }
}
