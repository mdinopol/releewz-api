<?php

namespace Database\Seeders\Tests;

use App\Enum\GameState;
use App\Enum\Sport;
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

            // OPEN REGISTRATION
            $forOpenRegistration = Game::factory()->create([
                'tournament_id' => $tournament->id,
                'sport'         => $sport,
            ]);
            app(GameService::class)->updateGameState($forOpenRegistration, GameState::OPEN_REGISTRATION);

            // LIVE
            $forLive = Game::factory()->create([
                'tournament_id' => $tournament->id,
                'sport'         => $sport,
            ]);
            app(GameService::class)->updateGameState($forLive, GameState::LIVE);
        }
    }
}
