<?php

namespace App\Data;

use App\Models\Game;

class PointTemplate
{
    public Game $game;

    public array $decisions;

    public array $fillables;

    public array $extras = [];

    public function __construct(Game $game, array $decisions, array $fillables, array $extras = [])
    {
        $this->game      = $game;
        $this->decisions = $decisions;
        $this->fillables = $fillables;
        $this->extras    = $extras;
    }

    public function isValid(): bool
    {
        $rawTemplate = $this->game->sport->template();

        if (!$this->game->sport->active()) {
            return false;
        }

        $rawAchievements   = [];
        $rawAchievements[] = array_keys($rawTemplate['decisions']);
        $rawAchievements[] = array_keys($rawTemplate['fillables']['basic']);
        $rawAchievements[] = array_keys($rawTemplate['fillables']['range']);
        $rawAchievements[] = array_keys($rawTemplate['extras']);

        $filledAchievements   = [];
        $filledAchievements[] = array_keys($this->decisions);
        $filledAchievements[] = array_keys($this->fillables['basic']);
        $filledAchievements[] = array_keys($this->fillables['range']);
        $filledAchievements[] = array_keys($this->extras);

        return $rawAchievements === $filledAchievements;
    }

    public function format(): array
    {
        return [
            'decisions' => $this->decisions,
            'fillables' => $this->fillables,
            'extras'    => $this->extras,
        ];
    }
}
