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
    | 'fillables.basic' Achievements whose score is 1:1.
    | 'fillables.range' Achievements whose score is determined within specified ranges/percentage-based.
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
            Achievement::WIN->value => null,
        ],
        'fillables' => [
            'basic' => [
                Achievement::SCORE->value    => null,
                Achievement::REBOUND->value  => null,
                Achievement::ASSIST->value   => null,
                Achievement::STEAL->value    => null,
                Achievement::BLOCK->value    => null,
                Achievement::TURNOVER->value => null,
                Achievement::FOUL->value     => null,
            ],
            'range' => [
                Achievement::FIELD_GOAL->value  => [
                    ['min' => 0, 'max' => 43],
                    ['min' => 44, 'max' => 46],
                    ['min' => 47, 'max' => 50],
                ],
                Achievement::THREE_POINT->value => [
                    ['min' => 0, 'max' => 34],
                    ['min' => 35, 'max' => 36],
                    ['min' => 37, 'max' => 39],
                    ['min' => 40, 'max' => PHP_INT_MAX],
                ],
                Achievement::FREE_THROW->value  => [
                    ['min' => 0, 'max' => 70],
                    ['min' => 71, 'max' => 75],
                    ['min' => 76, 'max' => 81],
                    ['min' => 82, 'max' => PHP_INT_MAX],
                ],
            ],
        ],
        'extras' => [
            Achievement::CHAMPION->value                         => null,
            Achievement::MOST_VALUABLE_PLAYER->value             => null,
            Achievement::ROOKIE_OF_THE_YEAR->value               => null,
            Achievement::DEFENSIVE_PLAYER_OF_THE_YEAR->value     => null,
            Achievement::MOST_IMPROVED_PLAYER_OF_THE_YEAR->value => null,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Soccer
    |--------------------------------------------------------------------------
    */
    Sport::SOCCER->value => [
        'decisions' => [
            Achievement::WIN->value         => null,
            Achievement::DRAW->value        => null,
            Achievement::CLEAN_SHEET->value => null,
        ],
        'fillables' => [
            'basic' => [
                Achievement::GOAL->value         => null,
                Achievement::GOAL_ATTEMPT->value => null,
                Achievement::CORNER_KICK->value  => null,
                Achievement::GOAL_SAVE->value    => null,
                Achievement::PENALTY_SAVE->value => null,
                Achievement::RED_CARD->value     => null,
                Achievement::YELLOW_CARD->value  => null,
            ],
            'range' => [
                Achievement::BALL_POSSESSION->value => [
                    ['min' => 60, 'max' => PHP_INT_MAX],
                ],
            ],
        ],
        'extras' => [
            Achievement::CHAMPION->value => null,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Tennis
    |--------------------------------------------------------------------------
    */
    Sport::TENNIS->value => [
        'decisions' => [
            Achievement::WIN->value            => null,
            Achievement::SET_DIFFERENCE->value => null,
        ],
        'fillables' => [
            'basic' => [
                Achievement::GAME_WON->value        => null,
                Achievement::ACES->value            => null,
                Achievement::DOUBLE_FAULT->value    => null,
                Achievement::TIE_BREAKER_WON->value => null,
            ],
            'range' => [
                Achievement::FIRST_SERVE->value     => [
                    ['min' => 5, 'max' => 50],
                    ['min' => 51, 'max' => 60],
                    ['min' => 61, 'max' => 70],
                    ['min' => 71, 'max' => 80],
                    ['min' => 81, 'max' => PHP_INT_MAX],
                ],
                Achievement::RECEIVING_POINT->value => [
                    ['min' => 5, 'max' => 10],
                    ['min' => 11, 'max' => 20],
                    ['min' => 21, 'max' => 30],
                    ['min' => 31, 'max' => 40],
                    ['min' => 41, 'max' => PHP_INT_MAX],
                ],
                Achievement::BREAK_POINT->value     => [
                    ['min' => 5, 'max' => 25],
                    ['min' => 26, 'max' => 50],
                    ['min' => 51, 'max' => 75],
                    ['min' => 76, 'max' => 100],
                ],
                Achievement::POINTS_IN_ROW->value   => [
                    ['min' => 5, 'max' => 9],
                    ['min' => 10, 'max' => PHP_INT_MAX],
                ],
            ],
        ],
        'extras' => [
            Achievement::CHAMPION->value => null,
        ],
    ],
];
