<?php

use App\Enum\Achievement;
use App\Enum\Sport;

return [
    /*
    |--------------------------------------------------------------------------
    | All applicable achievements for each sports
    |--------------------------------------------------------------------------
    |
    | 'decisions'       End-game achievements derived from the final score.
    | 'fillables'       Achievements that will be shown in the game's result form.
    | 'fillables.basic' Achievements whose score is 1:1 ().
    | 'fillables.range' Achievements whose score is determined within specified ranges.
    | 'extras'          Additional predictions, mostly applicable for SPAN-type games.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Basketball
    |--------------------------------------------------------------------------
    */
    Sport::BASKETBALL->value => [
        'decisions' => [
            Achievement::WIN->value,
        ],
        'fillables' => [
            'basic' => [
                Achievement::SCORE->value => [
                    Achievement::FIELD_GOAL->value => [
                        Achievement::TWO_POINT->value,
                        Achievement::THREE_POINT->value,
                    ],
                    Achievement::FREE_THROW->value,
                ],
                Achievement::REBOUND->value => [
                    Achievement::OFFENSIVE_REBOUND->value,
                    Achievement::DEFFENSIVE_REBOUND->value,
                ],
                Achievement::ASSIST->value,
                Achievement::STEAL->value,
                Achievement::BLOCK->value,
                Achievement::TURNOVER->value,
                Achievement::FOUL->value,
            ],
            'range' => [],
        ],
        'extras' => [
            Achievement::CHAMPION->value,
            Achievement::MOST_VALUABLE_PLAYER->value,
            Achievement::ROOKIE_OF_THE_YEAR->value,
            Achievement::DEFENSIVE_PLAYER_OF_THE_YEAR->value,
            Achievement::MOST_IMPROVED_PLAYER_OF_THE_YEAR->value,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Soccer
    |--------------------------------------------------------------------------
    */
    Sport::SOCCER->value => [
        'decisions' => [
            Achievement::WIN->value,
            Achievement::DRAW->value,
            Achievement::CLEAN_SHEET->value,
        ],
        'fillables' => [
            'basic' => [
                Achievement::GOAL->value,
                Achievement::GOAL_ATTEMPT->value,
                Achievement::CORNER_KICK->value,
                Achievement::SAVE->value => [
                    Achievement::GOAL_SAVE->value,
                    Achievement::PENALTY_SAVE->value,
                ],
                Achievement::RED_CARD->value,
                Achievement::YELLOW_CARD->value,
            ],
            'range' => [
                Achievement::BALL_POSSESSION->value,
            ],
        ],
        'extras' => [
            Achievement::CHAMPION->value,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Tennis
    |--------------------------------------------------------------------------
    */
    Sport::TENNIS->value => [
        'decisions' => [
            Achievement::WIN->value,
            Achievement::SET_DIFFERENCE->value,
        ],
        'fillables' => [
            'basic' => [
                Achievement::GAME_WON->value,
                Achievement::ACES->value,
                Achievement::DOUBLE_FAULT->value,
                Achievement::TIE_BREAKER_WON->value,
            ],
            'range' => [
                Achievement::FIRST_SERVE->value,
                Achievement::RECEIVING_POINT->value,
                Achievement::BREAK_POINT->value,
                Achievement::POINTS_IN_ROW->value,
            ],
        ],
        'extras' => [
            Achievement::CHAMPION->value,
        ],
    ],
];
