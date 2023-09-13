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
            Achievement::WIN->name,
        ],
        'fillables' => [
            'basic' => [
                Achievement::SCORE->name => [
                    Achievement::FIELD_GOAL->name => [
                        Achievement::TWO_POINT->name,
                        Achievement::THREE_POINT->name,
                    ],
                    Achievement::FREE_THROW->name,
                ],
                Achievement::REBOUND->name => [
                    Achievement::OFFENSIVE_REBOUND->name,
                    Achievement::DEFFENSIVE_REBOUND->name,
                ],
                Achievement::ASSIST->name,
                Achievement::STEAL->name,
                Achievement::BLOCK->name,
                Achievement::TURNOVER->name,
                Achievement::FOUL->name,
            ],
            'range' => [],
        ],
        'extras' => [
            Achievement::CHAMPION->name,
            Achievement::MOST_VALUABLE_PLAYER->name,
            Achievement::ROOKIE_OF_THE_YEAR->name,
            Achievement::DEFENSIVE_PLAYER_OF_THE_YEAR->name,
            Achievement::MOST_IMPROVED_PLAYER_OF_THE_YEAR->name,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Soccer
    |--------------------------------------------------------------------------
    */
    Sport::SOCCER->name => [
        'decisions' => [
            Achievement::WIN->name,
            Achievement::DRAW->name,
            Achievement::CLEAN_SHEET->name,
        ],
        'fillables' => [
            'basic' => [
                Achievement::GOAL->name,
                Achievement::GOAL_ATTEMPT->name,
                Achievement::CORNER_KICK->name,
                Achievement::SAVE->name => [
                    Achievement::GOAL_SAVE->name,
                    Achievement::PENALTY_SAVE->name,
                ],
                Achievement::RED_CARD->name,
                Achievement::YELLOW_CARD->name,
            ],
            'range' => [
                Achievement::BALL_POSSESSION->name,
            ],
        ],
        'extras' => [
            Achievement::CHAMPION->name,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Tennis
    |--------------------------------------------------------------------------
    */
    Sport::TENNIS->value => [
        'decisions' => [
            Achievement::WIN->name,
            Achievement::SET_DIFFERENCE->name,
        ],
        'fillables' => [
            'basic' => [
                Achievement::GAME_WON->name,
                Achievement::ACES->name,
                Achievement::DOUBLE_FAULT->name,
                Achievement::TIE_BREAKER_WON->name,
            ],
            'range' => [
                Achievement::FIRST_SERVE->name,
                Achievement::RECEIVING_POINT->name,
                Achievement::BREAK_POINT->name,
                Achievement::POINTS_IN_ROW->name,
            ],
        ],
        'extras' => [
            Achievement::CHAMPION->name,
        ],
    ],
];
