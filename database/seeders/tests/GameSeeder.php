<?php

namespace Database\Seeders\Tests;

use App\Enum\ContestantType;
use App\Enum\GameDuration;
use App\Enum\GameState;
use App\Enum\Sport;
use App\Models\Contestant;
use App\Models\Game;
use App\Models\Tournament;
use App\Services\GameService;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    public function run(): void
    {
        $sports = Sport::active();

        foreach ($sports as $sport) {
            $tournament = Tournament::factory()->create();

            // Create game variations for every contestant types
            foreach (ContestantType::cases() as $contestantType) {
                $budgets = $this->budgets($contestantType, $sport);

                // OPEN REGISTRATION
                $forOpenRegistrationSpan = Game::factory()->create([
                    'tournament_id'   => $tournament->id,
                    'sport'           => $sport,
                    'duration_type'   => GameDuration::SPAN,
                    'contestant_type' => $contestantType,
                ]);
                $forOpenRegistrationDaily = Game::factory()->create([
                    'tournament_id'   => $tournament->id,
                    'sport'           => $sport,
                    'duration_type'   => GameDuration::DAILY,
                    'start_date'      => now()->startOfDay(),
                    'end_date'        => now()->endOfDay(),
                    'contestant_type' => $contestantType,
                ]);
                // Sync startlist
                app(GameService::class)->syncStartlist($forOpenRegistrationSpan, $budgets);
                app(GameService::class)->syncStartlist($forOpenRegistrationDaily, $budgets);

                // Update game state
                app(GameService::class)->updateGameState($forOpenRegistrationSpan, GameState::OPEN_REGISTRATION);
                app(GameService::class)->updateGameState($forOpenRegistrationDaily, GameState::OPEN_REGISTRATION);

                // LIVE
                $forLiveSpan = Game::factory()->create([
                    'tournament_id'   => $tournament->id,
                    'sport'           => $sport,
                    'duration_type'   => GameDuration::SPAN,
                    'contestant_type' => $contestantType,
                ]);
                $forLiveDaily = Game::factory()->create([
                    'tournament_id'   => $tournament->id,
                    'sport'           => $sport,
                    'duration_type'   => GameDuration::DAILY,
                    'start_date'      => now()->startOfDay(),
                    'end_date'        => now()->endOfDay(),
                    'contestant_type' => $contestantType,
                ]);
                // Sync startlist
                app(GameService::class)->syncStartlist($forLiveSpan, $budgets);
                app(GameService::class)->syncStartlist($forLiveDaily, $budgets);

                // Update game state
                app(GameService::class)->updateGameState($forLiveSpan, GameState::LIVE);
                app(GameService::class)->updateGameState($forLiveDaily, GameState::LIVE);
            }
        }
    }

    private function budgets(ContestantType $contestantType, Sport $sport): array
    {
        $contestants = [];

        if ($contestantType === ContestantType::TEAM_MEMBER) {
            $team = Contestant::factory()->create([
                'contestant_type' => ContestantType::TEAM,
                'sport'           => $sport,
            ]);

            $contestants = Contestant::factory(20)->create([
                'parent_id'       => $team,
                'contestant_type' => ContestantType::TEAM_MEMBER,
            ])->pluck('id');
        }

        $contestants = Contestant::factory(20)->create([
            'contestant_type' => $contestantType,
            'sport'           => $sport,
        ])->pluck('id');
        
        $budgets = [];

        foreach ($contestants as $contestant) {
            $budgets[$contestant] = ['id' => $contestant, 'value' => fake()->randomFloat('2', 10, 50)];
        }

        return $budgets;
    }
}
